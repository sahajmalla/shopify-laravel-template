<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="shopify-api-key" content="{{ $clientId }}">
    <title>{{ config('app.name') }} – App</title>
    {{-- App Bridge – required for embedded apps (security & parent communication) --}}
    <script
        src="https://cdn.shopify.com/shopifycloud/app-bridge.js"
        data-api-key="{{ $clientId }}"
   ></script>
    {{-- Polaris Web Components – Shopify look and feel --}}
    <script src="https://cdn.shopify.com/shopifycloud/polaris.js"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; margin: 0; padding: 1rem; background: #f6f6f7; }
        .card { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 0 rgba(0,0,0,.05); max-width: 600px; }
        h1 { font-size: 1.25rem; margin: 0 0 0.5rem; color: #202223; }
        p { color: #6d7175; margin: 0; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Welcome to your app</h1>
        <p>Shop: <strong>{{ $shop }}</strong></p>
        <p style="margin-top: 1rem;">This is your embedded app home. Add your UI and API calls here.</p>
    </div>
</body>
</html>
