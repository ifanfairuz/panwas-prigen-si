<script setup>
const props = defineProps({
    label: String,
    value: String,
});
</script>

<template>
    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt class="text-sm font-medium text-gray-500">{{ label }}</dt>
        <dd
            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 font-semibold"
        >
            <slot>
                {{ value || "N/A" }}
            </slot>
        </dd>
    </div>
</template>
