<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { DateTime } from "luxon";
import { getGradient } from "@/lib/chart-gradient";
import { types } from "@/lib/support";
import ActionSection from "@/Components/Section/ActionSection.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import RatioCountSurat from "@/Components/Chart/RatioCountSurat.vue";
import RatioJenisSurat from "@/Components/Chart/RatioJenisSurat.vue";
import axios from "axios";
import { stringify } from "query-string";

const props = defineProps({
    months_options: Array,
    default_month: Object,
});

const gradients = [
    getGradient([0, "#1d4ed8"], [1, "#d946ef"]),
    getGradient([0, "#f43f5e"], [1, "#fecaca"]),
    getGradient([0, "#7e22ce"], [1, "#ec4899"]),
];
const dateFromOption = (v) => DateTime.fromFormat(v, "yyyy-MM");
const this_year = DateTime.now().toFormat("yyyy");
const start = ref(
    props.default_month?.start ||
        DateTime.now().minus({ months: 4 }).toFormat("yyyy-MM")
);
const end = ref(props.default_month?.end || DateTime.now().toFormat("yyyy-MM"));
const months_label = computed(() => {
    try {
        const ranges = [start.value, end.value];
        const options = props.months_options.filter(
            ({ code }) => ranges.indexOf(code) > -1
        );
        return options
            .map((o) => o.label)
            .join(" - ")
            .replaceAll(` ${this_year}`, "");
    } catch (error) {
        return "";
    }
});
const chart_labels = computed(() => {
    const s = dateFromOption(start.value).toJSDate();
    const e = dateFromOption(end.value).toJSDate();
    return props.months_options
        .filter((o) => {
            const date = dateFromOption(o.code).toJSDate();
            return date >= s && date <= e;
        })
        .map((o) => o.label.replaceAll(` ${this_year}`, ""));
});
const count_data = ref({
    masuk: [],
    keluar: [],
});
const jenis_keluar_data = ref([]);
const jenis_masuk_data = ref([]);

const getData = () => {
    axios
        .get(
            `${route("dashboard.chart.surat_month")}?${stringify({
                start: start.value,
                end: end.value,
            })}`
        )
        .then((res) => {
            count_data.value = res.data.count;
            jenis_keluar_data.value = Object.keys(res.data.jenis.keluar).map(
                (k, i) => ({
                    label: types[k],
                    backgroundColor: gradients[i],
                    data: res.data.jenis.keluar[k],
                })
            );
            jenis_masuk_data.value = Object.keys(res.data.jenis.masuk).map(
                (k, i) => ({
                    label: k,
                    backgroundColor: gradients[i],
                    data: res.data.jenis.masuk[k],
                })
            );
        });
};

onMounted(() => {
    getData();
});

watch([start, end], () => {
    getData();
});
</script>

<template>
    <ActionSection class="col-span-12">
        <template #title>
            <strong>Surat Masuk & Keluar</strong> {{ months_label }}
        </template>
        <template #description>
            Rasio surat masuk dan surat keluar di Panwaslu Kecamatan Prigen
        </template>
        <template #aside>
            <div class="flex gap-2 items-center min-w-[350px]">
                <SelectInput
                    class="flex-1"
                    :options="months_options"
                    v-model="start"
                />
                <span>-</span>
                <SelectInput
                    class="flex-1"
                    :options="months_options"
                    v-model="end"
                />
            </div>
        </template>
        <template #content>
            <div class="grid grid-flow-row lg:grid-flow-col grid-rows-2 gap-4">
                <div class="row-span-2">
                    <RatioCountSurat
                        :labels="chart_labels"
                        :data="count_data"
                    />
                </div>
                <div class="row-span-2 lg:row-span-1">
                    <RatioJenisSurat
                        :labels="chart_labels"
                        :data="jenis_keluar_data"
                    />
                </div>
                <div class="row-span-2 lg:row-span-1">
                    <RatioJenisSurat
                        :labels="chart_labels"
                        :data="jenis_masuk_data"
                    />
                </div>
            </div>
        </template>
    </ActionSection>
</template>
