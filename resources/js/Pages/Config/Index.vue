<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import Dropzone from "@/Components/Form/Dropzone.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import ActionMessage from "@/Components/Section/ActionMessage.vue";
import { types } from "@/lib/support";
import InputError from "@/Components/Form/InputError.vue";
import DescriptionItemFile from "@/Components/Description/DescriptionItemFile.vue";

defineProps({
    dropbox: Object,
    templates: Object,
});

const form = useForm({
    file: null,
    type: "",
});

const uploadFile = (type, files) => {
    if (files.length == 0) return;
    form.file = files[0].file;
    form.type = type;
    form.post(route("administration.config.template.upload"));
};
</script>

<template>
    <AppLayout title="Setting">
        <template #header> Setting </template>

        <div class="py-8 flex flex-col gap-4">
            <ActionSection>
                <template #title> Dropbox </template>
                <template #description> Dropbox access setting </template>
                <template #content>
                    <div class="pt-2">
                        <div
                            class="flex items-center justify-between gap-4 mb-2"
                        >
                            <p class="text-base">Dropbox Token</p>
                            <a
                                :href="
                                    route(
                                        'administration.config.dropbox.getaccess'
                                    )
                                "
                            >
                                <PrimaryButton>Connect</PrimaryButton>
                            </a>
                        </div>
                        <JsonViewer
                            :value="dropbox.token"
                            copyable
                            theme="jv-dark bg-slate-100"
                        />
                    </div>
                </template>
            </ActionSection>
            <ActionSection>
                <template #title> Templates </template>
                <template #description> Template docx </template>
                <template #aside>
                    <InputError
                        v-if="form.hasErrors"
                        :message="form.errors.file || form.errors.type"
                    />
                    <ActionMessage :on="form.recentlySuccessful">
                        <span class="text-emerald-500">
                            Uploaded {{ types[form.type] }}.
                        </span>
                    </ActionMessage>
                </template>
                <template #content>
                    <div class="pt-2 flex flex-col gap-2">
                        <div v-for="(label, t) in types" :key="t">
                            <div
                                class="flex items-center justify-between gap-4 mb-2"
                            >
                                <p class="text-base">{{ label }}</p>
                            </div>
                            <DescriptionItemFile
                                v-if="templates[t]"
                                class="px-0 sm:px-0 py-0 pb-2"
                                provider="local"
                                :value="templates[t]"
                            />
                            <Dropzone
                                accept=".docx"
                                @change="uploadFile(t, $event)"
                            />
                        </div>
                        <div>
                            <div
                                class="flex items-center justify-between gap-4 mb-2"
                            >
                                <p class="text-base">Surat Perjalanan Dinas</p>
                            </div>
                            <DescriptionItemFile
                                v-if="templates['spd']"
                                class="px-0 sm:px-0 py-0 pb-2"
                                provider="local"
                                :value="templates['spd']"
                            />
                            <Dropzone
                                accept=".docx"
                                @change="uploadFile('spd', $event)"
                            />
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>
    </AppLayout>
</template>
