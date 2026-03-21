<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * Column reference:
     * - shop: Shopify shop domain (e.g., example.myshopify.com)
     * - access_mode: Token type ("offline" or "online")
     * - user_id: Shopify merchant user ID for online tokens (null for offline)
     * - token: Access token (encrypted at rest)
     * - scope: Granted API scopes for the token
     * - refresh_token: Refresh token for token exchange (encrypted at rest)
     * - expires_at: Access token expiry timestamp (nullable)
     * - refresh_token_expires_at: Refresh token expiry timestamp (nullable)
     * - user: User payload for online tokens (JSON)
     */
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
     * Scope to a specific shop domain.
     */
    public function scopeForShop(Builder $query, string $shop): Builder
    {
        return $query->where('shop', $shop);
    }

    /**
     * Scope to an access mode (offline or online).
     */
    public function scopeForAccessMode(Builder $query, string $accessMode = 'offline'): Builder
    {
        return $query->where('access_mode', $accessMode);
    }

    /**
     * Scope to a Shopify user (online tokens) or null for offline tokens.
     */
    public function scopeForUserId(Builder $query, ?string $userId): Builder
    {
        if ($userId !== null && $userId !== '') {
            return $query->where('user_id', (string) $userId);
        }

        return $query->whereNull('user_id');
    }
}
