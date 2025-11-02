<script setup lang="ts">

import { Icon } from "@/components";
import { cn } from "@/lib/utils";
import type { HTMLAttributes } from "vue";

const props = defineProps<{
    class?: HTMLAttributes["class"]
}>();

</script>

<template>

    <span :class="cn('inline-flex self-start animate-spin', props.class)" role="status" aria-label="Loading">
        <Icon name="progress_activity" />
    </span>

</template>
