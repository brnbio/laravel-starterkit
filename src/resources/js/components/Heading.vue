<script setup lang="ts">

defineProps<{
    title: string;
    description?: string;
}>();

</script>

<template>

    <div class="flex flex-col gap-1">
        <h2 class="text-xl font-semibold tracking-tight">
            {{ title }}
        </h2>
        <p v-if="description" class="text-sm text-muted-foreground">
            {{ description }}
        </p>
    </div>

</template>
