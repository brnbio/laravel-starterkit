<script setup lang="ts">

import { Heading, Section } from "@/components";
import { FormControl } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldDescription, FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { AppLayout } from "@/layouts";
import { Link, useForm } from "@inertiajs/vue3";
import { provide } from "vue";

const title = "Passwort vergessen";

const form = useForm({
    email: null,
});

const submit = () => {
    form.post(route("password.email"));
};

provide("form", form);

</script>

<template>

    <AppLayout :title>
        <Section>
            <Heading :title />
            <form @submit.prevent="submit" class="w-1/2">
                <FieldSet>
                    <FieldDescription>
                        Bitte geben Sie Ihren Benutzernamen oder Ihre E-Mail-Adresse ein. Anweisungen zum
                        Zurücksetzen Ihres Passworts werden Ihnen umgehend per E-Mail zugesandt.
                    </FieldDescription>
                    <FieldGroup>
                        <FormControl name="email" label="E-Mail-Adresse" type="email" required />
                        <div class="flex gap-2">
                            <Button type="submit" size="default" :disabled="form.processing" class="grow-0">
                                <Spinner v-if="form.processing" />
                                Neues Passwort anfordern
                            </Button>
                            <Button as-child variant="ghost" class="text-gray-400">
                                <Link :href="route('login')" class="grow-0">
                                    Zurück zum Login
                                </Link>
                            </Button>
                        </div>
                    </FieldGroup>
                </FieldSet>
            </form>
        </Section>
    </AppLayout>

</template>
