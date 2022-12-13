<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const form = useForm({
    kode: "",
    label: "",
    keterangan: "",
});

const submit = () => {
    form.post(route("keuangan.komponen.create"), {
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
                <template #title> Add Komponen Keuangan </template>
            </Form>
        </div>
    </AppLayout>
</template>
