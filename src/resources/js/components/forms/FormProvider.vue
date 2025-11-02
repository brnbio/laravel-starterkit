<script setup lang="ts">

import { InertiaForm } from "@inertiajs/vue3";
import { provide } from "vue";

const props = defineProps<{
    form: InertiaForm<any>;
}>();

provide("form", props.form);

</script>

<template>

    <slot />

</template>
