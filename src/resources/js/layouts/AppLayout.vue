<script setup lang="ts">

import { SidebarInset, SidebarProvider } from "@/components/ui/sidebar";
import { AppSidebar, AppSidebarHeader } from "@/layouts/components";
import type { BreadcrumbItemType } from "@/types";
import { usePage } from "@inertiajs/vue3";

withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(), {
    breadcrumbs: () => [],
});

const isOpen: boolean = usePage().props.sidebarOpen;

</script>

<template>

    <SidebarProvider :default-open="isOpen">
        <AppSidebar />
        <SidebarInset class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs />
            <slot />
        </SidebarInset>
    </SidebarProvider>

</template>
