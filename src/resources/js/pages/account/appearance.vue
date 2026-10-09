<script setup lang="ts">

import { useAppearance } from "@/composables/useAppearance";
import { type BreadcrumbItem } from "@/types";
import type { Component } from "vue";
import IconDarkModeOutline from "~icons/material-symbols/dark-mode-outline";
import IconLightModeOutline from "~icons/material-symbols/light-mode-outline";
import IconScreenshotMonitorOutline from "~icons/material-symbols/screenshot-monitor-outline";
import account from "@/types/wayfinder/routes/account";

interface AppearanceTab {
    value: "light" | "dark" | "system";
    icon: Component;
    label: string;
    description?: string;
}

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: account.appearance.url(),
    },
];

const { appearance, updateAppearance } = useAppearance();

const tabs: AppearanceTab[] = [
    {
        value: "system",
        icon: IconScreenshotMonitorOutline,
        label: "Auto",
        description: "Das System-Farbschema nutzen"
    },
    {
        value: "light",
        icon: IconLightModeOutline,
        label: "Hell",
        description: "Das helle Farbschema nutzen"
    },
    {
        value: "dark",
        icon: IconDarkModeOutline,
        label: "Dunkel",
        description: "Das dunkle Farbschema nutzen"
    },
];

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <AccountLayout active="appearance">
            <div class="flex flex-col gap-6">
                <Item v-for="tab in tabs" :key="tab.value" :variant="appearance === tab.value ? 'outline' : 'default'">
                    <ItemMedia variant="icon">
                        <Component :is="tab.icon" />
                    </ItemMedia>
                    <ItemContent>
                        <ItemTitle>
                            {{ tab.label }}
                        </ItemTitle>
                        <ItemDescription>
                            {{ tab.description }}
                        </ItemDescription>
                    </ItemContent>
                    <ItemActions>
                        <Button v-if="appearance !== tab.value" variant="outline" size="sm" @click="updateAppearance(tab.value)">
                            Auswählen
                        </Button>
                    </ItemActions>
                </Item>
            </div>
        </AccountLayout>
    </AppLayout>

</template>
