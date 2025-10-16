<script setup lang="ts">

import { Heading, Section } from "@/components";
import FormControl from "@/components/forms/FormControl.vue";
import FormPassword from "@/components/forms/FormPassword.vue";
import FormSelect from "@/components/forms/FormSelect.vue";
import { Button } from "@/components/ui/button";
import { FieldDescription, FieldGroup, FieldLegend, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import AppLayout from "@/layouts/AppLayout.vue";
import { Option } from "@/types";
import { Link, useForm } from "@inertiajs/vue3";
import { provide } from "vue";

defineProps<{
    prefixes: Option[];
}>();

const title = "Registrierung";

const form = useForm({
    prefix: null,
    first_name: null,
    last_name: null,
    phone: null,
    email: null,
    password: null,
    password_confirmation: null,
});

const submit = () => {
    form.post(route("register"));
};

provide("form", form);

</script>

<template>

    <AppLayout :title>
        <div class="flex flex-col">
            <Section>
                <Heading :title description="Erstellen Sie Ihr Konto und starten Sie mit Ihrer Fortbildung." />
                <div>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <FieldSet>
                            <FieldLegend variant="legend">
                                Stammdaten
                            </FieldLegend>
                            <FieldDescription>
                                Bitte geben Sie Ihre Stammdaten ein. Diese Informationen benötigen für Ihre Fortbildung.
                            </FieldDescription>
                            <FieldGroup>
                                <div class="w-fit">
                                    <FormSelect name="prefix" label="Anrede" :options="prefixes" autofocus required />
                                </div>
                                <div class="grid grid-cols-2 gap-8">
                                    <FormControl name="first_name" label="Vorname" type="text" required />
                                    <FormControl name="last_name" label="Nachname" type="text" required />
                                </div>
                                <FormControl name="phone" label="Telefon" type="tel" />
                            </FieldGroup>
                            <FieldGroup>
                                <div>
                                    <Button type="submit" size="default" :disabled="form.processing" class="grow-0">
                                        <Spinner v-if="form.processing" />
                                        Registrieren
                                    </Button>
                                </div>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSet>
                            <FieldLegend variant="legend">
                                Zugangsdaten für Benutzerkonto
                            </FieldLegend>
                            <FieldDescription>
                                Hier finden Sie Ihre Rechnungen und Teilnahmebescheinigungen.
                            </FieldDescription>
                            <FieldGroup>
                                <FormControl name="email" label="E-Mail-Adresse" type="email" required />
                                <FormPassword name="password" label="Passwort" :show-strength="true" required />
                                <FormPassword name="password_confirmation" label="Passwort bestätigen" required />
                            </FieldGroup>
                        </FieldSet>
                    </form>
                </div>
            </Section>
            <Section>
                <p>
                    Sie haben bereits einen Account?
                    <Link :href="route('login')" class="font-bold text-primary">
                        Jetzt anmelden
                    </Link>
                </p>
            </Section>
        </div>
    </AppLayout>

</template>
