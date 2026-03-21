<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="shopify-api-key" content="{{ config('shopify.api_key') }}">
    <title inertia>{{ config('app.name') }} – App</title>
    <script>window.ShopifyApiKey = {!! json_encode(config('shopify.api_key')) !!};</script>
    <script src="https://cdn.shopify.com/shopifycloud/app-bridge.js"></script>
    <script src="https://cdn.shopify.com/shopifycloud/polaris.js"></script>
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
