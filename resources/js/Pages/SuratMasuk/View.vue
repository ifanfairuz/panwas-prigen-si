<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import DescriptionItem from "@/Components/DescriptionItem.vue";
import DescriptionItemFile from "@/Components/DescriptionItemFile.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    data: Object,
});
</script>

<template>
    <AppLayout title="Surat Masuk">
        <template #header> Surat Masuk </template>

        <div class="py-8 max-w-3xl mx-auto">
            <ActionSection>
                <template #title>Detail Surat Masuk</template>
                <template #description>
                    Detail Surat Masuk yang diterima Panwascam Prigen
                </template>
                <template #aside>
                    <div class="h-full flex items-center">
                        <Link :href="route('surat.masuk.index')" replace>
                            <SecondaryButton>
                                <i class="fa fa-times"></i>
                            </SecondaryButton>
                        </Link>
                    </div>
                </template>
                <template #content>
                    <div>
                        <dl>
                            <DescriptionItem
                                label="Nomor"
                                :value="data.nomor"
                            />
                            <DescriptionItem
                                label="Masuk"
                                :value="data.tanggal"
                            />
                            <DescriptionItem label="Dari" :value="data.dari" />
                            <DescriptionItem
                                label="Alamat"
                                :value="data.alamat"
                            />
                            <DescriptionItem
                                label="Perihal"
                                :value="data.perihal"
                            />
                            <DescriptionItem
                                label="Tempat"
                                :value="data.tempat"
                            />
                            <DescriptionItem
                                label="Keterangan"
                                :value="data.keterangan"
                            />
                            <DescriptionItemFile
                                label="Dokumen"
                                :value="data.doc"
                            />
                        </dl>
                    </div>
                </template>
            </ActionSection>
        </div>
    </AppLayout>
</template>
