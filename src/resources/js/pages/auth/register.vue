<script setup lang="ts">

import { Heading } from "@/components";
import FormControl from "@/components/forms/FormControl.vue";
import FormPassword from "@/components/forms/FormPassword.vue";
import { Button } from "@/components/ui/button";
import { FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { GuestLayout } from "@/layouts";
import { Link, useForm } from "@inertiajs/vue3";
import { provide } from "vue";

const title = "Registrierung";

const form = useForm({
    name: null,
    email: null,
    password: null,
});

const submit = () => {
    form.post(route("register"));
};

provide("form", form);

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title description="Erstellen Sie Ihr Konto und starten Sie mit Ihrer Fortbildung." />
            <form @submit.prevent="submit">
                <FieldSet>
                    <FieldGroup>
                        <FormControl name="name" label="Name" required />
                        <FormControl name="email" label="E-Mail-Adresse" type="email" required />
                        <FormPassword name="password" label="Passwort" required description="Mindestens 8 Zeichen" />
                        <div>
                            <Button type="submit" size="default" :disabled="form.processing" class="grow-0">
                                <Spinner v-if="form.processing" />
                                Registrieren
                            </Button>
                        </div>
                    </FieldGroup>
                </FieldSet>
            </form>
            <div>
                Sie haben bereits einen Account?
                <Link :href="route('login')" class="font-bold text-primary">
                    Jetzt anmelden
                </Link>
            </div>
        </div>
    </GuestLayout>

</template>
