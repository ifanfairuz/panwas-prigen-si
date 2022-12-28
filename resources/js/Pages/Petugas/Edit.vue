<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";
import { DateTime } from "luxon";

const props = defineProps({
    data: Object,
});

const form = useForm({
    nama: props.data.nama,
    jabatan: props.data.jabatan,
    tempat_lahir: props.data.tempat_lahir,
    tanggal_lahir: DateTime.fromISO(props.data.tanggal_lahir).toISODate(),
    alamat: props.data.alamat,
    pendidikan: props.data.pendidikan,
    jk: props.data.jk,
    agama: props.data.agama,
    pangkat: props.data.pangkat,
    tingkat: props.data.tingkat,
    nik: props.data.nik,
    nip: props.data.nip,
    npwp: props.data.npwp,
    no_hp: props.data.no_hp,
    email: props.data.email,
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
            <Form
                :form="form"
                @change:npwp="form.npwp = $event"
                @change:nip="form.nip = $event"
                @submit="submit"
            >
                <template #title> Edit Petugas </template>
            </Form>
        </div>
    </AppLayout>
</template>
