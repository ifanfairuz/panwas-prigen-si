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
    { text: "Nama", value: "nama", sortable: true },
    { text: "Jabatan", value: "jabatan", sortable: true },
    { text: "Tingkat", value: "tingkat", sortable: true },
    { text: "Golongan", value: "pangkat", sortable: true },
    { text: "Action", value: "action", sortable: false, searchable: false },
];
const deletion = ref(null);
const form = useForm({});

const tryDelete = (id) => {
    deletion.value = id;
};

const deletePetugas = () => {
    form.delete(route("petugas.delete", deletion.value), {
        onSuccess: () => (deletion.value = null),
    });
};
</script>

<template>
    <AppLayout title="Petugas">
        <template #header> Petugas </template>

        <div class="py-8">
            <ActionSection>
                <template #title> List Petugas </template>
                <template #aside>
                    <Link :href="route('petugas.add')">
                        <PrimaryButton> Add Petugas </PrimaryButton>
                    </Link>
                </template>
                <template #content>
                    <DataTable :headers="columns" :items="datas">
                        <template #item-action="{ id }">
                            <div class="flex gap-2">
                                <Link
                                    :href="route('petugas.view', id)"
                                    class="hover:text-blue-500 px-1"
                                >
                                    <i class="fa fa-eye"></i>
                                </Link>
                                <Link
                                    :href="route('petugas.edit', id)"
                                    class="hover:text-blue-500"
                                >
                                    <i class="fa fa-pencil"></i>
                                </Link>
                                <button
                                    class="hover:text-red-500"
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

        <!-- Delete Petugas Confirmation Modal -->
        <ConfirmationModal :show="!!deletion" @close="deletion = null">
            <template #title> Delete Petugas </template>

            <template #content>
                Apakah anda yakin ?, data yang telah dihapus tidak dapat
                dikembalikan.
            </template>

            <template #footer>
                <SecondaryButton @click="deletion = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deletePetugas"
                >
                    Delete Petugas
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
