<script setup lang="ts">
import { ref } from 'vue';
import { pingApi } from '@/services/api/ping';
import { useAuthenticatedFetch } from '@/composables/useAuthenticatedFetch';

const { shop, pingUrl } = defineProps<{
    shop: string;
    pingUrl: string;
}>();

const pingResult = ref('');
const pingBusy = ref(false);
const { request, loading, error } = useAuthenticatedFetch();

const runPing = async () => {
    pingBusy.value = true;
    pingResult.value = '...';

    try {
        const payload = await pingApi(pingUrl, request);
        pingResult.value = `OK: shop=${payload.shop}, userId=${payload.userId}`;
    } catch (error: unknown) {
        const message = error instanceof Error ? error.message : 'Unknown error';
        pingResult.value = `Error: ${message}`;
    } finally {
        pingBusy.value = false;
    }
};
</script>

<template>
    <s-page heading="Home">
        <Head title="Home" />
        <s-section heading="Welcome">
            <s-paragraph>Shop: <s-text type="strong">{{ shop }}</s-text></s-paragraph>
            <s-paragraph>
                This is your embedded app home. Build your UI here using Vue + Inertia.
            </s-paragraph>
            <s-stack direction="inline" gap="base" align-items="center">
                <s-button variant="primary" :disabled="pingBusy" @click="runPing">
                    {{ pingBusy ? 'Pinging...' : 'Ping /api/ping' }}
                </s-button>
                <s-text>{{ pingResult }}</s-text>
            </s-stack>
        </s-section>
    </s-page>
</template>
