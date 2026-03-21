<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PingController extends ApiController
{

    /**
     * Minimal protected API example. Requires session token (Authorization: Bearer).
     * Returns shop and userId from the verified session.
     */
    public function __invoke(Request $request)
    {
        return $this->successResponse([
            'shop' => $request->attributes->get('shop'),
            'userId' => $request->attributes->get('userId'),
        ]);
    }
}
