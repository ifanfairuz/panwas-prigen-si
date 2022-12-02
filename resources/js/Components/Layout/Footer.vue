<script setup>
const date = new Date().getFullYear();
</script>

<template>
    <footer class="relative pb-6">
        <div class="container mx-auto px-4">
            <hr class="my-6 border-slate-200" />
            <div
                class="flex flex-wrap items-center md:justify-between justify-center"
            >
                <div class="w-full md:w-4/12 px-4 mx-auto text-center">
                    <div class="text-sm text-slate-500 font-semibold py-1">
                        Copyright © {{ date }} PANWASCAM PRIGEN.
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
