<script setup>
import { isArray } from "@vue/shared";
import { computed } from "vue";
import DescriptionItem from "./DescriptionItem.vue";

const props = defineProps({
    label: String,
    value: Object,
});

const files = computed(() =>
    isArray(props.value) ? props.value : [props.value]
);
</script>

<template>
    <DescriptionItem :label="label">
        <ul
            role="list"
            class="divide-y divide-gray-200 rounded-md border border-gray-200"
        >
            <li
                v-for="file in files"
                :key="file"
                class="flex items-center justify-between py-3 pl-3 pr-4 text-sm"
            >
                <div class="flex w-0 flex-1 items-center">
                    <i class="fa fa-file"></i>
                    <span class="ml-2 w-0 flex-1 truncate">{{ file }}</span>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <a
                        href="#"
                        class="font-medium text-blue-600 hover:text-blue-500"
                    >
                        Lihat
                    </a>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <a
                        href="#"
                        class="font-medium text-blue-600 hover:text-blue-500"
                    >
                        Download
                    </a>
                </div>
            </li>
        </ul>
    </DescriptionItem>
</template>
