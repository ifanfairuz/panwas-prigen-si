<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const form = useForm({
    nama: "",
    jabatan: "",
    tempat_lahir: "",
    tanggal_lahir: "",
    alamat: "",
    pendidikan: "",
    jk: "",
    agama: "",
    pangkat: "",
    tingkat: "",
    nik: "",
    nip: "",
    npwp: "",
    no_hp: "",
    email: "",
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
            <Form
                :form="form"
                @change:npwp="form.npwp = $event"
                @change:nip="form.nip = $event"
                @submit="submit"
            >
                <template #title> Add Petugas </template>
            </Form>
        </div>
    </AppLayout>
</template>
