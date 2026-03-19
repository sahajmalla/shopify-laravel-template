/**
 * Session-token authenticated fetch for backend API calls.
 * Requires window.ShopifyApiKey to be set (e.g. from app.blade.php).
 * Host is read from the page URL (Shopify injects it when loading the embedded app).
 * Usage: window.authenticatedFetch('/api/ping').then(r => r.json()).then(console.log)
 */
import createApp from '@shopify/app-bridge';
import { authenticatedFetch as appBridgeAuthenticatedFetch } from '@shopify/app-bridge-utils';

function getHostFromUrl() {
  if (typeof window === 'undefined' || !window.location.search) return '';
  return new URLSearchParams(window.location.search).get('host') || '';
}

const apiKey = typeof window !== 'undefined' ? window.ShopifyApiKey : null;
const host = getHostFromUrl();
const app = apiKey ? createApp({ apiKey, host }) : null;

const authenticatedFetch = app
  ? appBridgeAuthenticatedFetch(app)
  : function () {
      return Promise.reject(
        new Error('ShopifyApiKey not set; authenticatedFetch is only available in the embedded app.')
      );
    };

export { authenticatedFetch };

if (typeof window !== 'undefined') {
  window.authenticatedFetch = authenticatedFetch;
}
