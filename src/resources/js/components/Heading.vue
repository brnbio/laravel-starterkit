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
        <div v-if="description || $slots.description" class="text-sm text-muted-foreground">
            <slot name="description">
                {{ description }}
            </slot>
        </div>
    </div>

</template>
