<script setup lang="ts">

import { AppLogo, FlashMessages } from "@/layouts/components";
import { Head, Link } from "@inertiajs/vue3";

defineProps<{
    title?: string;
    description?: string;
}>();

</script>

<template>

    <Head v-if="title" :title />
    <div class="min-h-screen flex flex-col items-center justify-center overflow-x-clip bg-background text-foreground">
        <div class="w-full max-w-md">
            <Link :href="route('dashboard')" class="fixed top-0 left-0 p-3">
                <AppLogo :height="30" />
            </Link>
            <slot />
        </div>
    </div>
    <FlashMessages />

</template>
