<script setup>
import { useDropzone } from "vue3-dropzone";

const props = defineProps({ modelValue: Array, accept: String });
const emit = defineEmits(["update:modelValue", "change"]);

const onDrop = (f) => {
    const files = f.map((file) => ({ file }));
    emit("update:modelValue", files);
    emit("change", files);
};
const removeFile = (i) => {
    let f = props.modelValue;
    f.splice(i, 1);
    emit("update:modelValue", f);
    emit("change", f);
};
const { getRootProps, getInputProps, open, isDragActive } = useDropzone({
    onDrop,
    multiple: false,
    accept: props.accept,
    noClick: true,
});
</script>

<template>
    <div
        v-if="!modelValue || modelValue.length < 1"
        v-bind="getRootProps()"
        class="relative bg-slate-50 rounded-md text-center flex flex-col gap-4 items-center border border-blue-300 ease-linear transition-all duration-150"
        :class="[isDragActive ? 'border-2 border-blue-400 bg-slate-200' : '']"
    >
        <div class="p-6">
            <input v-bind="getInputProps()" />
            <span class="block text-xl text-slate-600">
                Seret file kesini
            </span>
            <span class="block text-base mb-2 mt-1 text-slate-400"> atau </span>
            <button
                type="button"
                class="mb-2 inline-flex rounded-3xl border border-blue-300 py-1 px-5 text-sm font-medium text-slate-800 bg-blue-200 hover:bg-blue-500 hover:text-white"
                @click="open"
            >
                Browse...
            </button>
            <span v-if="accept" class="block text-sm text-slate-400">
                [{{ accept }}]
            </span>
        </div>
    </div>
    <div v-else class="flex flex-col gap-4">
        <div
            v-for="(f, i) in modelValue"
            :key="i"
            class="rounded-md bg-blue-100 p-3"
        >
            <div class="flex items-center justify-between gap-2">
                <span class="truncate text-base font-medium text-[#07074D]">
                    <i class="fa fa-file mr-1"></i>
                    {{ f.file.name }}
                </span>
                <button
                    class="text-slate-800"
                    type="button"
                    @click="removeFile(i)"
                >
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div
                v-if="'progress' in f && f.progress < 100"
                class="relative mt-5 h-[6px] w-full rounded-lg bg-gray-300"
            >
                <div
                    class="absolute left-0 right-0 h-full rounded-lg bg-blue-500"
                    :style="`width: ${f.progress}%`"
                ></div>
            </div>
        </div>
    </div>
</template>
