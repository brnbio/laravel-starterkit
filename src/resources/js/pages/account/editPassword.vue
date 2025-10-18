<script setup lang="ts">

import { FormPassword, FormProvider } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { AccountLayout, AppLayout } from "@/layouts";
import { type BreadcrumbItem } from "@/types";
import { useForm } from "@inertiajs/vue3";

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("account.password"),
    },
];

const form = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

const submit = () => form.patch(route("account.password.update"), {
    onSuccess: () => {
        form.reset();
        form.clearErrors();
    }
});

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <AccountLayout active="password">
            <FormProvider :form>
                <form @submit.prevent="submit">
                    <FieldSet>
                        <FieldGroup>
                            <FormPassword name="current_password" label="Aktuelles Passwort" required />
                            <FormPassword name="password" label="Neues Passwort" required />
                            <FormPassword name="password_confirmation" label="Neues Passwort bestätigen" required />
                            <Button class="self-start" type="submit" :disabled="form.processing">
                                Speichern
                                <Spinner v-if="form.processing" />
                            </Button>
                        </FieldGroup>
                    </FieldSet>
                </form>
            </FormProvider>
        </AccountLayout>
    </AppLayout>

</template>
