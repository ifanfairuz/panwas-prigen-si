<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Navbar from "@/Components/Layout/Navbar.vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
// import Footer from "@/Components/Layout/Footer.vue";

defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Sidebar />
        <div class="relative md:ml-64 bg-slate-100">
            <Navbar>
                <template #header>
                    <slot name="header" />
                </template>
            </Navbar>
            <div class="px-4 md:px-10 mx-auto w-full">
                <!-- Page Content -->
                <main>
                    <slot />
                </main>
                <!-- <Footer /> -->
            </div>
        </div>

        <!-- Page Heading -->
        <!-- <header v-if="$slots.header" class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header> -->
    </div>
</template>
