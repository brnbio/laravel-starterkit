<script setup lang="ts">

import { Link, useForm } from "@inertiajs/vue3";
import password from "@/types/wayfinder/routes/password";
import { login } from "@/types/wayfinder/routes";

const title = "Passwort vergessen";

const form = useForm({
    email: null,
});

const submit = () => {
    form.post(password.email.url());
};

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title />
            <FormProvider :form>
                <form @submit.prevent="submit">
                    <FieldSet>
                        <FieldDescription>
                            Bitte geben Sie Ihre E-Mail-Adresse ein. Anweisungen zum
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
                                    <Link :href="login()" class="grow-0">
                                        Zurück zum Login
                                    </Link>
                                </Button>
                            </div>
                        </FieldGroup>
                    </FieldSet>
                </form>
            </FormProvider>
        </div>
    </GuestLayout>

</template>
