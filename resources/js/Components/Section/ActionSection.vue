<script setup>
import SectionTitle from "./SectionTitle.vue";
</script>

<template>
    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #aside>
                <slot name="aside" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>
        <hr class="my-2" />
        <slot name="content" />
    </div>
</template>
