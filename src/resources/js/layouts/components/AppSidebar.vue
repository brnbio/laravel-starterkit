<script setup lang="ts">

import { Icon } from "@/components";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from "@/components/ui/sidebar";
import { AppLogo } from "@/layouts/components";
import { type NavItem } from "@/types";
import { Link } from "@inertiajs/vue3";

const mainNavItems: NavItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard")
    },
];

const footerNavItems: NavItem[] = [
    {
        title: "Github Repo",
        href: "https://github.com/laravel/vue-starter-kit"
    },
    {
        title: "Documentation",
        href: "https://laravel.com/docs/starter-kits#vue"
    },
];

</script>

<template>

    <Sidebar collapsible="icon" variant="sidebar">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>Platform</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
                        <SidebarMenuButton as-child :tooltip="item.title">
                            <Link :href="item.href">
                                <Icon v-if="item.icon" :name="item.icon" />
                                <span>
                                    {{ item.title }}
                                </span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>
        <SidebarFooter>
            <SidebarGroup class="group-data-[collapsible=icon]:p-0">
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in footerNavItems" :key="item.title">
                            <SidebarMenuButton class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100" as-child>
                                <a :href="item.href" target="_blank" rel="noopener noreferrer">
                                    <Icon v-if="item.icon" :name="item.icon" />
                                    <span>
                                        {{ item.title }}
                                    </span>
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarFooter>
    </Sidebar>
    <slot />

</template>
