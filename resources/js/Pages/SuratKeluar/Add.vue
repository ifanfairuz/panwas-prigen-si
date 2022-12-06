<script setup>
import { ref } from "vue";
import { DateTime } from "luxon";
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    type: String,
    urut: String,
    reference: Object,
});

const formComp = ref(null);
const form = useForm({
    urut: props.urut || "000",
    type: props.type || "tugas_panwas",
    nomor: "",
    tanggal: DateTime.now().toISODate(),
    tujuan: "",
    alamat: "",
    perihal: props.reference?.perihal || "",
    tempat: props.reference?.tempat || "",
    keterangan: props.reference?.id
        ? `Berdasarkan Surat Masuk dari ${props.reference.dari}\n` +
          `dengan nomor ${props.reference.nomor}\n` +
          `perihal ${props.reference.perihal}`
        : "",
    surat_masuk_id: props.reference?.id || null,
    doc: null,
    generated: null,
    tanggal_dinas_start: null,
    tanggal_dinas_end: null,
    petugas_id: null,
});

const submit = () => {
    form.post(route("surat.keluar.create"), {
        preserveScroll: true,
        onSuccess: () => {
            file.value = null;
            form.reset();
        },
        onProgress: (e) => {
            formComp.value.setProgress(e.percentage);
        },
    });
};
</script>

<template>
    <AppLayout title="Surat Keluar">
        <template #header> Surat Keluar </template>
        <div class="py-8">
            <Form
                ref="formComp"
                :form="form"
                @change:file="form.doc = $event"
                @change:nomor="form.nomor = $event"
                @change:generated="form.generated = $event"
                @submit="submit"
            >
                <template #title>Tambah Surat Keluar</template>
                <template #description>
                    Tambah Surat Keluar yang diterima Panwascam Prigen
                </template>
            </Form>
        </div>
    </AppLayout>
</template>
