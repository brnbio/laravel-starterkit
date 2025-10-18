<script setup lang="ts">

import { Icon } from "@/components";
import { Button } from "@/components/ui/button";
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle, } from "@/components/ui/item";
import { useAppearance } from "@/composables/useAppearance";
import { AccountLayout, AppLayout } from "@/layouts";
import { type BreadcrumbItem } from "@/types";

interface AppearanceTab {
    value: "light" | "dark" | "system";
    icon: string;
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
        icon: "monitor",
        label: "Auto",
        description: "Das System-Farbschema nutzen"
    },
    {
        value: "light",
        icon: "light_mode",
        label: "Hell",
        description: "Das helle Farbschema nutzen"
    },
    {
        value: "dark",
        icon: "dark_mode",
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
                        <Icon :name="tab.icon" />
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
                        <Button variant="outline" size="sm" @click="updateAppearance(tab.value)" v-if="appearance !== tab.value">
                            Auswählen
                        </Button>
                    </ItemActions>
                </Item>
            </div>
        </AccountLayout>
    </AppLayout>

</template>
