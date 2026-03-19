<?php

namespace Tests\Support;

use Shopify\App\ShopifyApp;
use Shopify\App\Types\ResultForReq;
use Shopify\App\Types\ResultWithExchangeableIdToken;

class FakeShopifyApp extends ShopifyApp
{
    private ResultWithExchangeableIdToken $appHomeResult;
    private ResultForReq $webhookResult;

    public function __construct(ResultWithExchangeableIdToken $appHomeResult, ResultForReq $webhookResult)
    {
        parent::__construct('fake-client', str_repeat('s', 32));
        $this->appHomeResult = $appHomeResult;
        $this->webhookResult = $webhookResult;
    }

    public function setAppHomeResult(ResultWithExchangeableIdToken $result): void
    {
        $this->appHomeResult = $result;
    }

    public function setWebhookResult(ResultForReq $result): void
    {
        $this->webhookResult = $result;
    }

    public function verifyAppHomeReq(array $req, mixed $appHomePatchIdTokenPath = ''): ResultWithExchangeableIdToken
    {
        return $this->appHomeResult;
    }

    public function verifyWebhookReq(array $req): ResultForReq
    {
        return $this->webhookResult;
    }
}
