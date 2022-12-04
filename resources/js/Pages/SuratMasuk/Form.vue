<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/Section/FormSection.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputError from "@/Components/Form/InputError.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ActionMessage from "@/Components/Section/ActionMessage.vue";
import TextArea from "@/Components/Form/TextArea.vue";
import Dropzone from "@/Components/Form/Dropzone.vue";

const props = defineProps({
    form: Object,
    old_file: String,
});
const files = ref([]);

const emit = defineEmits(["submit", "fileChange"]);
const onChangeFile = (v) => {
    const f = v.length > 0 ? v[0] : null;
    files.value = f ? [f] : [];
    emit("fileChange", f?.file);
};
const setProgress = (progress) => {
    if (files.value.length > 0) files.value[0].progress = progress;
};
defineExpose({ setProgress });
onMounted(() => {
    if (props.old_file) files.value = [{ file: { name: props.old_file } }];
});
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
                    <InputLabel for="tanggal" value="Tanggal Masuk" />
                    <TextInput
                        id="tanggal"
                        v-model="form.tanggal"
                        type="date"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.tanggal" class="mt-2" />
                </div>
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="dari" value="Surat Dari" />
                    <TextInput
                        id="dari"
                        v-model="form.dari"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.dari" class="mt-2" />
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
                <div class="col-span-8 sm:col-span-4">
                    <InputLabel for="doc" value="File" />
                    <Dropzone
                        accept=".docx, .doc, .pdf"
                        v-model="files"
                        @onChange="onChangeFile"
                    />
                    <InputError :message="form.errors.doc" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-between gap-2">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                    Tersimpan.
                </ActionMessage>
                <Link :href="route('surat.masuk.index')" replace>
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
