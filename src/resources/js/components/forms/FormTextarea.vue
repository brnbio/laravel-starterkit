<script setup lang="ts">

import { FieldDescription } from "@/components/ui/field";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { inject } from "vue";

defineOptions({
    inheritAttrs: false,
});

defineProps<{
    name: string;
    label?: string;
    description?: string;
    placeholder?: string;
}>();

const form: any = inject("form");

</script>

<template>

    <div class="grid gap-2">
        <Label v-if="label" :for="name">
            {{ label }}
        </Label>
        <Textarea :id="name" v-bind="$attrs" v-model="form[name]" :placeholder="placeholder ?? label" />
        <FieldDescription v-if="description">
            {{ description }}
        </FieldDescription>
        <FieldDescription v-show="form.errors[name]" class="text-sm text-red-600 dark:text-red-500">
            {{ form.errors[name] }}
        </FieldDescription>
    </div>

</template>