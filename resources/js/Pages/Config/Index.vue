<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

defineProps({
    dropbox: Object,
});
</script>

<template>
    <AppLayout title="Setting">
        <template #header> Setting </template>

        <div class="py-8">
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
        </div>
    </AppLayout>
</template>
