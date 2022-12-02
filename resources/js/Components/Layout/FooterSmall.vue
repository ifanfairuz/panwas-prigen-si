<template>
    <footer
        class="pb-6"
        :class="[
            absolute ? 'absolute w-full bottom-0 bg-slate-800' : 'relative',
        ]"
    >
        <div class="container mx-auto px-4">
            <hr class="mb-6 border-b-1 border-slate-600" />
            <div
                class="flex flex-wrap items-center md:justify-end justify-center"
            >
                <div class="w-full md:w-4/12 px-4">
                    <div
                        class="text-sm text-slate-500 font-semibold py-1 text-center md:text-right"
                    >
                        Copyright © {{ date }}
                        <a
                            href="https://www.creative-tim.com?ref=vn-footer-small"
                            class="text-white hover:text-slate-300 text-sm font-semibold py-1"
                        >
                            Panwascam Prigen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    data() {
        return {
            date: new Date().getFullYear(),
        };
    },
    props: {
        absolute: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
