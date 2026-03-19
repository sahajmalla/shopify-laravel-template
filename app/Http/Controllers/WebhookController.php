<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\ShopifyAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopify\App\Types\ResultForReq;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    /**
     * Handle app/uninstalled webhook.
     */
    public function uninstalled(Request $request): Response
    {
        $result = $this->verifyWebhook($request);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        Shop::where('shop', $result->shop)->delete();

        return response('', 204);
    }

    /**
     * GDPR: customers/data_request – log and return 200 (no customer data stored by default).
     */
    public function customersDataRequest(Request $request): Response
    {
        $result = $this->verifyWebhook($request);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        Log::info('GDPR customers/data_request received', [
            'shop' => $result->shop,
            'payload' => $request->all(),
        ]);

        return response('', 200);
    }

    /**
     * GDPR: customers/redact – log and return 200 (no customer data stored by default).
     */
    public function customersRedact(Request $request): Response
    {
        $result = $this->verifyWebhook($request);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        Log::info('GDPR customers/redact received', [
            'shop' => $result->shop,
            'payload' => $request->all(),
        ]);

        return response('', 200);
    }

    /**
     * GDPR: shop/redact – delete shop/token records and return 200.
     */
    public function shopRedact(Request $request): Response
    {
        $result = $this->verifyWebhook($request);
        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        Shop::where('shop', $result->shop)->delete();

        return response('', 200);
    }

    private function verifyWebhook(Request $request): ResultForReq
    {
        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        return $shopify->verifyWebhookReq($req);
    }
}
