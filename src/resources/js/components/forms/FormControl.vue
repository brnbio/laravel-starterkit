<script setup lang="ts">

import { Field, FieldDescription, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { InertiaForm } from "@inertiajs/vue3";
import { inject } from "vue";

defineOptions({
    inheritAttrs: false,
});

defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
    description?: string;
}>();

const form = inject<InertiaForm<any>>("form");

</script>

<template>

    <Field>
        <FieldLabel v-if="label" :for="name">
            {{ label }}
        </FieldLabel>
        <Input :id="name" v-bind="$attrs" v-model="form[name]" :placeholder="placeholder" />
        <FieldDescription v-if="description">
            {{ description }}
        </FieldDescription>
        <FieldDescription v-show="form.errors[name]" class="text-sm text-red-600 dark:text-red-500">
            {{ form.errors[name] }}
        </FieldDescription>
    </Field>

</template>