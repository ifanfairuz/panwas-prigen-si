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
    { text: "Nama", value: "name", sortable: true },
    { text: "Email", value: "email", sortable: true },
    { text: "Action", value: "action", sortable: false },
];
const deletion = ref(null);
const form = useForm({});

const tryDelete = (id) => {
    deletion.value = id;
};

const deleteUser = () => {
    form.delete(route("administration.users.delete", deletion.value), {
        errorBag: "deleteUser",
        onSuccess: () => (deletion.value = null),
    });
};
</script>

<template>
    <AppLayout title="Users">
        <template #header> Users </template>

        <div class="py-8">
            <ActionSection>
                <template #title> List Users </template>
                <template #aside>
                    <Link :href="route('administration.users.add')">
                        <PrimaryButton> Add Users </PrimaryButton>
                    </Link>
                </template>
                <template #content>
                    <DataTable
                        :headers="columns"
                        :items="datas"
                        theme-color="#2563eb"
                        alternating
                        buttons-pagination
                        show-index
                    >
                        <template #item-action="{ id }">
                            <div class="flex gap-2">
                                <Link
                                    :href="
                                        route('administration.users.edit', id)
                                    "
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

        <!-- Delete User Confirmation Modal -->
        <ConfirmationModal :show="!!deletion" @close="deletion = null">
            <template #title> Delete User </template>

            <template #content>
                Are you sure you want to delete this user? all of user resources
                and data will be permanently deleted.
            </template>

            <template #footer>
                <SecondaryButton @click="deletion = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteUser"
                >
                    Delete User
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>
