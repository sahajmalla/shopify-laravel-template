<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\ShopifyAppService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Shopify\App\Types\TokenExchangeAccessToken;

class AppHomeController extends Controller
{
    /**
     * Convert package TokenExchangeAccessToken to array shape expected by Shop model.
     */
    private static function accessTokenObjectToArray(TokenExchangeAccessToken $t): array
    {
        $user = $t->user;
        return [
            'shop' => $t->shop,
            'accessMode' => $t->accessMode,
            'token' => $t->token,
            'scope' => $t->scope ?? '',
            'refreshToken' => $t->refreshToken ?? '',
            'expires' => $t->expires,
            'refreshTokenExpires' => $t->refreshTokenExpires,
            'userId' => $user['id'] ?? null,
            'user' => $user,
        ];
    }

    public function __invoke(Request $request): View|\Illuminate\Http\Response
    {
        $service = app(ShopifyAppService::class);
        $shopify = $service->getShopify();
        $req = ShopifyAppService::requestToShopifyReq($request);

        $result = $shopify->verifyAppHomeReq(
            $req,
            appHomePatchIdTokenPath: config('shopify.app_home_patch_id_token_path', '/auth/patch-id-token'),
        );

        if (! $result->ok) {
            return ShopifyAppService::resultToResponse($result);
        }

        $shop = $result->shop;
        $accessTokenModel = Shop::fromShopAndMode($shop, 'offline');

        if ($accessTokenModel !== null) {
            $accessToken = $accessTokenModel->toAccessTokenArray();
            $refreshResult = $shopify->refreshTokenExchangedAccessToken($accessToken);

            if (! $refreshResult->ok) {
                return ShopifyAppService::resultToResponse($refreshResult);
            }

            if ($refreshResult->accessToken !== null) {
                Shop::fromAccessTokenArray(self::accessTokenObjectToArray($refreshResult->accessToken));
            }
        } else {
            $exchangeResult = $shopify->exchangeUsingTokenExchange(
                accessMode: 'offline',
                idToken: $result->idToken,
                invalidTokenResponse: $result->newIdTokenResponse,
            );

            if (! $exchangeResult->ok) {
                return ShopifyAppService::resultToResponse($exchangeResult);
            }

            if ($exchangeResult->accessToken !== null) {
                Shop::fromAccessTokenArray(self::accessTokenObjectToArray($exchangeResult->accessToken));
            }
        }

        $response = response()->view('app', [
            'shop' => $shop,
            'clientId' => config('shopify.api_key'),
        ]);

        // Copy headers from result for iframe protection
        $headers = $result->response->headers;
        $headersArray = is_array($headers) ? $headers : (array) $headers;
        foreach ($headersArray as $header => $value) {
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            $response->headers->set($header, $value);
        }

        return $response;
    }
}
