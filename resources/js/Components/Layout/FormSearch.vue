<script setup>
import TextInput from "../Form/TextInput.vue";
</script>

<template>
    <form>
        <div class="relative flex w-full flex-wrap items-stretch">
            <span
                class="z-10 h-full leading-snug font-normal absolute text-center text-slate-300 absolute bg-transparent rounded text-base items-center justify-center w-7 pl-2 py-2"
            >
                <i class="fas fa-search"></i>
            </span>
            <TextInput
                type="text"
                placeholder="Search here..."
                class="bg-slate-100 pl-9"
            />
        </div>
    </form>
</template>
