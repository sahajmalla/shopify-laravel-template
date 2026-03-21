<?php

namespace App\DTO;

use App\Models\Shop;
use Shopify\App\Types\TokenExchangeAccessToken;

final class ShopAccessTokenData
{
    public function __construct(
        public readonly string $shop,
        public readonly string $accessMode,
        public readonly ?string $userId,
        public readonly string $token,
        public readonly ?string $scope,
        public readonly ?string $refreshToken,
        public readonly ?string $expires,
        public readonly ?string $refreshTokenExpires,
        public readonly ?array $user
    ) {
    }

    public static function fromShop(Shop $shop): self
    {
        return new self(
            shop: $shop->shop,
            accessMode: $shop->access_mode,
            userId: $shop->user_id ? (string) $shop->user_id : null,
            token: $shop->token,
            scope: $shop->scope,
            refreshToken: $shop->refresh_token,
            expires: $shop->expires_at?->format('c'),
            refreshTokenExpires: $shop->refresh_token_expires_at?->format('c'),
            user: $shop->user,
        );
    }

    public static function fromTokenExchange(TokenExchangeAccessToken $token): self
    {
        $user = $token->user;

        return new self(
            shop: $token->shop,
            accessMode: $token->accessMode,
            userId: isset($user['id']) ? (string) $user['id'] : null,
            token: $token->token,
            scope: $token->scope ?? null,
            refreshToken: $token->refreshToken ?? null,
            expires: $token->expires !== null ? (string) $token->expires : null,
            refreshTokenExpires: $token->refreshTokenExpires !== null ? (string) $token->refreshTokenExpires : null,
            user: $user,
        );
    }

    public function toPackageArray(): array
    {
        return [
            'shop' => $this->shop,
            'accessMode' => $this->accessMode,
            'token' => $this->token,
            'scope' => $this->scope ?? '',
            'refreshToken' => $this->refreshToken ?? '',
            'expires' => $this->expires,
            'refreshTokenExpires' => $this->refreshTokenExpires,
            'userId' => $this->userId ?? '',
            'user' => $this->user,
        ];
    }

    public function toModelAttributes(): array
    {
        return [
            'shop' => $this->shop,
            'access_mode' => $this->accessMode,
            'user_id' => $this->userId ? (string) $this->userId : null,
            'token' => $this->token,
            'scope' => $this->scope,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->expires,
            'refresh_token_expires_at' => $this->refreshTokenExpires,
            'user' => $this->user,
        ];
    }
}
