<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/Section/FormSection.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputError from "@/Components/Form/InputError.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import ActionMessage from "@/Components/Section/ActionMessage.vue";

const props = defineProps({
    form: Object,
});

const emit = defineEmits(["submit"]);
</script>

<template>
    <FormSection @submitted="emit('submit')">
        <template #title>
            <slot name="title" />
        </template>
        <template #form>
            <div class="col-span-8 sm:col-span-4">
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

            <div class="col-span-8 sm:col-span-4">
                <InputLabel for="jabatan" value="Jabatan" />
                <TextInput
                    id="jabatan"
                    v-model="form.jabatan"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.jabatan" class="mt-2" />
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
