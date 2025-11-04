<script setup lang="ts">

import { Field, FieldDescription, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { VisibilityOffOutline, VisibilityOutline } from "@brnbio/vue-material-design-icons";
import { InertiaForm } from "@inertiajs/vue3";
import { inject, ref } from "vue";

defineOptions({
    inheritAttrs: false,
});

defineProps<{
    name: string;
    label?: string;
    description?: string;
}>();

const form = inject<InertiaForm<any>>("form");

const passwordVisible = ref(false);

const toggle = () => {
    passwordVisible.value = !passwordVisible.value;
};

</script>

<template>

    <Field>
        <FieldLabel :for="name" class="flex justify-between">
            <slot name="label">
                {{ label }}
            </slot>
        </FieldLabel>
        <div class="relative w-full items-center">
            <Input :id="name" v-bind="$attrs" v-model="form[name]" :type="passwordVisible ? 'text': 'password'" />
            <span class="absolute end-0 inset-y-0 flex items-center justify-center px-2 cursor-pointer" @click="toggle">
                <VisibilityOffOutline v-if="passwordVisible" :size="16" class="text-muted-foreground" />
                <VisibilityOutline v-else :size="16" class="text-muted-foreground" />
            </span>
        </div>
        <FieldDescription v-if="description">
            {{ description }}
        </FieldDescription>
        <FieldDescription v-show="form.errors[name]" class="text-sm text-red-600 dark:text-red-500">
            {{ form.errors[name] }}
        </FieldDescription>
    </Field>

</template>