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
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70">
                <svg class="absolute inset-0 size-full stroke-neutral-900/20" fill="none">
                    <defs>
                        <pattern id="pattern0" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect stroke="none" :fill="`url(#pattern0)`" width="100%" height="100%" />
                </svg>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70">
                <svg class="absolute inset-0 size-full stroke-neutral-900/20" fill="none">
                    <defs>
                        <pattern id="pattern1" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect stroke="none" :fill="`url(#pattern1)`" width="100%" height="100%" />
                </svg>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70">
                <svg class="absolute inset-0 size-full stroke-neutral-900/20" fill="none">
                    <defs>
                        <pattern id="pattern2" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect stroke="none" :fill="`url(#pattern2)`" width="100%" height="100%" />
                </svg>
            </div>
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 max-h-64">
            <svg class="absolute inset-0 size-full stroke-neutral-900/20" fill="none">
                <defs>
                    <pattern id="pattern3" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect stroke="none" :fill="`url(#pattern3)`" width="100%" height="100%" />
            </svg>
        </div>
    </AppLayout>

</template>
