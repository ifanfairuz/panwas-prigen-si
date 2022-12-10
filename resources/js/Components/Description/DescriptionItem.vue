<script setup>
import { computed } from "vue";

const props = defineProps({
    label: String,
    value: undefined,
    divider: String,
    keyExtractor: Function,
    valueExtractor: Function,
});
const isArray = computed(() => props.value instanceof Array);
</script>

<template>
    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
        <dt v-if="label" class="text-sm font-medium text-gray-500">
            {{ label }}
        </dt>
        <dd
            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 font-semibold"
        >
            <slot>
                <template v-if="!isArray">
                    {{ value || "N/A" }}
                </template>
                <template v-else>
                    <slot
                        v-for="(v, i) in value"
                        :key="props.keyExtractor ? props.keyExtractor(v) : i"
                        name="item"
                        :item="v"
                        :next="value[i + 1]"
                    >
                        {{ props.valueExtractor ? props.valueExtractor(v) : v
                        }}{{ value[i + 1] ? props.divider || ", " : "" }}
                    </slot>
                </template>
            </slot>
        </dd>
    </div>
</template>
