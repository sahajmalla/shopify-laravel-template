<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ShopifyAppService;
use Illuminate\Http\Request;

class PatchIdTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);
        $result = $shopify->appHomePatchIdToken($req);

        return ShopifyAppService::resultToResponse($result);
    }
}
