<script setup>
import { ref } from "vue";
import { DateTime } from "luxon";
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const formComp = ref(null);
const form = useForm({
    nomor: "",
    tanggal: DateTime.now().toISODate(),
    dari: "",
    alamat: "",
    perihal: "",
    tempat: "",
    keterangan: "",
    doc: null,
});

const submit = () => {
    form.post(route("surat.masuk.create"), {
        preserveScroll: true,
        onSuccess: () => {
            file.value = null;
            form.reset();
        },
        onProgress: (e) => {
            formComp.value.setProgress(e.percentage);
        },
        onFinish: () => {
            formComp.value.setProgress(100);
        },
    });
};
</script>

<template>
    <AppLayout title="Surat Masuk">
        <template #header> Surat Masuk </template>
        <div class="py-8">
            <Form
                ref="formComp"
                :form="form"
                @fileChange="form.doc = $event"
                @submit="submit"
            >
                <template #title>Tambah Surat Masuk</template>
                <template #description>
                    Tambah Surat Masuk yang diterima Panwascam Prigen
                </template>
            </Form>
        </div>
    </AppLayout>
</template>
