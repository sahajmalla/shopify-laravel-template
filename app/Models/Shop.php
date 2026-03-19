<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'shop',
        'access_mode',
        'user_id',
        'token',
        'scope',
        'refresh_token',
        'expires_at',
        'refresh_token_expires_at',
        'user',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
        'user' => 'array',
        'token' => 'encrypted',
        'refresh_token' => 'encrypted',
    ];

    /**
     * Convert model to the access token shape expected by shopify-app-php.
     */
    public function toAccessTokenArray(): array
    {
        return [
            'shop' => $this->shop,
            'accessMode' => $this->access_mode,
            'token' => $this->token,
            'scope' => $this->scope ?? '',
            'refreshToken' => $this->refresh_token ?? '',
            'expires' => $this->expires_at?->format('c'),
            'refreshTokenExpires' => $this->refresh_token_expires_at?->format('c'),
            'userId' => $this->user_id ?? '',
            'user' => $this->user,
        ];
    }

    /**
     * Create or update a shop access token from the array shape returned by the package.
     */
    public static function fromAccessTokenArray(array $data): self
    {
        $shop = $data['shop'] ?? '';
        $accessMode = $data['accessMode'] ?? 'offline';
        $userId = $data['userId'] ?? null;

        $attributes = [
            'shop' => $shop,
            'access_mode' => $accessMode,
            'user_id' => $userId ? (string) $userId : null,
            'token' => $data['token'] ?? '',
            'scope' => $data['scope'] ?? null,
            'refresh_token' => $data['refreshToken'] ?? null,
            'expires_at' => isset($data['expires']) ? $data['expires'] : null,
            'refresh_token_expires_at' => isset($data['refreshTokenExpires']) ? $data['refreshTokenExpires'] : null,
            'user' => $data['user'] ?? null,
        ];

        $instance = self::query()
            ->where('shop', $shop)
            ->where('access_mode', $accessMode)
            ->where(
                fn ($q) => $userId
                    ? $q->where('user_id', (string) $userId)
                    : $q->whereNull('user_id')
            )
            ->first();

        if ($instance) {
            $instance->update($attributes);
            return $instance->fresh();
        }

        return self::query()->create($attributes);
    }

    /**
     * Get the access token record for a shop and access mode (and optionally user for online).
     */
    public static function fromShopAndMode(string $shop, string $accessMode = 'offline', ?string $userId = null): ?self
    {
        $query = self::query()
            ->where('shop', $shop)
            ->where('access_mode', $accessMode);

        if ($accessMode === 'online' && $userId !== null) {
            $query->where('user_id', (string) $userId);
        } else {
            $query->whereNull('user_id');
        }

        return $query->first();
    }
}
