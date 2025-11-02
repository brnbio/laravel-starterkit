<script setup lang="ts">

import { Heading } from "@/components";
import { FormControl, FormPassword, FormProvider, FormSubmit } from "@/components/forms";
import { FieldGroup, FieldSet } from "@/components/ui/field";
import { GuestLayout } from "@/layouts";
import { Link, useForm } from "@inertiajs/vue3";

const title = "Registrierung";

const form = useForm({
    name: null,
    email: null,
    password: null,
});

const submit = () => {
    form.post(route("register"));
};

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title description="Erstellen Sie Ihr Konto und starten Sie mit Ihrer Fortbildung." />
            <FormProvider :form>
                <form @submit.prevent="submit">
                    <FieldSet>
                        <FieldGroup>
                            <FormControl name="name" label="Name" required />
                            <FormControl name="email" label="E-Mail-Adresse" type="email" required />
                            <FormPassword name="password" label="Passwort" required description="Mindestens 8 Zeichen" />
                            <FormSubmit label="Registrieren" />
                        </FieldGroup>
                    </FieldSet>
                </form>
            </FormProvider>
            <div>
                Sie haben bereits einen Account?
                <Link :href="route('login')" class="font-bold text-primary">
                    Jetzt anmelden
                </Link>
            </div>
        </div>
    </GuestLayout>

</template>
