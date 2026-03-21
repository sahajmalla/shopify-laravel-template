<?php

namespace App\Http\Middleware;

use App\Services\ShopifyAppService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyShopifyWebhook
{
    /**
     * Verify Shopify webhook requests and attach the verification result to the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        $result = $shopify->verifyWebhookReq($req);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        $request->attributes->set('shopify_webhook', $result);

        return $next($request);
    }
}
