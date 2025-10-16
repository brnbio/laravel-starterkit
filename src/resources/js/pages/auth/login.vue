<script setup lang="ts">

import { Heading } from "@/components";
import { FormControl, FormPassword } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldGroup } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { GuestLayout } from "@/layouts";
import { Link, useForm } from "@inertiajs/vue3";
import { provide } from "vue";

const title = "Anmeldung";

const form = useForm({
    email: null,
    password: null,
    remember: true,
});

const submit = () => {
    form.post(route("login"));
};

provide("form", form);

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title description="Willkommen zurück! Bitte melden Sie sich an." />
            <form @submit.prevent="submit">
                <FieldGroup>
                    <FormControl name="email" label="E-Mail-Adresse" autofocus type="email" required />
                    <FormPassword name="password" required>
                        <template #label>
                            Passwort
                            <Link :href="route('password.request')" class="text-sm underline text-gray-500" tabindex="-1">
                                Passwort vergessen?
                            </Link>
                        </template>
                    </FormPassword>
                    <div>
                        <Button type="submit" size="default" :disabled="form.processing" class="grow-0">
                            Anmelden
                            <Spinner v-if="form.processing" />
                        </Button>
                    </div>
                </FieldGroup>
            </form>
            <div>
                Sie haben noch keinen Account?
                <Link :href="route('register')" class="font-bold text-primary">
                    Jetzt registrieren
                </Link>
            </div>
        </div>
    </GuestLayout>

</template>
