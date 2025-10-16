<script setup lang="ts">

import { Checkbox } from "@/components/ui/checkbox";
import { Field, FieldContent, FieldDescription, FieldLabel } from "@/components/ui/field";
import { inject } from "vue";

defineOptions({
    inheritAttrs: false,
});

defineProps<{
    name: string;
    label: string;
    description?: string;
}>();

const form: any = inject("form");

</script>

<template>

    <Field orientation="horizontal">
        <Checkbox :id="name" v-model="form[name]" />
        <FieldContent>
            <FieldLabel :for="name" class="font-normal cursor-pointer">
                {{ label }}
            </FieldLabel>
            <FieldDescription v-if="description">
                {{ description }}
            </FieldDescription>
            <FieldDescription v-show="form.errors[name]" class="text-sm text-red-600 dark:text-red-500">
                {{ form.errors[name] }}
            </FieldDescription>
        </FieldContent>
    </Field>

</template>