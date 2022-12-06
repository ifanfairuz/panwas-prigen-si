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
    dari: props.data.dari,
    alamat: props.data.alamat,
    perihal: props.data.perihal,
    tempat: props.data.tempat,
    keterangan: props.data.keterangan,
    doc: null,
});

const submit = () => {
    form.post(route("surat.masuk.update", props.data.id), {
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
    <AppLayout title="Surat Masuk">
        <template #header> Surat Masuk </template>

        <div class="py-8">
            <Form
                ref="formComp"
                :form="form"
                :old_file="data.doc || null"
                @change:file="form.doc = $event"
                @submit="submit"
            >
                <template #title>Ubah Surat Masuk</template>
                <template #description>
                    Edit Surat Masuk yang diterima Panwascam Prigen
                </template>
            </Form>
        </div>
    </AppLayout>
</template>
