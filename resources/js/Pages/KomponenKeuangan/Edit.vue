<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    data: Object,
});

const form = useForm({
    kode: props.data.kode,
    label: props.data.label,
    keterangan: props.data.keterangan,
});

const submit = () => {
    form.post(route("keuangan.komponen.update", props.data.kode), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout title="Komponen Keuangan">
        <template #header> Komponen Keuangan </template>

        <div class="py-8">
            <Form :form="form" @submit="submit">
                <template #title> Edit Komponen Keuangan </template>
            </Form>
        </div>
    </AppLayout>
</template>
