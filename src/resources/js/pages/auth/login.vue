<script setup lang="ts">

import { Link, useForm } from "@inertiajs/vue3";
import login from "@/types/wayfinder/routes/login";
import password from "@/types/wayfinder/routes/password";
import { register } from "@/types/wayfinder/routes";

const title = "Anmeldung";

const form = useForm({
    email: null,
    password: null,
    remember: true,
});

const submit = () => {
    form.post(login.store.url());
};

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title description="Willkommen zurück! Bitte melden Sie sich an." />
            <FormProvider :form>
                <form @submit.prevent="submit">
                    <FieldGroup>
                        <FormControl name="email" label="E-Mail-Adresse" autofocus type="email" required />
                        <FormPassword name="password" required>
                            <template #label>
                                Passwort
                                <Link :href="password.request()" class="text-sm underline text-gray-500" tabindex="-1">
                                    Passwort vergessen?
                                </Link>
                            </template>
                        </FormPassword>
                        <FormSubmit label="Anmelden" />
                    </FieldGroup>
                </form>
            </FormProvider>
            <div>
                Sie haben noch keinen Account?
                <Link :href="register()" class="font-bold text-primary">
                    Jetzt registrieren
                </Link>
            </div>
        </div>
    </GuestLayout>

</template>
