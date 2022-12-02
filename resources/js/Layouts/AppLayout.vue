<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import Navbar from "@/Components/Layout/Navbar.vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import Footer from "@/Components/Layout/Footer.vue";

defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Sidebar />
        <div class="relative md:ml-64 bg-slate-50 min-h-screen flex flex-col">
            <Navbar>
                <template #header>
                    <slot name="header" />
                </template>
            </Navbar>
            <div class="px-4 md:px-10 flex flex-col flex-1">
                <!-- Page Content -->
                <main class="flex-1">
                    <slot />
                </main>
                <Footer class="mt-auto" />
            </div>
        </div>
    </div>
</template>
