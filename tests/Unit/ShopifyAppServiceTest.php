<?php

namespace Tests\Unit;

use App\Services\ShopifyAppService;
use Illuminate\Http\Request;
use Tests\TestCase;

class ShopifyAppServiceTest extends TestCase
{
    public function test_request_to_shopify_req_normalizes_headers(): void
    {
        $request = Request::create('https://example.test/webhook', 'POST', [], [], [], [], '{"ok":true}');
        $request->headers->set('X-Shopify-Hmac-Sha256', ['a', 'b']);

        $result = ShopifyAppService::requestToShopifyReq($request);

        $this->assertSame('POST', $result['method']);
        $this->assertSame('https://example.test/webhook', $result['url']);
        $this->assertSame('{"ok":true}', $result['body']);
        $this->assertSame('a,b', $result['headers']['x-shopify-hmac-sha256']);
    }
}
