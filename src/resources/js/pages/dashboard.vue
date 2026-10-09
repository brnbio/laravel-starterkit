<script setup lang="ts">

import { type BreadcrumbItem } from "@/types";
import { dashboard } from "@/types/wayfinder/routes";

const title = "Dashboard";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: dashboard.url(),
    },
];

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <Heading :title />
        <div class="flex flex-col gap-4">
            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-xl border bg-gray-50 dark:bg-gray-900 h-64" />
                <div class="rounded-xl border bg-gray-50 dark:bg-gray-900 h-64" />
                <div class="rounded-xl border bg-gray-50 dark:bg-gray-900 h-64" />
            </div>
            <div class="rounded-xl border bg-gray-50 dark:bg-gray-900 h-64" />
        </div>
    </AppLayout>

</template>
