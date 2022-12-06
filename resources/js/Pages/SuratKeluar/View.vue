<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import DescriptionItem from "@/Components/Description/DescriptionItem.vue";
import DescriptionItemFile from "@/Components/Description/DescriptionItemFile.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    data: Object,
});
</script>

<template>
    <AppLayout title="Surat Keluar">
        <template #header> Surat Keluar </template>

        <div class="py-8 max-w-3xl mx-auto">
            <ActionSection>
                <template #title>Detail Surat Keluar</template>
                <template #description>
                    Detail Surat Keluar yang diterima Panwascam Prigen
                </template>
                <template #aside>
                    <div class="h-full flex items-center">
                        <Link :href="route('surat.keluar.index')" replace>
                            <SecondaryButton>
                                <i class="fa fa-times"></i>
                            </SecondaryButton>
                        </Link>
                    </div>
                </template>
                <template #content>
                    <div>
                        <dl>
                            <DescriptionItem label="ID" :value="data.id" />
                            <DescriptionItem label="Tipe" :value="data.type" />
                            <DescriptionItem
                                label="Berdasarkan Surat Masuk"
                                :value="data.surat_masuk?.nomor"
                            />
                            <DescriptionItem
                                label="Nomor"
                                :value="data.nomor"
                            />
                            <DescriptionItem
                                label="Keluar"
                                :value="data.tanggal"
                            />
                            <DescriptionItem
                                label="Tujuan"
                                :value="data.tujuan"
                            />
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
                                label="Petugas"
                                :value="data.petugas?.nama"
                            />
                            <DescriptionItem
                                label="Tanggal Dinas"
                                :value="data.range_dinas"
                            />
                            <DescriptionItem
                                label="Keterangan"
                                :value="data.keterangan"
                            />
                            <DescriptionItemFile
                                label="Dokumen"
                                provider="dropbox"
                                :filename="`${data.tujuan} - ${data.nomor}`"
                                :value="data.doc"
                            />
                        </dl>
                    </div>
                </template>
            </ActionSection>
        </div>
    </AppLayout>
</template>
