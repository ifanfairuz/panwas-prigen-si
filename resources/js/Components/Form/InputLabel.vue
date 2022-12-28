<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <slot v-else />
    </label>
</template>
