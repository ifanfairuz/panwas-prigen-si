<script setup>
import { ref } from "vue";
import { DateTime } from "luxon";
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    data: Object,
});
const formComp = ref(null);
const form = useForm({
    urut: props.data.nomor.substring(0, 3),
    type: props.data.type,
    nomor: props.data.nomor,
    tanggal: DateTime.fromISO(props.data.tanggal).toISODate(),
    tujuan: props.data.tujuan,
    alamat: props.data.alamat,
    perihal: props.data.perihal,
    tempat: props.data.tempat,
    keterangan: props.data.keterangan,
    surat_masuk_id: props.data.surat_masuk_id || null,
    doc: null,
    generated: null,
    tanggal_dinas_start: props.data.tanggal_dinas_start,
    tanggal_dinas_end: props.data.tanggal_dinas_end,
    petugases_id: props.data.petugases.map((p) => p.id) || null,
});

const submit = () => {
    form.post(route("surat.keluar.update", props.data.id), {
        preserveScroll: true,
        onSuccess: () => {
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
                prefix="edit"
                :form="form"
                :old_file="data.doc || null"
                @change:file="form.doc = $event"
                @change:nomor="form.nomor = $event"
                @change:generated="form.generated = $event"
                @submit="submit"
            >
                <template #title>Ubah Surat Keluar</template>
                <template #description>
                    Edit Surat Keluar yang diterima Panwascam Prigen
                </template>
            </Form>
        </div>
    </AppLayout>
</template>
