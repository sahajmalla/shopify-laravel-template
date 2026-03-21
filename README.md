# Shopify Laravel + Vue + Inertia Template

This template builds Shopify embedded apps using **App Bridge** and **Polaris web components** (recommended by Shopify).

## What this template gives you (low level)

- `routes/web.php` defines embedded app pages (`/` and `/settings`).
- `routes/api.php` is for backend API endpoints called from the UI.
- `app/Http/Controllers/App/HomeController.php` renders the Home page.
- `app/Http/Controllers/App/SettingsController.php` renders the Settings page.
- `app/Services/AppHomePageService.php` verifies Shopify requests, refreshes tokens, and renders Inertia pages.
- `resources/views/app.blade.php` is the Inertia root template.
- `resources/js/app.js` boots the app and loads Inertia.
- `resources/js/bootstrap/inertia.js` wires Inertia + `shopify:navigate`.
- `resources/js/layouts/AppLayout.vue` is the shared layout (App Nav + page slot). For App Bridge web components navigation, use: https://shopify.dev/docs/api/app-home/app-bridge-web-components/app-nav
- `resources/js/pages/**/Index.vue` are page components.
- `resources/js/services/shopify/authenticatedFetch.ts` wraps App Bridge `authenticatedFetch`.
- `resources/js/composables/useAuthenticatedFetch.ts` exposes it with Vue state.
- Backend API routes use the `shopify.session.token` middleware for session token validation.
- Core package: `shopify/shopify-app-php` (GitHub: https://github.com/Shopify/shopify-app-php).

## Why this helps you build faster

- The embedded app verification and token refresh are already wired.
- You get a working Inertia layout with navigation, so adding pages is just creating a controller + Vue file.
- Session-token auth is already set up for safe backend calls.
- Webhook routes are already in place.

## Local HTTPS for Shopify development

Shopify requires HTTPS for embedded apps. Two common local HTTPS options:

- Laravel Valet (macOS): https://laravel.com/docs/valet
- Laravel Herd (macOS/Windows): https://herd.laravel.com

If you use Valet or Herd, set your `shopify.app.toml` `application_url` and `auth.redirect_urls` to your local HTTPS domain (for example, `https://your-app.test`).

## Quick workflow

1. Add a controller that calls `AppHomePageService::render()`.
2. Create a Vue page in `resources/js/pages/<Page>/Index.vue`.
3. Add a route in `routes/web.php`.
4. Update the nav in `resources/js/components/app/AppNav.vue` if needed.
5. Call backend APIs with `window.authenticatedFetch`.

That is the minimal Shopify-compliant embedded app starting point.

## Getting started (smooth run)

1. Install dependencies.

```bash
composer install
npm install
```

2. Create your local env file and app key.

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure Shopify env vars in `.env`.

Set:
- `SHOPIFY_API_KEY` (from Shopify CLI `shopify app dev`)
- `SHOPIFY_API_SECRET` (from Shopify CLI `shopify app dev`)
- `SHOPIFY_API_VERSION` (match your desired Admin API version)

4. Update `shopify.app.toml` with your dev URL and app details.

Set:
- `client_id`
- `name`
- `application_url` (must be HTTPS)
- `[auth].redirect_urls` (at least one, HTTPS)
- `[access_scopes].scopes`

5. Run migrations.

```bash
php artisan migrate
```

6. Start the app.

Option A: run everything with the composer script:

```bash
composer dev
```

Option B: run in two terminals:

```bash
php artisan serve
```

```bash
npm run dev
```

7. Start Shopify CLI and open the app in the admin.

```bash
shopify app dev
```

Follow the CLI prompt to open the embedded app.

Notes:
- Shopify requires HTTPS for embedded apps. Use Valet, Herd, or your own HTTPS tunnel.
- This template focuses on App Home + token exchange. If you need OAuth callback handling, add your own `/api/auth` route and set it in `[auth].redirect_urls`.
