<script setup lang="ts">

import { SidebarInset, SidebarProvider } from "@/components/ui/sidebar";
import { AppSidebar, AppSidebarHeader } from "@/layouts/components";
import type { BreadcrumbItemType } from "@/types";
import { Head, usePage } from "@inertiajs/vue3";

withDefaults(defineProps<{
    title?: string;
    breadcrumbs?: BreadcrumbItemType[];
}>(), {
    breadcrumbs: () => [],
});

const isOpen: boolean = usePage().props.sidebarOpen;

</script>

<template>

    <Head v-if="title" :title />
    <SidebarProvider :default-open="isOpen">
        <AppSidebar />
        <SidebarInset class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs />
            <main class="mx-auto w-full max-w-[1920px] px-8 pt-10 pb-20 flex flex-col gap-8">
                <slot />
            </main>
        </SidebarInset>
    </SidebarProvider>

</template>
