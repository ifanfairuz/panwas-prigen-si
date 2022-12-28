<script setup>
import { ref, computed, useAttrs, onMounted, onUnmounted } from "vue";

const { headers } = useAttrs();
const inputComp = ref(null);
const search = ref("");
const searchFields = computed(() =>
    headers
        .filter((h) => !("searchable" in h) || h.searchable)
        .map((h) => h.value)
);
const focusSearch = (e) => {
    if (e.keyCode == 191 && inputComp.value !== document.activeElement) {
        e.preventDefault();
        inputComp.value.focus();
    }
};
onMounted(() => {
    window.removeEventListener("keydown", focusSearch);
    window.addEventListener("keydown", focusSearch);
});
onUnmounted(() => {
    window.removeEventListener("keydown", focusSearch);
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <div
            class="flex flex-col items-start lg:flex-row lg:items-center gap-2 justify-between"
        >
            <slot name="filter">
                <div></div>
            </slot>
            <div class="flex items-center gap-4">
                <div
                    class="py-2 px-1 flex items-center rounded-3xl overflow-hidden border border-blue-300 placeholder-slate-400 text-slate-600 bg-gray-50 rounded text-sm shadow-md focus:outline-none w-[300px]"
                >
                    <span
                        class="leading-snug font-normal text-center text-slate-300 bg-transparent rounded text-base pl-3"
                    >
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        ref="inputComp"
                        class="border-0 focus:border-0 focus:ring-0 focus:-ml-7 -mr-8 rounded-3xl pl-2 pr-2 py-0 placeholder-slate-400 text-slate-600 bg-gray-50 rounded text-sm outline-none w-full ease-linear transition-all duration-100"
                        v-model="search"
                        placeholder="Tekan '/' untuk mencari..."
                        type="search"
                    />
                </div>
            </div>
        </div>
        <EasyDataTable
            theme-color="#2563eb"
            alternating
            buttons-pagination
            :search-field="searchFields"
            :search-value="search"
            v-bind="$attrs"
        >
            <template
                v-for="(_, name) in $slots"
                #[name]="slotData"
                :key="name"
            >
                <slot :name="name" v-bind="slotData" />
            </template>
        </EasyDataTable>
    </div>
</template>
