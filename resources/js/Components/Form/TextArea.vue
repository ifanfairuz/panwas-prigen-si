<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        ref="input"
        class="border-blue-300 px-2 py-2 placeholder-slate-500 text-slate-600 bg-gray-50 rounded text-sm shadow-md focus:outline-none w-full ease-linear transition-all duration-150"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
