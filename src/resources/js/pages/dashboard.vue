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
        <Spinner />
    </AppLayout>

</template>
