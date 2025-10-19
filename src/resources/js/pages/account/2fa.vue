<script setup lang="ts">

import { HeadingSmall, Icon } from "@/components";
import TwoFactorSetupModal from "@/components/TwoFactorSetupModal.vue";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { useTwoFactorAuth } from "@/composables/useTwoFactorAuth";
import { AccountLayout, AppLayout } from "@/layouts";
import { type BreadcrumbItem } from "@/types";
import { Form, Link, router } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref } from "vue";

const props = defineProps<{
    twoFactorEnabled: boolean;
}>();

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("account.2fa"),
    },
];

const { hasSetupData, clearTwoFactorAuthData, recoveryCodesList, fetchRecoveryCodes } = useTwoFactorAuth();

const showSetupModal = ref<boolean>(false);

const regenerate = () => {
    router.visit(route("two-factor.regenerate-recovery-codes"), {
        method: "post",
        preserveScroll: true,
        onSuccess: () => {
            fetchRecoveryCodes();
        },
    })
};

onMounted(async () => {
    if (props.twoFactorEnabled && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }
});

onUnmounted(() => {
    clearTwoFactorAuthData();
});

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <AccountLayout active="two-factor-auth">

            <HeadingSmall title="Zwei-Faktor-Authentifizierung" description="Verwalten Sie Ihre Authentifizierungseinstellungen" />

            <div v-if="!twoFactorEnabled" class="flex flex-col gap-4">
                <Badge variant="destructive">
                    Nicht aktiv
                </Badge>
                <p class="text-muted-foreground">
                    Wenn Sie die Zwei-Faktor-Authentifizierung aktivieren, werden Sie beim Anmelden nach einer sicheren PIN gefragt.
                    Diese PIN kann über eine TOTP-kompatible App auf Ihrem Smartphone abgerufen werden.
                </p>
                <Button v-if="hasSetupData" class="self-start" @click="showSetupModal = true">
                    <Icon name="shield_lock" />
                    Einrichtung fortsetzen
                </Button>
                <Form v-else v-slot="{ processing }" :action="route('two-factor.enable')" method="post" @success="showSetupModal = true">
                    <Button type="submit" :disabled="processing">
                        <Icon name="shield_lock" />
                        2FA aktivieren
                    </Button>
                </Form>
            </div>

            <div v-else class="flex flex-col gap-4">
                <Badge variant="success">
                    Aktiv
                </Badge>
                <p class="text-muted-foreground">
                    Mit aktivierter Zwei-Faktor-Authentifizierung werden Sie beim Anmelden nach einer sicheren, zufälligen PIN gefragt,
                    die Sie über eine TOTP-kompatible App auf Ihrem Smartphone abrufen können.
                </p>
                <section class="flex flex-col gap-4">
                    <div class="flex justify-between items-center">
                        <HeadingSmall title="Wiederherstellungscodes" />
                        <Button variant="ghost" class="text-muted-foreground hover:text-foreground" @click.prevent="regenerate">
                            <Icon name="cached" />
                        </Button>
                    </div>
                    <div class="bg-gray-200 dark:bg-gray-800 rounded p-4 flex flex-col gap-1">
                        <div v-for="(code, index) in recoveryCodesList" :key="index" class="font-mono text-sm">
                            {{ code }}
                        </div>
                    </div>
                    <p class="text-xs text-muted-foreground">
                        Jeder Wiederherstellungscode kann einmalig verwendet werden, um auf Ihr Konto zuzugreifen, und wird nach der
                        Nutzung entfernt. Wenn Sie weitere Codes benötigen, klicken Sie oben auf Codes neu generieren.
                    </p>
                </section>
                <section class="flex flex-col gap-4">
                    <HeadingSmall
                        title="Zwei-Faktor-Authentifizierung deaktivieren"
                        description="Nach der Deaktivierung müssen Sie sich bei jedem Login erneut mit Ihrem Passwort anmelden."
                    />
                    <Button variant="destructive" as-child class="self-start">
                        <Link :href="route('two-factor.disable')" method="delete">
                            <Icon name="safety_check_off" />
                            Deaktivieren
                        </Link>
                    </Button>
                </section>
            </div>

            <TwoFactorSetupModal v-model:is-open="showSetupModal" :two-factor-enabled />

        </AccountLayout>
    </AppLayout>

</template>
