<script setup lang="ts">

import { Heading } from "@/components";
import { Spinner } from "@/components/ui/spinner";
import AppLayout from "@/layouts/AppLayout.vue";
import { type BreadcrumbItem } from "@/types";

const title = "Dashboard";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("dashboard"),
    },
];

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <Heading :title />
        <Spinner />
    </AppLayout>

</template>
