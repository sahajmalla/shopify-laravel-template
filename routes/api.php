<?php

use App\Http\Controllers\Api\PingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (session-token protected)
|--------------------------------------------------------------------------
|
| These routes require a valid Shopify session token (Authorization: Bearer).
| Use the frontend authenticatedFetch helper when calling from the embedded app.
|
*/

Route::middleware(['shopify.session.token'])->group(function (): void {
    Route::get('/ping', PingController::class)->name('api.ping');
});
