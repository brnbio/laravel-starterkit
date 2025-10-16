<script setup lang="ts">

import { cn } from "@/lib/utils";
import { computed } from "vue";

const props = withDefaults(defineProps<{
    name: string;
    class?: string;
    fill?: boolean;
    weight?: number;
    grade?: number;
    opticalSize?: number;
}>(), {
    class: "",
    fill: false,
    weight: 400,
    grade: 0,
    opticalSize: 24
});

const className = computed(() => cn("material-symbols-rounded", props.class));

const style = computed(() => ({
    fontVariationSettings: `'FILL' ${ props.fill ? 1 : 0 }, 'wght' ${ props.weight }, 'GRAD' ${ props.grade }, 'opsz' ${ props.opticalSize }`
}));

</script>

<template>

    <span :class="className" :style="style">
        {{ name }}
    </span>

</template>
