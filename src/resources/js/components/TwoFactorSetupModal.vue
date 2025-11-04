<script setup lang="ts">

import { Button } from "@/components/ui/button";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, } from "@/components/ui/dialog";
import { Field, FieldDescription, FieldGroup, FieldSeparator, FieldSet } from "@/components/ui/field";
import { InputGroup, InputGroupButton, InputGroupInput } from "@/components/ui/input-group";
import { PinInput, PinInputGroup, PinInputSlot } from "@/components/ui/pin-input";
import { useTwoFactorAuth } from "@/composables/useTwoFactorAuth";
import { CheckRounded, ContentCopyOutline } from "@brnbio/vue-material-design-icons";
import { Form } from "@inertiajs/vue3";
import { useClipboard } from "@vueuse/core";
import { computed, ref, watch } from "vue";

interface ModalConfig {
    title: string;
    description: string;
    buttonText: string;
}

const props = defineProps<{
    twoFactorEnabled: boolean;
}>();

const isOpen = defineModel<boolean>("isOpen");

const { copy, copied } = useClipboard();
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData } = useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<number[]>([]);
const codeValue = computed<string>(() => code.value.join(""));

const modalConfig = computed<ModalConfig>(() => {

    if (props.twoFactorEnabled) {
        return {
            title: "Zwei-Faktor-Authentifizierung aktiviert",
            description: "Die Zwei-Faktor-Authentifizierung ist jetzt aktiviert. Scannen Sie den QR-Code oder geben Sie den Einrichtungsschlüssel in Ihrer Authentifizierungs-App ein.",
            buttonText: "Schließen",
        };
    }

    if (showVerificationStep.value) {
        return {
            title: "Authentifizierungscode bestätigen",
            description: "Geben Sie den 6-stelligen Code aus Ihrer Authentifizierungs-App ein",
            buttonText: "Weiter",
        };
    }

    return {
        title: "Zwei-Faktor-Authentifizierung aktivieren",
        description: "Um die Zwei-Faktor-Authentifizierung abzuschließen, scannen Sie den QR-Code oder geben Sie den Einrichtungsschlüssel in Ihrer Authentifizierungs-App ein",
        buttonText: "Weiter",
    };

});

const handleModalNextStep = () => {
    showVerificationStep.value = true;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData();
    }
    showVerificationStep.value = false;
    code.value = [];
};

watch(() => isOpen.value, async (isOpen) => {
    if (!isOpen) {
        resetModalState();
        return;
    }

    if (!qrCodeSvg.value) {
        await fetchSetupData();
    }
});

</script>

<template>

    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>
                    {{ modalConfig.title }}
                </DialogTitle>
                <DialogDescription>
                    {{ modalConfig.description }}
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-5">
                <FieldSet v-if="!showVerificationStep">
                    <FieldGroup>
                        <div class="relative mx-auto aspect-square w-64 overflow-hidden rounded-lg border border-border">
                            <div class="flex aspect-square size-full items-center justify-center" v-html="qrCodeSvg" />
                        </div>
                    </FieldGroup>
                    <FieldSeparator>oder</FieldSeparator>
                    <InputGroup>
                        <InputGroup>
                            <InputGroupInput :value="manualSetupKey" />
                            <InputGroupButton @click="copy(manualSetupKey || '')">
                                <CheckRounded v-if="copied" />
                                <ContentCopyOutline v-else />
                            </InputGroupButton>
                        </InputGroup>
                    </InputGroup>
                    <Button class="w-full" @click="handleModalNextStep">
                        {{ modalConfig.buttonText }}
                    </Button>
                </FieldSet>
                <template v-else>
                    <Form v-slot="{ errors, processing }" :action="route('two-factor.confirm')" method="post" @finish="code = []" @success="isOpen = false">
                        <FieldSet>
                            <input type="hidden" name="code" :value="codeValue">
                            <FieldGroup>
                                <Field>
                                    <PinInput id="otp" v-model="code" placeholder="○" type="number" otp>
                                        <PinInputGroup>
                                            <PinInputSlot v-for="(id, index) in 6" :key="id" :index :disabled="processing" />
                                        </PinInputGroup>
                                    </PinInput>
                                    <FieldDescription v-show="errors?.confirmTwoFactorAuthentication?.code" class="text-sm text-red-600 dark:text-red-500">
                                        {{ errors?.confirmTwoFactorAuthentication?.code }}
                                    </FieldDescription>
                                </Field>
                                <div class="flex gap-2">
                                    <Button type="button" variant="outline" :disabled="processing" @click="showVerificationStep = false">
                                        Zurück
                                    </Button>
                                    <Button :disabled="processing || codeValue.length < 6">
                                        2FA bestätigen
                                    </Button>
                                </div>
                            </FieldGroup>
                        </FieldSet>
                    </Form>
                </template>
            </div>
        </DialogContent>
    </Dialog>

</template>