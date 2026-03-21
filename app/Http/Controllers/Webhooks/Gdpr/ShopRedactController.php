<?php

namespace App\Http\Controllers\Webhooks\Gdpr;

use App\Http\Controllers\BaseWebhookController;
use App\Models\Shop;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopRedactController extends BaseWebhookController
{
    public function __invoke(Request $request): Response
    {
        $result = $this->webhookResult($request);

        Shop::where('shop', $result->shop)->delete();

        return response('', 200);
    }
}
