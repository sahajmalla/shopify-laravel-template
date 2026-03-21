<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const appName = computed(() => page.props.appName ?? 'App');
const shop = computed(() => page.props.shop ?? '');
</script>

<template>
    <s-section>
        <s-stack direction="inline" align-items="center" gap="base">
            <s-box>
                <s-text type="strong">{{ appName }}</s-text>
                <s-paragraph>Shopify app</s-paragraph>
            </s-box>
            <s-text v-if="shop">Shop: <s-text type="strong">{{ shop }}</s-text></s-text>
        </s-stack>
    </s-section>
</template>
