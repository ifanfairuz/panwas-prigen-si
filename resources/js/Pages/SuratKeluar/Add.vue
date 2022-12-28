<script setup>
import { ref } from "vue";
import { DateTime } from "luxon";
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    type: String,
    reference: Object,
});

const formComp = ref(null);
const form = useForm({
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
    generated_doc: null,
    generated_pdf: null,
    tanggal_dinas_start: null,
    tanggal_dinas_end: null,
    petugases_id: null,
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
                @change:generated="
                    form.generated_doc = $event.doc;
                    form.generated_pdf = $event.pdf;
                "
                @change:generated_spd="
                    form.generated_doc_spd = $event.doc;
                    form.generated_pdf_spd = $event.pdf;
                "
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
