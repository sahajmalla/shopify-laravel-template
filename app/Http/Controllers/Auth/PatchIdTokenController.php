<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ShopifyAppService;
use Illuminate\Http\Request;

class PatchIdTokenController extends Controller
{
    /**
     * Shopify loads embedded apps without an id_token in some flows.
     * This endpoint returns a patch page that fetches a fresh token and reloads the iframe.
     */
    public function __invoke(Request $request, ShopifyAppService $service)
    {
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);
        $result = $shopify->appHomePatchIdToken($req);

        return ShopifyAppService::resultToResponse($result);
    }
}
