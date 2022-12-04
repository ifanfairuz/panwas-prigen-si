<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    href: String,
    active: Boolean,
});
</script>

<template>
    <li class="items-center mb-1">
        <Link
            :href="href"
            class="text-sm block p-2 rounded"
            :class="[
                active
                    ? 'text-white hover:bg-blue-600 bg-blue-500'
                    : 'hover:bg-slate-100',
            ]"
        >
            <slot />
        </Link>
    </li>
</template>
