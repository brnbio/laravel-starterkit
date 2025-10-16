<script setup lang="ts">

import { Field, FieldDescription, FieldLabel } from "@/components/ui/field";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import type { Option } from "@/types";
import { computed, inject } from "vue";

defineOptions({
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        name: string;
        label?: string;
        options: Option[];
        required?: boolean;
        placeholder?: string;
        description?: string;
    }>(),
    {
        label: "",
        description: "",
        required: false,
        placeholder: "Bitte auswählen"
    });

const form: any = inject("form");

const getValue = (obj: any, path: string): any => {
    return path.split(".").reduce((acc, key) => acc?.[key], obj);
};

const setValue = (obj: any, path: string, value: any): void => {
    const keys = path.split(".");
    const lastKey = keys.pop()!;
    const target = keys.reduce((acc, key) => acc[key], obj);
    target[lastKey] = value;
};

const modelValue = computed({
    get: () => getValue(form, props.name),
    set: (value) => setValue(form, props.name, value)
});

</script>

<template>

    <Field>
        <FieldLabel v-if="label">
            {{ label }}
        </FieldLabel>
        <Select :id="name" v-bind="$attrs" v-model="modelValue">
            <SelectTrigger class="w-full">
                <SelectValue :placeholder />
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-if="!required && modelValue !== null" value=" " class="text-gray-400 hover:text-gray-400" title="Zurücksetzen" @click.stop="modelValue = null">
                    &mdash;
                </SelectItem>
                <SelectItem v-for="option in options" :key="option.id" :value="option.id">
                    {{ option.text }}
                </SelectItem>
            </SelectContent>
        </Select>
        <FieldDescription v-if="description">
            {{ description }}
        </FieldDescription>
        <FieldDescription v-show="form.errors[name]" class="text-sm text-red-600 dark:text-red-500">
            {{ form.errors[name] }}
        </FieldDescription>
    </Field>

</template>