<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const form = useForm({
    nama: "",
    jabatan: "",
});

const submit = () => {
    form.post(route("petugas.create"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout title="Petugas">
        <template #header> Petugas </template>

        <div class="py-8">
            <Form :form="form" @submit="submit">
                <template #title> Add Petugas </template>
            </Form>
        </div>
    </AppLayout>
</template>
