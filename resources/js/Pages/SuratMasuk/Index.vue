<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import DataTable from "@/Components/Table/DataTable.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import ConfirmationModal from "@/Components/Modal/ConfirmationModal.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import DangerButton from "@/Components/Button/DangerButton.vue";
import { ref } from "vue";

defineProps({
    datas: Array,
});

const columns = [
    { text: "ID", value: "id", sortable: true },
    { text: "Tanggal", value: "tanggal", sortable: true },
    { text: "Nomor", value: "nomor", sortable: true },
    { text: "Dari", value: "dari", sortable: true },
    { text: "Perihal", value: "perihal", sortable: true },
    { text: "Action", value: "action", sortable: false, searchable: false },
];
const deletion = ref(null);
const form = useForm({});

const tryDelete = (id) => {
    deletion.value = id;
};

const deleteData = () => {
    form.delete(route("surat.masuk.delete", deletion.value), {
        errorBag: "deleteData",
        onSuccess: () => (deletion.value = null),
    });
};
</script>

<template>
    <AppLayout title="Surat Masuk">
        <template #header> Surat Masuk </template>

        <div class="py-8">
            <ActionSection>
                <template #title> Surat Masuk </template>
                <template #description>
                    Data Surat Masuk yang diterima Panwascam Prigen
                </template>
                <template #aside>
                    <Link :href="route('surat.masuk.add')">
                        <PrimaryButton> Tambah Surat Masuk </PrimaryButton>
                    </Link>
                </template>
                <template #content>
                    <DataTable :headers="columns" :items="datas">
                        <template #item-action="{ id }">
                            <div class="flex gap-2">
                                <Link
                                    :href="route('surat.masuk.view', id)"
                                    class="hover:text-blue-500 px-1"
                                >
                                    <i class="fa fa-eye"></i>
                                </Link>
                                <Link
                                    :href="route('surat.masuk.edit', id)"
                                    class="hover:text-blue-500 px-1"
                                >
                                    <i class="fa fa-pencil"></i>
                                </Link>
                                <button
                                    class="hover:text-red-500 px-1"
                                    @click="tryDelete(id)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </DataTable>
                </template>
            </ActionSection>
        </div>

        <!-- Confirmation Modal -->
        <ConfirmationModal :show="!!deletion" @close="deletion = null">
            <template #title> Hapus Surat Masuk </template>

            <template #content>
                Apakah anda yakin ?, data yang telah dihapus tidak dapat
                dikembalikan.
            </template>

            <template #footer>
                <SecondaryButton @click="deletion = null">
                    Batal
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteData"
                >
                    Ya, Hapus
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
