<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    data: Object,
});

const form = useForm({
    nama: props.data.nama,
    jabatan: props.data.jabatan,
});

const submit = () => {
    form.put(route("petugas.update", props.data.id), {
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
                <template #title> Edit Petugas </template>
            </Form>
        </div>
    </AppLayout>
</template>
