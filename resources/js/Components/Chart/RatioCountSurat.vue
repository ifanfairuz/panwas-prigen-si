<script setup>
import "@/lib/chart";
import { Line } from "vue-chartjs";
import { getGradient } from "@/lib/chart-gradient";
import { computed } from "vue";

const props = defineProps({
    labels: Array,
    data: Object,
});

const chartPlugins = [
    {
        beforeDatasetsDraw: (chart, options, el) => {
            chart.ctx.globalCompositeOperation = "multiply";
        },
        afterDatasetsDraw: (chart, options) => {
            chart.ctx.globalCompositeOperation = "source-over";
        },
    },
];
const chartData = computed(() => ({
    labels: props.labels,
    datasets: [
        {
            label: "Surat Masuk",
            backgroundColor: getGradient([0, "#FF55B8"], [1, "#FF8787"]),
            data: props.data.masuk ?? [],
        },
        {
            label: "Surat Keluar",
            backgroundColor: getGradient([0, "#5555FF"], [1, "#9787FF"]),
            data: props.data.keluar ?? [],
        },
    ],
}));
</script>

<template>
    <Line :data="chartData" :plugins="chartPlugins" />
</template>
