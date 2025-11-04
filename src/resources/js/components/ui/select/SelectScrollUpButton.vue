<script setup lang="ts">

import { cn } from "@/lib/utils";
import { KeyboardArrowUp } from "@brnbio/vue-material-design-icons";
import { reactiveOmit } from "@vueuse/core";
import type { SelectScrollUpButtonProps } from "reka-ui";
import { SelectScrollUpButton, useForwardProps } from "reka-ui";
import type { HTMLAttributes } from "vue";

const props = defineProps<SelectScrollUpButtonProps & { class?: HTMLAttributes["class"] }>();

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);

</script>

<template>

    <SelectScrollUpButton v-bind="forwardedProps" :class="cn('flex cursor-default items-center justify-center py-1', props.class)">
        <slot>
            <KeyboardArrowUp class="h-4 w-4" />
        </slot>
    </SelectScrollUpButton>

</template>
