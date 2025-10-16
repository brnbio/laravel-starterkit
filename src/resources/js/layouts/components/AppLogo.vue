<script setup lang="ts">

defineProps<{
    height?: number;
}>();

</script>

<template>

    <img src="/images/logo.svg" alt="" :height="height">

</template>
