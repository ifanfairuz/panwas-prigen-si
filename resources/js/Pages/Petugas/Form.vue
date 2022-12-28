<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/Section/FormSection.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputError from "@/Components/Form/InputError.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ActionMessage from "@/Components/Section/ActionMessage.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import formatPatern from "format-string-by-pattern";

const props = defineProps({
    form: Object,
});
const pangkat_options = ["PNS", "Non PNS"];
const jk_options = [
    { code: "L", label: "Laki-Laki" },
    { code: "P", label: "Perempuan" },
];

const emit = defineEmits(["submit", "change:npwp", "change:nip"]);

const unformatNPWP = () => {
    emit("change:npwp", props.form.npwp.replace(/[^0-9]+/g, ""));
};
const formatNPWP = () => {
    emit(
        "change:npwp",
        formatPatern(
            "99.999.999.9-999.999",
            props.form.npwp.replace(/[^0-9]+/g, "")
        )
    );
};
const unformatNIP = () => {
    emit("change:nip", props.form.nip.replace(/[^0-9]+/g, ""));
};
const formatNIP = () => {
    emit(
        "change:nip",
        formatPatern(
            "99999999 999999 9 999",
            props.form.nip.replace(/[^0-9]+/g, "")
        )
    );
};
</script>

<template>
    <FormSection @submitted="emit('submit')">
        <template #title>
            <slot name="title" />
        </template>
        <template #form>
            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="nik" value="NIK" />
                <TextInput
                    id="nik"
                    v-model="form.nik"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.nik" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="npwp" value="NPWP" />
                <TextInput
                    id="npwp"
                    v-model="form.npwp"
                    type="text"
                    class="mt-1 block w-full"
                    @focusin="unformatNPWP"
                    @focusout="formatNPWP"
                />
                <InputError :message="form.errors.npwp" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="nama" value="Nama" />
                <TextInput
                    id="nama"
                    v-model="form.nama"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <InputError :message="form.errors.nama" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="nip" value="NIP" />
                <TextInput
                    id="nip"
                    v-model="form.nip"
                    type="text"
                    class="mt-1 block w-full"
                    @focusin="unformatNIP"
                    @focusout="formatNIP"
                />
                <InputError :message="form.errors.nip" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="tempat_lahir" value="Tempat Lahir" />
                <TextInput
                    id="tempat_lahir"
                    v-model="form.tempat_lahir"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.tempat_lahir" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="tanggal_lahir" value="Tanggal Lahir" />
                <TextInput
                    id="tanggal_lahir"
                    v-model="form.tanggal_lahir"
                    type="date"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.tanggal_lahir" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="alamat" value="Alamat" />
                <TextInput
                    id="alamat"
                    v-model="form.alamat"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.alamat" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="pendidikan" value="Pendidikan" />
                <TextInput
                    id="pendidikan"
                    v-model="form.pendidikan"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.pendidikan" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="jk" value="Jenis Kelamin" />
                <SelectInput
                    id="jk"
                    v-model="form.jk"
                    class="mt-1 block w-full"
                    :options="jk_options"
                />
                <InputError :message="form.errors.jk" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="agama" value="Agama" />
                <TextInput
                    id="agama"
                    v-model="form.agama"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.agama" class="mt-2" />
            </div>

            <div class="col-span-2 lg:col-span-1">
                <InputLabel for="pangkat" value="Golongan" />
                <SelectInput
                    id="pangkat"
                    v-model="form.pangkat"
                    :options="pangkat_options"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.pangkat" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="jabatan" value="Jabatan" />
                <TextInput
                    id="jabatan"
                    v-model="form.jabatan"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.jabatan" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="tingkat" value="Tingkat" />
                <TextInput
                    id="tingkat"
                    v-model="form.tingkat"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.tingkat" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="no_hp" value="No HP" />
                <TextInput
                    id="no_hp"
                    v-model="form.no_hp"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.no_hp" class="mt-2" />
            </div>

            <div class="col-span-4 lg:col-span-2">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email"
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-between gap-2">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </ActionMessage>
                <Link :href="route('petugas.index')" replace>
                    <SecondaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Cancel
                    </SecondaryButton>
                </Link>
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </PrimaryButton>
            </div>
        </template>
    </FormSection>
</template>
