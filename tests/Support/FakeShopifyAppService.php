<?php

namespace Tests\Support;

use App\Services\ShopifyAppService;
use Shopify\App\ShopifyApp;

class FakeShopifyAppService extends ShopifyAppService
{
    public function __construct(ShopifyApp $shopify)
    {
        $this->shopify = $shopify;
    }
}
