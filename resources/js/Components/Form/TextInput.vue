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
    <input
        ref="input"
        class="bg-gray-50 focus:outline-none border-blue-300 focus:border-blue-400 ring-blue-400 outline-none px-2 py-2 placeholder-slate-400 text-slate-600 rounded-md text-sm shadow-md w-full ease-linear transition-all duration-150"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
