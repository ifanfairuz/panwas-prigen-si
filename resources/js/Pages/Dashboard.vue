<script setup>
import ChartSuratMonth from "@/Components/Widget/ChartSuratMonth.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    months_options: Array,
    default_month: Object,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header> Dashboard </template>

        <div class="py-8">
            <div class="grid grid-cols-12 gap-8">
                <ChartSuratMonth
                    :months_options="months_options"
                    :default_month="default_month"
                    class="col-span-12"
                />
            </div>
        </div>
    </AppLayout>
</template>
