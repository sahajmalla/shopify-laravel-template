<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { appNavItems } from '@/data/nav';

const page = usePage();
const currentUrl = computed(() => page.url ?? '/');
const currentQuery = computed(() => {
    const index = currentUrl.value.indexOf('?');
    return index === -1 ? '' : currentUrl.value.slice(index);
});

const withQuery = (href: string) => `${href}${currentQuery.value}`;
</script>

<template>
    <s-app-nav>
        <s-link
            v-for="item in appNavItems"
            :key="item.href"
            :href="withQuery(item.href)"
            :rel="item.rel"
        >
            {{ item.name }}
        </s-link>
    </s-app-nav>
</template>
