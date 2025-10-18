<script setup lang="ts">

import { provide } from "vue";

const props = defineProps<{
    form: any;
}>();

provide("form", props.form);

</script>

<template>

    <slot />

</template>
