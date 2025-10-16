<script setup lang="ts">

defineProps<{
    title: string;
    description?: string;
}>();

</script>

<template>

    <div class="flex flex-col gap-1">
        <h3 class="text-base font-medium">
            {{ title }}
        </h3>
        <div v-if="description" class="text-sm text-muted-foreground">
            {{ description }}
        </div>
    </div>

</template>
