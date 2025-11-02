<script setup lang="ts">

import { Button } from "@/components/ui/button";
import { Spinner } from "@/components/ui/spinner";
import { InertiaForm } from "@inertiajs/vue3";
import { inject } from "vue";

const form = inject<InertiaForm<any>>("form");

withDefaults(defineProps<{
    label?: string;
    variant?: "default" | "destructive" | "outline" | "secondary" | "ghost";
}>(), {
    label: "Speichern",
    variant: "default",
});

</script>

<template>

    <div>
        <Button type="submit" :disabled="form.processing" :variant>
            <Spinner v-if="form.processing" />
            {{ label }}
        </Button>
    </div>

</template>