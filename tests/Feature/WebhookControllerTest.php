<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Services\ShopifyAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Shopify\App\Types\LogWithReq;
use Shopify\App\Types\ResponseInfo;
use Shopify\App\Types\ResultForReq;
use Shopify\App\Types\ResultWithExchangeableIdToken;
use Tests\Support\FakeShopifyApp;
use Tests\Support\FakeShopifyAppService;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (! config('app.key')) {
            config(['app.key' => 'base64:' . base64_encode(str_repeat('a', 32))]);
        }
    }

    private function makeAppHomeResult(): ResultWithExchangeableIdToken
    {
        return new ResultWithExchangeableIdToken(
            ok: true,
            shop: 'test-shop',
            idToken: null,
            userId: null,
            newIdTokenResponse: null,
            log: new LogWithReq('test', 'test', []),
            response: new ResponseInfo(200, '', []),
        );
    }

    private function makeWebhookResult(bool $ok, int $status, array $headers = []): ResultForReq
    {
        return new ResultForReq(
            ok: $ok,
            shop: $ok ? 'test-shop' : null,
            log: new LogWithReq('test', 'test', []),
            response: new ResponseInfo($status, $ok ? '' : 'Unauthorized', $headers),
        );
    }

    private function bindFakeShopify(ResultForReq $webhookResult): void
    {
        $fakeApp = new FakeShopifyApp($this->makeAppHomeResult(), $webhookResult);
        app()->instance(ShopifyAppService::class, new FakeShopifyAppService($fakeApp));
    }

    public function test_uninstalled_webhook_deletes_shop(): void
    {
        $this->bindFakeShopify($this->makeWebhookResult(true, 200));

        Shop::create([
            'shop' => 'test-shop',
            'access_mode' => 'offline',
            'user_id' => null,
            'token' => 'token',
            'scope' => 'read_products',
            'refresh_token' => 'refresh',
            'expires_at' => null,
            'refresh_token_expires_at' => null,
            'user' => null,
        ]);

        $response = $this->postJson('/webhooks/app/uninstalled', ['reason' => 'uninstalled']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('shops', ['shop' => 'test-shop']);
    }

    public function test_shop_redact_webhook_deletes_shop(): void
    {
        $this->bindFakeShopify($this->makeWebhookResult(true, 200));

        Shop::create([
            'shop' => 'test-shop',
            'access_mode' => 'offline',
            'user_id' => null,
            'token' => 'token',
            'scope' => 'read_products',
            'refresh_token' => 'refresh',
            'expires_at' => null,
            'refresh_token_expires_at' => null,
            'user' => null,
        ]);

        $response = $this->postJson('/webhooks/gdpr/shop/redact', ['id' => 123]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('shops', ['shop' => 'test-shop']);
    }

    public function test_webhook_returns_verifier_response_on_failure(): void
    {
        $this->bindFakeShopify($this->makeWebhookResult(false, 401, ['X-Test' => '1']));

        $response = $this->postJson('/webhooks/gdpr/customers/redact', ['sample' => true]);

        $response->assertStatus(401);
        $response->assertHeader('X-Test', '1');
    }
}
