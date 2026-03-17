<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\ShopifyAppService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Handle app/uninstalled webhook.
     */
    public function uninstalled(Request $request)
    {
        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        $result = $shopify->verifyWebhookReq($req);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        // Remove shop and access tokens when app is uninstalled
        Shop::where('shop', $result->shop)->delete();

        return response('', 204);
    }
}
