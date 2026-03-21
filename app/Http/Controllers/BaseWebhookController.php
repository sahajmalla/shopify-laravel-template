<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Shopify\App\Types\ResultForReq;

abstract class BaseWebhookController extends Controller
{
    protected function webhookResult(Request $request): ResultForReq
    {
        $result = $request->attributes->get('shopify_webhook');
        if (! $result instanceof ResultForReq) {
            abort(400, 'Missing webhook verification result.');
        }

        return $result;
    }
}
