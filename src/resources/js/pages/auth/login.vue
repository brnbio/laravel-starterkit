<script setup lang="ts">

import { Heading, Section } from "@/components";
import FormControl from "@/components/forms/FormControl.vue";
import FormPassword from "@/components/forms/FormPassword.vue";
import { Button } from "@/components/ui/button";
import { FieldGroup } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import AppLayout from "@/layouts/AppLayout.vue";
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

    <AppLayout :title>
        <div class="flex flex-col">
            <Section>
                <Heading :title description="Willkommen zurück! Bitte melden Sie sich an." />
                <div>
                    <form @submit.prevent="submit" class="w-1/2">
                        <FieldGroup>
                            <FormControl name="email" label="E-Mail-Adresse" autofocus type="email" required />
                            <FormPassword name="password" label="Passwort" required>
                                <template #label>
                                    <Link :href="route('password.request')" class="text-sm underline text-gray-500" tabindex="-1">
                                        Passwort vergessen?
                                    </Link>
                                </template>
                            </FormPassword>
                            <div>
                                <Button type="submit" size="default" :disabled="form.processing" class="grow-0">
                                    <Spinner v-if="form.processing" />
                                    Anmelden
                                </Button>
                            </div>
                        </FieldGroup>
                    </form>
                </div>
            </Section>
            <Section>
                <p>
                    Sie haben noch keinen Account?
                    <Link :href="route('register')" class="font-bold text-primary">
                        Jetzt registrieren
                    </Link>
                </p>
            </Section>
        </div>
    </AppLayout>

</template>
