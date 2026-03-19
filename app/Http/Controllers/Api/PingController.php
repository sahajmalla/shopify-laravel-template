<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PingController extends Controller
{
    /**
     * Minimal protected API example. Requires session token (Authorization: Bearer).
     * Returns shop and userId from the verified session.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'shop' => $request->attributes->get('shop'),
            'userId' => $request->attributes->get('userId'),
        ]);
    }
}
