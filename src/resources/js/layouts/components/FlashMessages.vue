<script setup lang="ts">

import { Toaster } from "@/components/ui/sonner";
import { useAppearance } from "@/composables/useAppearance";
import type { FlashMessage } from "@/types/index.d";
import { usePage } from "@inertiajs/vue3";
import { onMounted, watch } from "vue";
import { toast } from "vue-sonner";

const page = usePage();

const { appearance } = useAppearance();

function showFlashMessages(messages: FlashMessage[] | null | undefined) {
    messages?.forEach((message) => {
        if (message.level === "success") {
            toast.success("Erfolgreich", { description: message.message });
            return;
        }
        if (message.level === "danger") {
            toast.error("Fehler", { description: message.message });
            return;
        }
        if (message.level === "warning") {
            toast.warning("Achtung", { description: message.message });
            return;
        }
        if (message.level === "info") {
            toast.info("Info", { description: message.message });
            return;
        }

        return toast.message(message.message);
    });
}

onMounted(() => {
    showFlashMessages(page.props.flash as FlashMessage[]);
});

watch(
    () => page.props.flash,
    (messages) => showFlashMessages(messages as FlashMessage[]),
);

</script>

<template>

    <Toaster rich-colors :expand="true" position="bottom-right" :theme="appearance" />

</template>