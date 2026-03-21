<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends AppController
{
    public function __invoke(Request $request): Response
    {
        return $this->renderApp($request, 'Settings/Index');
    }
}
