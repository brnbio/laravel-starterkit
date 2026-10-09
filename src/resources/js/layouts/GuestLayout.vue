<script setup lang="ts">

import { Head, Link } from "@inertiajs/vue3";
import { dashboard } from "@/types/wayfinder/routes";

defineProps<{
    title?: string;
    description?: string;
}>();

</script>

<template>

    <Head v-if="title" :title />
    <div class="min-h-screen flex flex-col items-center justify-center overflow-x-clip bg-background text-foreground">
        <div class="w-full max-w-md">
            <Link :href="dashboard()" class="fixed top-0 left-0 p-3">
                <AppLogo :height="30" />
            </Link>
            <slot />
        </div>
    </div>
    <FlashMessages />

</template>
