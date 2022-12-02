<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    href: String,
    active: Boolean,
});
</script>

<template>
    <li class="items-center">
        <Link
            :href="href"
            class="text-xs block py-1"
            :class="[
                active
                    ? 'text-indigo-500 hover:text-indigo-600'
                    : 'text-slate-700 hover:text-slate-500',
            ]"
        >
            <slot />
        </Link>
    </li>
</template>
