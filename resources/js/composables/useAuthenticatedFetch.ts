import { ref } from 'vue';
import type { AuthenticatedFetch } from '@/types/http';

// Wraps Shopify App Bridge authenticatedFetch with Vue state for loading and error handling.
export function useAuthenticatedFetch() {
    const loading = ref(false);
    const error = ref<string | null>(null);

    const request: AuthenticatedFetch = async (input, init) => {
        loading.value = true;
        error.value = null;

        try {
            if (typeof window === 'undefined' || typeof window.authenticatedFetch !== 'function') {
                throw new Error('authenticatedFetch not loaded');
            }

            return await window.authenticatedFetch(input, init);
        } catch (err: unknown) {
            error.value = err instanceof Error ? err.message : 'Unknown error';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return { request, loading, error };
}
