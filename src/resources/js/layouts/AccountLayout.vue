<script setup lang="ts">

import { Heading } from "@/components";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { type NavItem } from "@/types";
import { Link } from "@inertiajs/vue3";

defineProps<{
    active: string;
}>();

const sidebarNavItems: NavItem[] = [
    {
        title: "Persönliche Daten",
        href: "#",
        identifier: "account",
    },
    {
        title: "Password",
        href: "#",
        identifier: "password",
    },
    {
        title: "Two-Factor Auth",
        href: "#",
        identifier: "two-factor-auth",
    },
    {
        title: "Appearance",
        href: "#",
        identifier: "appearance",
    },
];

</script>

<template>

    <Heading title="Mein Konto" description="Verwalten Sie die Einstellungen Ihres persönlichen Kontos" />
    <div class="flex flex-col lg:flex-row gap-8">
        <aside class="w-full max-w-xl lg:w-48">
            <nav class="flex flex-col">
                <Button
                    v-for="(item, index) in sidebarNavItems"
                    :key="index"
                    as-child
                    variant="ghost"
                    :class="[
                        'w-full justify-start',
                        { 'bg-muted': item.identifier === active },
                    ]"
                >
                    <Link :href="item.href">
                        {{ item.title }}
                    </Link>
                </Button>
            </nav>
        </aside>
        <Separator class="lg:hidden" />
        <section class="flex-1 md:max-w-xl flex flex-col gap-8">
            <slot />
        </section>
    </div>

</template>