<script setup lang="ts">

import { Heading, Section } from "@/components";
import { FormControl, FormPassword } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldDescription, FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { AppLayout } from "@/layouts";
import { useForm } from "@inertiajs/vue3";
import { provide } from "vue";

const props = defineProps<{
    email: string;
    token: string;
}>();

const title = "Neues Passwort erstellen";

const form = useForm({
    email: props.email,
    token: props.token,
    password: null,
    password_confirmation: null,
});

const submit = () => {
    form.post(route("password.store", [props.token]), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
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
                        Bitte geben Sie Ihr neues Passwort zweimal ein. Das Passwort muss mindestens 8 Zeichen lang sein.
                    </FieldDescription>
                    <FieldGroup>
                        <FormControl name="email" label="E-Mail-Adresse" readonly disabled />
                        <FormPassword name="password" label="Neues Passwort" />
                        <FormPassword name="password_confirmation" label="Neues Passwort bestätigen" />
                        <div>
                            <Button type="submit" :disabled="form.processing">
                                <Spinner v-if="form.processing" />
                                Passwort speichern
                            </Button>
                        </div>
                    </FieldGroup>
                </FieldSet>
            </form>
        </Section>
    </AppLayout>

</template>
