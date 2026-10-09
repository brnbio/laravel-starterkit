<script setup lang="ts">

import { type NavItem } from "@/types";
import { Link } from "@inertiajs/vue3";
import account from "@/types/wayfinder/routes/account";

defineProps<{
    active: "account" | "password" | "two-factor-auth" | "appearance";
}>();

const sidebarNavItems: NavItem[] = [
    {
        title: "Persönliche Daten",
        href: account.edit.url(),
        identifier: "account",
    },
    {
        title: "Passwort",
        href: account.password.url(),
        identifier: "password",
    },
    {
        title: "2-Faktor-Authentifizierung",
        href: account.twoFactor.url(),
        identifier: "two-factor-auth",
    },
    {
        title: "Appearance",
        href: account.appearance.url(),
        identifier: "appearance",
    },
];

</script>

<template>

    <Heading title="Mein Konto" description="Verwalten Sie die Einstellungen Ihres persönlichen Kontos" />
    <div class="flex flex-col lg:flex-row gap-8">
        <aside class="w-full max-w-xl lg:w-56">
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