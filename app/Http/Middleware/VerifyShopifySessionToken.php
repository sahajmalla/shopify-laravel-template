<?php

namespace App\Http\Middleware;

use App\Services\ShopifyAppService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyShopifySessionToken
{
    /**
     * Verify the request using session token (Authorization: Bearer &lt;token&gt;).
     * On failure returns 401 with X-Shopify-Retry-Invalid-Session-Request when appropriate.
     * On success attaches shop, userId, and idToken to the request for downstream handlers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization', '');
        if ($authHeader === '' || ! str_starts_with($authHeader, 'Bearer ')) {
            return response('Unauthorized', 401)->withHeaders([
                'X-Shopify-Retry-Invalid-Session-Request' => '1',
            ]);
        }

        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        $result = $shopify->verifyAppHomeReq(
            $req,
            appHomePatchIdTokenPath: config('shopify.app_home_patch_id_token_path', '/auth/patch-id-token'),
        );

        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        $request->attributes->set('shop', $result->shop);
        $request->attributes->set('userId', $result->userId);
        $request->attributes->set('idToken', $result->idToken);

        return $next($request);
    }
}
