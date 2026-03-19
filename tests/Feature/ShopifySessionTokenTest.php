<?php

namespace Tests\Feature;

use App\Services\ShopifyAppService;
use Shopify\App\Types\IdToken;
use Shopify\App\Types\LogWithReq;
use Shopify\App\Types\ResponseInfo;
use Shopify\App\Types\ResultForReq;
use Shopify\App\Types\ResultWithExchangeableIdToken;
use Tests\Support\FakeShopifyApp;
use Tests\Support\FakeShopifyAppService;
use Tests\TestCase;

class ShopifySessionTokenTest extends TestCase
{
    private function makeAppHomeResult(bool $ok): ResultWithExchangeableIdToken
    {
        $log = new LogWithReq('test', 'test', []);
        $headers = $ok ? [] : ['X-Shopify-Retry-Invalid-Session-Request' => '1'];
        $response = new ResponseInfo($ok ? 200 : 401, $ok ? '' : 'Unauthorized', $headers);

        return new ResultWithExchangeableIdToken(
            ok: $ok,
            shop: $ok ? 'test-shop' : null,
            idToken: $ok ? new IdToken(true, 'token', []) : null,
            userId: $ok ? '12345' : null,
            newIdTokenResponse: null,
            log: $log,
            response: $response,
        );
    }

    private function makeWebhookResult(): ResultForReq
    {
        return new ResultForReq(
            ok: true,
            shop: 'test-shop',
            log: new LogWithReq('test', 'test', []),
            response: new ResponseInfo(200, '', []),
        );
    }

    public function test_ping_requires_bearer_token(): void
    {
        $response = $this->getJson('/api/ping');

        $response->assertStatus(401);
        $response->assertHeader('X-Shopify-Retry-Invalid-Session-Request', '1');
    }

    public function test_ping_returns_verifier_response_on_failure(): void
    {
        $appHomeResult = $this->makeAppHomeResult(false);
        $fakeApp = new FakeShopifyApp($appHomeResult, $this->makeWebhookResult());
        app()->instance(ShopifyAppService::class, new FakeShopifyAppService($fakeApp));

        $response = $this->getJson('/api/ping', [
            'Authorization' => 'Bearer test',
        ]);

        $response->assertStatus(401);
        $response->assertHeader('X-Shopify-Retry-Invalid-Session-Request', '1');
    }

    public function test_ping_accepts_verified_session_token(): void
    {
        $appHomeResult = $this->makeAppHomeResult(true);
        $fakeApp = new FakeShopifyApp($appHomeResult, $this->makeWebhookResult());
        app()->instance(ShopifyAppService::class, new FakeShopifyAppService($fakeApp));

        $response = $this->getJson('/api/ping', [
            'Authorization' => 'Bearer test',
        ]);

        $response->assertOk();
        $response->assertJson([
            'shop' => 'test-shop',
            'userId' => '12345',
        ]);
    }
}
