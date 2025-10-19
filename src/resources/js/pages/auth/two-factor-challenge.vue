<script setup lang="ts">

import { Button } from "@/components/ui/button";
import { Field, FieldDescription, FieldGroup, FieldLegend, FieldSet } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { PinInput, PinInputGroup, PinInputSlot } from "@/components/ui/pin-input";
import { GuestLayout } from "@/layouts";
import { Form } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const title = "Zwei-Faktor-Authentifizierung";

const showRecoveryInput = ref<boolean>(false);

const code = ref<number[]>([]);

const codeValue = computed<string>(() => code.value.join(""));

const toggle = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = [];
};

</script>

<template>

    <GuestLayout :title>
        <div class="flex flex-col gap-8">
            <template v-if="showRecoveryInput">
                <Form v-slot="{ errors, processing, clearErrors }" :action="route('two-factor.login.store')" method="post">
                    <FieldSet>
                        <FieldLegend variant="legend">
                            Wiederherstellungscode eingeben
                        </FieldLegend>
                        <FieldDescription>
                            Bitte bestätigen Sie den Zugriff auf Ihr Konto, indem Sie einen Ihrer Notfall-Wiederherstellungscodes eingeben.
                        </FieldDescription>
                        <Field>
                            <Input name="recovery_code" :autofocus="showRecoveryInput" required />
                            <FieldDescription v-show="errors.recovery_code" class="text-sm text-red-600 dark:text-red-500">
                                {{ errors.recovery_code }}
                            </FieldDescription>
                        </Field>
                        <div class="flex flex-col gap-4 items-start">
                            <Button type="submit" :disabled="processing">
                                Weiter
                            </Button>
                            <span class="cursor-pointer text-sm text-gray-500" @click="toggle(clearErrors)">
                                Mit einem Authentifizierungscode anmelden
                            </span>
                        </div>
                    </FieldSet>
                </Form>
            </template>
            <template v-else>
                <Form v-slot="{ errors, processing, clearErrors }" :action="route('two-factor.login.store')" method="post" @error="code = []">
                    <FieldSet>
                        <input type="hidden" name="code" :value="codeValue">
                        <FieldLegend variant="legend">
                            Authentifizierungscode eingeben
                        </FieldLegend>
                        <FieldDescription>
                            Geben Sie den Authentifizierungscode ein, der von Ihrer Authentifizierungs-App bereitgestellt wird.
                        </FieldDescription>
                        <FieldGroup>
                            <Field>
                                <PinInput id="otp" v-model="code" placeholder="○" type="number" otp>
                                    <PinInputGroup>
                                        <PinInputSlot v-for="(id, index) in 6" :key="id" :index="index" :disabled="processing" />
                                    </PinInputGroup>
                                </PinInput>
                                <FieldDescription v-if="errors.code" class="text-sm text-red-600 dark:text-red-500">
                                    {{ errors.code }}
                                </FieldDescription>
                            </Field>
                        </FieldGroup>
                        <div class="flex flex-col gap-4 items-start">
                            <Button type="submit" :disabled="processing">
                                Weiter
                            </Button>
                            <span class="cursor-pointer text-sm text-gray-500" @click="toggle(clearErrors)">
                                Mit einem Wiederherstellungscode anmelden
                            </span>
                        </div>
                    </FieldSet>
                </Form>
            </template>
        </div>
    </GuestLayout>

</template>
