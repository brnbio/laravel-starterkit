<script setup lang="ts">

import { Button } from "@/components/ui/button";
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle, } from "@/components/ui/item";
import { useAppearance } from "@/composables/useAppearance";
import { AccountLayout, AppLayout } from "@/layouts";
import { type BreadcrumbItem } from "@/types";
import { DarkModeOutline, IconComponent, LightModeOutline, ScreenshotMonitorOutline } from "@brnbio/vue-material-design-icons";

interface AppearanceTab {
    value: "light" | "dark" | "system";
    icon: IconComponent;
    label: string;
    description?: string;
}

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("account.appearance"),
    },
];

const { appearance, updateAppearance } = useAppearance();

const tabs: AppearanceTab[] = [
    {
        value: "system",
        icon: ScreenshotMonitorOutline,
        label: "Auto",
        description: "Das System-Farbschema nutzen"
    },
    {
        value: "light",
        icon: LightModeOutline,
        label: "Hell",
        description: "Das helle Farbschema nutzen"
    },
    {
        value: "dark",
        icon: DarkModeOutline,
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
