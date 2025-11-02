<script setup lang="ts">

withDefaults(defineProps<{
    height?: number;
}>(), {
    height: 40,
});

</script>

<template>

    <img src="/images/logo.svg" alt="" :height="height">

</template>
