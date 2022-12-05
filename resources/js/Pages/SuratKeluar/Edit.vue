<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";
import { DateTime } from "luxon";

const props = defineProps({
    data: Object,
});
const formComp = ref(null);
const form = useForm({
    nomor: props.data.nomor,
    tanggal: DateTime.fromISO(props.data.tanggal).toISODate(),
    tujuan: props.data.tujuan,
    alamat: props.data.alamat,
    perihal: props.data.perihal,
    tempat: props.data.tempat,
    keterangan: props.data.keterangan,
    doc: null,
});

const submit = () => {
    form.put(route("surat.keluar.update", props.data.id), {
        preserveScroll: true,
        onSuccess: () => {
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
    <AppLayout title="Surat Keluar">
        <template #header> Surat Keluar </template>

        <div class="py-8">
            <Form
                ref="formComp"
                :form="form"
                :old_file="data.doc || null"
                @fileChange="form.doc = $event"
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
