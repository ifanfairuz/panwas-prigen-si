<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import FormSection from "@/Components/Section/FormSection.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import TextArea from "@/Components/Form/TextArea.vue";
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
                <InputLabel for="kode" value="Kode" />
                <TextInput
                    id="kode"
                    v-model="form.kode"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="name"
                />
                <InputError :message="form.errors.kode" class="mt-2" />
            </div>

            <div class="col-span-8 sm:col-span-4">
                <InputLabel for="label" value="Label" />
                <TextInput
                    id="label"
                    v-model="form.label"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.label" class="mt-2" />
            </div>

            <div class="col-span-8 sm:col-span-4">
                <InputLabel for="keterangan" value="Keterangan" />
                <TextArea
                    id="keterangan"
                    v-model="form.keterangan"
                    type="text"
                    class="mt-1 block w-full"
                    rows="3"
                />
                <InputError :message="form.errors.keterangan" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <div class="flex items-center justify-between gap-2">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </ActionMessage>
                <Link :href="route('keuangan.komponen.index')" replace>
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
