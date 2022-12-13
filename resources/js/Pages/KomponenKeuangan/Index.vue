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
    { text: "Kode", value: "kode", sortable: true },
    { text: "Nama", value: "label", sortable: true },
    { text: "Action", value: "action", sortable: false, searchable: false },
];
const deletion = ref(null);
const form = useForm({});

const tryDelete = (id) => {
    deletion.value = id;
};

const deleteKomponenKeuangan = () => {
    form.delete(route("keuangan.komponen.delete", deletion.value), {
        onSuccess: () => (deletion.value = null),
    });
};
</script>

<template>
    <AppLayout title="Komponen Keuangan">
        <template #header> Komponen Keuangan </template>

        <div class="py-8">
            <ActionSection>
                <template #title> List Komponen Keuangan </template>
                <template #aside>
                    <Link :href="route('keuangan.komponen.add')">
                        <PrimaryButton> Add Komponen Keuangan </PrimaryButton>
                    </Link>
                </template>
                <template #content>
                    <DataTable :headers="columns" :items="datas">
                        <template #item-action="{ kode }">
                            <div class="flex gap-2">
                                <Link
                                    :href="
                                        route('keuangan.komponen.edit', kode)
                                    "
                                    class="hover:text-blue-500"
                                >
                                    <i class="fa fa-pencil"></i>
                                </Link>
                                <button
                                    class="hover:text-red-500"
                                    @click="tryDelete(kode)"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </DataTable>
                </template>
            </ActionSection>
        </div>

        <!-- Delete Komponen Keuangan Confirmation Modal -->
        <ConfirmationModal :show="!!deletion" @close="deletion = null">
            <template #title> Delete Komponen Keuangan </template>

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
                    @click="deleteKomponenKeuangan"
                >
                    Delete Komponen Keuangan
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
