<script setup>
import { ref, onMounted, computed } from "vue";
import { DateTime } from "luxon";
import { Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/Section/FormSection.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputError from "@/Components/Form/InputError.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ActionMessage from "@/Components/Section/ActionMessage.vue";
import TextArea from "@/Components/Form/TextArea.vue";
import Dropzone from "@/Components/Form/Dropzone.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import { types, types_options } from "@/lib/support";
import { stringify } from "query-string";

const props = defineProps({
    prefix: String,
    form: Object,
    old_file: String,
});
const references = usePage().props.value.references;
const petugases = usePage().props.value.petugases;
const files = ref([]);

const emit = defineEmits([
    "submit",
    "change:file",
    "change:nomor",
    "change:generated",
]);
const onChangeFile = (v) => {
    const f = v.length > 0 ? v[0] : null;
    files.value = f ? [f] : [];
    emit("change:file", f?.file);
};
const genNomor = () => {
    if (props.prefix === "edit") return;
    const label = props.form.type.match(/^(\w+)_panwas$/) ? "K.JI" : "JI";
    const from_date = DateTime.fromFormat(props.form.tanggal, "yyyy-MM-dd");
    const mY = (from_date.isValid ? from_date : DateTime.now()).toFormat(
        "MM/yyyy"
    );
    emit("change:nomor", `${props.form.urut}/KP.01/${label}-20.10/${mY}`);
};
const setProgress = (progress) => {
    if (files.value.length > 0) files.value[0].progress = progress;
};

const query = computed(() => ({
    doc: stringify({ path: props.form.generated_doc }),
    pdf: stringify({ path: props.form.generated_pdf }),
}));
const form_gen = useForm({
    param: "{}",
    from: props.prefix === "edit" ? "edit" : "add",
});
const genDocument = () => {
    form_gen.param = JSON.stringify(props.form.data());
    form_gen.post(route("surat.keluar.generate"), {
        preserveScroll: true,
        onSuccess: (res) => {
            const { generated_doc: doc, generated_pdf: pdf } =
                res.props.jetstream.flash;
            emit("change:generated", { doc, pdf });
        },
    });
};

onMounted(() => {
    if (props.old_file) files.value = [{ file: { name: props.old_file } }];
    genNomor();
});
defineExpose({ setProgress });
</script>

<template>
    <FormSection @submitted="emit('submit')">
        <template #title>
            <slot name="title" />
        </template>
        <template #description>
            <slot name="description" />
        </template>
        <template #form>
            <div class="grid col-span-4 grid-cols-8 gap-6">
                <slot />
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="type" value="Tipe" />
                    <SelectInput
                        id="type"
                        v-model="form.type"
                        class="mt-1 block w-full"
                        :options="types_options"
                        @option:selected="genNomor"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel
                        for="surat_masuk_id"
                        value="Berdasarkan Surat Masuk"
                    />
                    <SelectInput
                        id="surat_masuk_id"
                        v-model="form.surat_masuk_id"
                        class="mt-1 block w-full"
                        label="nomor"
                        :clearable="true"
                        :options="references"
                        :reduce="(o) => o.id"
                        @change="genNomor"
                        placeholder="Surat Masuk"
                    >
                        <template #option="{ nomor, perihal }">
                            {{ nomor }} - {{ perihal }}
                        </template>
                    </SelectInput>
                    <InputError
                        :message="form.errors.surat_masuk_id"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="urut" value="Urut" />
                    <TextInput
                        id="urut"
                        v-model="form.urut"
                        type="text"
                        class="mt-1 block w-full"
                        @change="genNomor"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="nomor" value="Nomor" />
                    <TextInput
                        id="nomor"
                        v-model="form.nomor"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.nomor" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="tanggal" value="Tanggal Keluar" />
                    <TextInput
                        id="tanggal"
                        v-model="form.tanggal"
                        type="date"
                        class="mt-1 block w-full"
                        @change="genNomor"
                    />
                    <InputError :message="form.errors.tanggal" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="tujuan" value="Tujuan Surat" />
                    <TextInput
                        id="tujuan"
                        v-model="form.tujuan"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.tujuan" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="alamat" value="Alamat" />
                    <TextInput
                        id="alamat"
                        v-model="form.alamat"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.alamat" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="perihal" value="Perihal" />
                    <TextInput
                        id="perihal"
                        v-model="form.perihal"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.perihal" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="tempat" value="Tempat" />
                    <TextInput
                        id="tempat"
                        v-model="form.tempat"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.tempat" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="petugases_id" value="Petugas" />
                    <SelectInput
                        multiple
                        id="petugases_id"
                        v-model="form.petugases_id"
                        class="mt-1 block w-full"
                        label="nama"
                        :options="petugases"
                        :reduce="(o) => o.id"
                        placeholder="Petugas"
                    >
                        <template #option="{ nama, jabatan }">
                            {{ nama }} - {{ jabatan }}
                        </template>
                    </SelectInput>
                    <InputError
                        :message="form.errors.petugases_id"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel
                        for="tanggal_dinas_start"
                        value="Tanggal Dinas Start"
                    />
                    <TextInput
                        id="tanggal_dinas_start"
                        v-model="form.tanggal_dinas_start"
                        type="date"
                        class="mt-1 block w-full"
                        @change="genNomor"
                    />
                    <InputError
                        :message="form.errors.tanggal_dinas_start"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel
                        for="tanggal_dinas_end"
                        value="Tanggal Dinas End"
                    />
                    <TextInput
                        id="tanggal_dinas_end"
                        v-model="form.tanggal_dinas_end"
                        type="date"
                        class="mt-1 block w-full"
                        @change="genNomor"
                    />
                    <InputError
                        :message="form.errors.tanggal_dinas_end"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="keterangan" value="Keterangan" />
                    <TextArea
                        id="keterangan"
                        v-model="form.keterangan"
                        type="text"
                        class="mt-1 block w-full"
                        rows="5"
                    />
                    <InputError
                        :message="form.errors.keterangan"
                        class="mt-2"
                    />
                </div>
                <div class="col-span-8">
                    <InputLabel for="doc" value="File" />
                    <Dropzone
                        accept=".docx, .doc, .pdf"
                        v-model="files"
                        @change="onChangeFile"
                    />
                    <InputError :message="form.errors.doc" class="mt-2" />
                </div>
                <div class="col-span-8">
                    <InputLabel value="Dokumen" />
                    <div
                        class="bg-slate-50 rounded-md flex flex-col gap-4 border border-blue-300 ease-linear transition-all duration-150"
                    >
                        <div class="p-6">
                            <span class="block text-xl text-slate-600">
                                Generate Surat Keluar
                            </span>
                            <span
                                class="block text-base mb-2 mt-1 text-slate-400"
                            >
                                {{ types[form.type] || "" }}
                            </span>
                            <div class="flex justify-between gap-4">
                                <div
                                    class="flex flex-shrink items-center gap-2"
                                >
                                    <button
                                        type="button"
                                        class="mb-2 inline-flex rounded-3xl border border-blue-300 py-1 px-5 text-sm font-medium text-slate-800 bg-blue-200 hover:bg-blue-500 hover:text-white"
                                        @click="genDocument"
                                    >
                                        Generate
                                    </button>
                                    <ActionMessage
                                        v-if="!form_gen.hasErrors"
                                        :on="form_gen.recentlySuccessful"
                                        class="mr-3"
                                    >
                                        Berhasil.
                                    </ActionMessage>
                                    <InputError
                                        v-else
                                        v-for="err in form_gen.errors"
                                        :key="`${err}`"
                                        :message="`${['err']}`"
                                        class="mt-2"
                                    />
                                </div>
                                <div
                                    v-if="
                                        form_gen.wasSuccessful &&
                                        (form.generated_pdf ||
                                            form.generated_doc)
                                    "
                                    class="flex flex-shrink gap-2"
                                >
                                    <a
                                        v-if="form.generated_pdf"
                                        :href="`${route('file.view')}?${
                                            query.pdf
                                        }`"
                                        target="_blank"
                                        class="mb-2 inline-flex rounded-3xl border border-indigo-300 py-1 px-5 text-sm font-medium text-slate-800 bg-indigo-200 hover:bg-indigo-500 hover:text-white"
                                    >
                                        Lihat
                                    </a>
                                    <a
                                        v-if="form.generated_pdf"
                                        :href="`${route('file.download')}?${
                                            query.pdf
                                        }`"
                                        target="_blank"
                                        class="mb-2 inline-flex rounded-3xl border border-sky-300 py-1 px-5 text-sm font-medium text-slate-800 bg-sky-200 hover:bg-sky-500 hover:text-white"
                                    >
                                        Unduh [.pdf]
                                    </a>
                                    <a
                                        v-if="form.generated_doc"
                                        :href="`${route('file.download')}?${
                                            query.doc
                                        }`"
                                        target="_blank"
                                        class="mb-2 inline-flex rounded-3xl border border-sky-300 py-1 px-5 text-sm font-medium text-slate-800 bg-sky-200 hover:bg-sky-500 hover:text-white"
                                    >
                                        Unduh [.docx]
                                    </a>
                                    <button
                                        type="button"
                                        class="mb-2 inline-flex rounded-3xl border border-red-300 py-1 px-5 text-sm font-medium text-slate-800 bg-red-200 hover:bg-red-500 hover:text-white"
                                        @click="
                                            $emit('change:generated', {
                                                doc: null,
                                                pdf: null,
                                            })
                                        "
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-between gap-2">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                    Tersimpan.
                </ActionMessage>
                <Link :href="route('surat.keluar.index')" replace>
                    <SecondaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Batal
                    </SecondaryButton>
                </Link>
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Simpan
                </PrimaryButton>
            </div>
        </template>
    </FormSection>
</template>
