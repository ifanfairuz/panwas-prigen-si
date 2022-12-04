<script setup>
import { ref, computed, useAttrs } from "vue";

const { headers } = useAttrs();
const search = ref("");
const searchFields = computed(() =>
    headers
        .filter((h) => !("searchable" in h) || h.searchable)
        .map((h) => h.value)
);
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2 justify-end">
            <div class="flex items-center gap-4">
                <label class="text-slate-700">Search:</label>
                <div
                    class="flex items-center rounded-3xl overflow-hidden border border-blue-300 placeholder-slate-400 text-slate-600 bg-gray-50 rounded text-sm shadow-md focus:outline-none w-[300px]"
                >
                    <span
                        class="leading-snug font-normal text-center text-slate-300 bg-transparent rounded text-base pl-3"
                    >
                        <i class="fas fa-search"></i>
                    </span>
                    <input
                        class="border-0 focus:border-0 focus:ring-none focus:-ml-7 -mr-8 rounded-3xl pl-2 pr-2 py-2 placeholder-slate-400 text-slate-600 bg-gray-50 rounded text-sm outline-none w-full ease-linear transition-all duration-100"
                        v-model="search"
                        placeholder="Cari..."
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
