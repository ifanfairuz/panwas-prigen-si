<script setup>
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
    small: Boolean,
});
</script>

<template>
    <Link :href="'/'">
        <img
            src="/img/logo.png"
            alt="logo"
            :class="{ 'h-16': !small, 'h-10': small }"
        />
    </Link>
</template>
