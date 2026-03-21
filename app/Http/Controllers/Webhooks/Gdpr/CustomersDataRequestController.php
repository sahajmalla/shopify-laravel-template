<?php

namespace App\Http\Controllers\Webhooks\Gdpr;

use App\Http\Controllers\BaseWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CustomersDataRequestController extends BaseWebhookController
{
    public function __invoke(Request $request): Response
    {
        $result = $this->webhookResult($request);

        Log::info('GDPR customers/data_request received', [
            'shop' => $result->shop,
            'payload' => $request->all(),
        ]);

        return response('', 200);
    }
}
