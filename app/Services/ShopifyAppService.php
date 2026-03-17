<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Shopify\App\ShopifyApp;

class ShopifyAppService
{
    protected ShopifyApp $shopify;

    public function __construct()
    {
        $this->shopify = new ShopifyApp(
            clientId: config('shopify.api_key'),
            clientSecret: config('shopify.api_secret'),
            oldClientSecret: config('shopify.old_api_secret'),
        );
    }

    /**
     * Convert a Laravel Request to the format expected by shopify-app-php.
     */
    public static function requestToShopifyReq(Request $request): array
    {
        return [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'url' => $request->fullUrl(),
            'body' => $request->getContent(),
        ];
    }

    /**
     * Convert a result from the package to a Laravel Response.
     * Accepts both array and object results from shopify-app-php.
     */
    public static function resultToResponse(array|object $result): Response
    {
        $log = is_array($result) ? $result['log'] : $result->log;
        $code = is_array($log) ? $log['code'] : $log->code;
        $detail = is_array($log) ? $log['detail'] : $log->detail;
        Log::info("{$code} - {$detail}");

        $resp = is_array($result) ? $result['response'] : $result->response;
        $body = is_array($resp) ? $resp['body'] : $resp->body;
        $status = is_array($resp) ? $resp['status'] : $resp->status;
        $headers = is_array($resp) ? $resp['headers'] : (array) $resp->headers;
        return response($body, $status)->withHeaders($headers);
    }

    public function getShopify(): ShopifyApp
    {
        return $this->shopify;
    }
}
