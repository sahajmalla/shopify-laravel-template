<script setup lang="ts">
import AppNav from '@/components/app/AppNav.vue';
</script>

<template>
    <div>
        <AppNav />

        <main>
            <slot />
        </main>
    </div>
</template>
