<?php

use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\SettingsController;
use App\Http\Controllers\Auth\PatchIdTokenController;
use App\Http\Controllers\Webhooks\AppUninstalledController;
use App\Http\Controllers\Webhooks\Gdpr\CustomersDataRequestController;
use App\Http\Controllers\Webhooks\Gdpr\CustomersRedactController;
use App\Http\Controllers\Webhooks\Gdpr\ShopRedactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shopify App Routes
|--------------------------------------------------------------------------
|
| App home is the embedded app URL (loaded in Shopify admin iframe).
| Patch ID token and webhooks must be excluded from CSRF in production.
|
*/

Route::get('/', HomeController::class)->name('app.home');
Route::get('/settings', SettingsController::class)->name('app.settings');
Route::post(config('shopify.app_home_patch_id_token_path', '/auth/patch-id-token'), PatchIdTokenController::class)->name('auth.patch-id-token');

// Mandatory webhooks (HMAC verified)
Route::prefix('webhooks')->middleware('shopify.webhook')->group(function () {
    Route::post('/app/uninstalled', AppUninstalledController::class)->name('webhooks.uninstalled');
    Route::post('/gdpr/customers/data_request', CustomersDataRequestController::class)->name('webhooks.gdpr.customers.data_request');
    Route::post('/gdpr/customers/redact', CustomersRedactController::class)->name('webhooks.gdpr.customers.redact');
    Route::post('/gdpr/shop/redact', ShopRedactController::class)->name('webhooks.gdpr.shop.redact');
});
