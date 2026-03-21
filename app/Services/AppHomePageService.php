<?php

namespace App\Services;

use App\DTO\ShopAccessTokenData;
use App\Models\Shop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class AppHomePageService
{
    public function __construct(private readonly ShopifyAppService $shopifyService)
    {
    }

    public function render(Request $request, string $page, array $extraProps = []): Response
    {
        $shopify = $this->shopifyService->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        $result = $shopify->verifyAppHomeReq(
            $req,
            appHomePatchIdTokenPath: config('shopify.app_home_patch_id_token_path', '/auth/patch-id-token'),
        );

        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        $tokenResponse = $this->syncOfflineAccessToken($shopify, $result);
        if ($tokenResponse !== null) {
            return $tokenResponse;
        }

        $response = Inertia::render($page, array_merge([
            'shop' => $result->shop,
        ], $extraProps))->toResponse($request);

        $this->copyIframeHeaders($response, $result->response->headers ?? (object)[]);

        return $response;
    }

    private function syncOfflineAccessToken($shopify, $result): ?Response
    {
        $shop = $result->shop;
        $accessTokenModel = Shop::query()
            ->forShop($shop)
            ->forAccessMode('offline')
            ->forUserId(null)
            ->first();

        if ($accessTokenModel !== null) {
            $accessToken = ShopAccessTokenData::fromShop($accessTokenModel)->toPackageArray();
            $refreshResult = $shopify->refreshTokenExchangedAccessToken($accessToken);

            if (! $refreshResult->ok) {
                return ShopifyAppService::resultToResponse($refreshResult);
            }

            if ($refreshResult->accessToken !== null) {
                $this->storeAccessToken(ShopAccessTokenData::fromTokenExchange($refreshResult->accessToken));
            }

            return null;
        }

        $exchangeResult = $shopify->exchangeUsingTokenExchange(
            accessMode: 'offline',
            idToken: $result->idToken,
            invalidTokenResponse: $result->newIdTokenResponse,
        );

        if (! $exchangeResult->ok) {
            return ShopifyAppService::resultToResponse($exchangeResult);
        }

        if ($exchangeResult->accessToken !== null) {
            $this->storeAccessToken(ShopAccessTokenData::fromTokenExchange($exchangeResult->accessToken));
        }

        return null;
    }

    private function storeAccessToken(ShopAccessTokenData $data): void
    {
        $existing = Shop::query()
            ->forShop($data->shop)
            ->forAccessMode($data->accessMode)
            ->forUserId($data->userId ? (string) $data->userId : null)
            ->first();

        if ($existing) {
            $existing->fill($data->toModelAttributes())->save();
            return;
        }

        Shop::query()->create($data->toModelAttributes());
    }

    private function copyIframeHeaders(Response $response, mixed $headers): void
    {
        $headersArray = is_array($headers) ? $headers : (array) $headers;
        foreach ($headersArray as $header => $value) {
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            $response->headers->set($header, $value);
        }
    }
}
