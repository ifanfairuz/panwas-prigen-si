<script setup>
import "@/lib/chart";
import { Bar } from "vue-chartjs";
import { computed } from "vue";

const props = defineProps({
    labels: Array,
    data: Array,
});

const chartData = computed(() => ({
    labels: props.labels,
    datasets: props.data,
}));
</script>

<template>
    <Bar :data="chartData" />
</template>
