<?php

use App\Http\Controllers\AppHomeController;
use App\Http\Controllers\Auth\PatchIdTokenController;
use App\Http\Controllers\WebhookController;
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

Route::get('/', AppHomeController::class)->name('app.home');
Route::post(config('shopify.app_home_patch_id_token_path', '/auth/patch-id-token'), PatchIdTokenController::class)->name('auth.patch-id-token');

// Mandatory webhooks (HMAC verified)
Route::post('/webhooks/app/uninstalled', [WebhookController::class, 'uninstalled'])->name('webhooks.uninstalled');
Route::post('/webhooks/gdpr/customers/data_request', [WebhookController::class, 'customersDataRequest'])->name('webhooks.gdpr.customers.data_request');
Route::post('/webhooks/gdpr/customers/redact', [WebhookController::class, 'customersRedact'])->name('webhooks.gdpr.customers.redact');
Route::post('/webhooks/gdpr/shop/redact', [WebhookController::class, 'shopRedact'])->name('webhooks.gdpr.shop.redact');
