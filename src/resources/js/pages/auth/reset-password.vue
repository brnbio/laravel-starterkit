<script setup lang="ts">

import { Heading } from "@/components";
import { FormControl, FormPassword } from "@/components/forms";
import { Button } from "@/components/ui/button";
import { FieldDescription, FieldGroup, FieldSet } from "@/components/ui/field";
import { Spinner } from "@/components/ui/spinner";
import { GuestLayout } from "@/layouts";
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
    form.post(route("password.store", [ props.token ]), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};

provide("form", form);

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <Heading :title />
            <form @submit.prevent="submit">
                <FieldSet>
                    <FieldDescription>
                        Bitte geben Sie Ihr neues Passwort zweimal ein. Das Passwort muss mindestens 8 Zeichen lang sein.
                    </FieldDescription>
                    <FieldGroup>
                        <FormPassword name="password" label="Neues Passwort" />
                        <FormPassword name="password_confirmation" label="Neues Passwort bestätigen" />
                        <div>
                            <Button type="submit" :disabled="form.processing">
                                Passwort speichern
                                <Spinner v-if="form.processing" />
                            </Button>
                        </div>
                    </FieldGroup>
                </FieldSet>
            </form>
        </div>
    </GuestLayout>

</template>
