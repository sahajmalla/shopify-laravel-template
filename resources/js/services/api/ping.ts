import type { AuthenticatedFetch } from '@/types/http';

import type { ApiResponse } from './types';

type PingPayload = {
    shop: string | null;
    userId: string | null;
};

export async function pingApi(pingUrl: string, request: AuthenticatedFetch): Promise<PingPayload> {
    const response = await request(pingUrl);
    const json = (await response.json()) as ApiResponse<PingPayload>;

    if (!json.success) {
        throw new Error(json.message || 'Request failed');
    }

    return json.data ?? { shop: null, userId: null };
}
