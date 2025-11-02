<script setup lang="ts">

import { Icon } from "@/components";
import { Button } from "@/components/ui/button";
import type { HTMLAttributes } from "vue";
import { useSidebar } from "./utils";

const props = defineProps<{
    class?: HTMLAttributes["class"]
}>();

const { toggleSidebar } = useSidebar();

</script>

<template>

    <Button data-sidebar="trigger" data-slot="sidebar-trigger" variant="ghost" size="icon" :class="props.class" @click="toggleSidebar">
        <Icon name="thumbnail_bar" />
        <span class="sr-only">Toggle Sidebar</span>
    </Button>

</template>
