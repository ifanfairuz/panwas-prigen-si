<script setup>
import FooterSmall from "@/Components/Layout/FooterSmall.vue";
</script>
<template>
    <div>
        <main>
            <section class="relative w-full h-full py-40 min-h-screen">
                <div
                    class="absolute top-0 w-full h-full bg-slate-800 bg-no-repeat bg-full"
                />
                <slot />
                <footer-small absolute />
            </section>
        </main>
    </div>
</template>
