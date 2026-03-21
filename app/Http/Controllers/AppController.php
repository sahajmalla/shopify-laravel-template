<?php

namespace App\Http\Controllers;

use App\Services\AppHomePageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Base controller for embedded app pages. It renders Inertia pages after Shopify app-home verification.
abstract class AppController extends Controller
{
    public function __construct(protected AppHomePageService $appHome)
    {
    }

    /**
     * Render an embedded app page with shared Shopify verification, token refresh, and iframe headers.
     */
    protected function renderApp(Request $request, string $page, array $props = []): Response
    {
        return $this->appHome->render($request, $page, $props);
    }
}
