<script setup lang="ts">

import { Icon } from "@/components";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { useInitials } from "@/composables/useInitials";
import { Breadcrumbs } from "@/layouts/components/index";
import type { BreadcrumbItemType } from "@/types";
import { Link, usePage } from "@inertiajs/vue3";

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = page.props.user;

const { getInitials } = useInitials();

</script>

<template>

    <header class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <DropdownMenu :modal="false">
            <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="icon" class="group cursor-pointer aspect-square">
                    <Avatar class="h-8 w-8 rounded-lg">
                        <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                        <AvatarFallback class="rounded-lg">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="min-w-56" align="end" :side-offset="5" :align-offset="0">
                <DropdownMenuGroup>
                    <DropdownMenuLabel class="flex flex-col text-sm">
                        <span class="truncate font-semibold">
                            {{ user.name }}
                        </span>
                        <span class="truncate text-xs text-muted-foreground">
                            {{ user.email }}
                        </span>
                    </DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem as-child>
                        <Link :href="route('account.edit')" class="flex w-full cursor-pointer items-center gap-2">
                            <Icon name="account_box" />
                            Mein Konto
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem as-child>
                        <Link :href="route('logout')" method="post" class="flex w-full items-center gap-2">
                            <Icon name="logout" />
                            Logout
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    </header>

</template>
