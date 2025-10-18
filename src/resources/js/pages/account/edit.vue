<script setup lang="ts">

import { FormControl } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { AppLayout } from "@/layouts";
import AccountLayout from "@/layouts/AccountLayout.vue";
import { type BreadcrumbItem } from "@/types";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { provide } from "vue";

const title = "Mein Konto";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: route("account.edit"),
    },
];

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => form.patch(route("account.update"));

provide("form", form);

</script>

<template>

    <AppLayout :breadcrumbs :title>
        <AccountLayout active="account">
            <form @submit.prevent="submit">
                <FieldSet>
                    <FieldGroup>
                        <FormControl name="name" label="Name" required />
                        <FormControl name="email" label="E-Mail" type="email" required />
                        <Button class="self-start" type="submit" :disabled="form.processing">
                            Speichern
                            <Spinner v-if="form.processing" />
                        </Button>
                    </FieldGroup>
                </FieldSet>
            </form>
            <div v-if="!user.email_verified_at" class="text-sm text-muted-foreground flex flex-col gap-1 items-start">
                Ihre E-Mail-Adresse ist noch nicht verifiziert.
                <Link href="#" class="text-foreground underline decoration-neutral-300 underline-offset-4">
                    Klicken Sie hier, um die Bestätigungs-E-Mail erneut zu senden.
                </Link>
            </div>
        </AccountLayout>
    </AppLayout>

</template>
