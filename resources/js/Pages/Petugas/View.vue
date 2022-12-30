<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import DescriptionItem from "@/Components/Description/DescriptionItem.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    data: Object,
});
</script>

<template>
    <AppLayout title="Petugas">
        <template #header> Petugas </template>

        <div class="py-8 max-w-3xl mx-auto">
            <ActionSection>
                <template #title>Detail Petugas</template>
                <template #description>
                    Detail Petugas Panwaslu Kecamatan Prigen
                </template>
                <template #aside>
                    <div class="h-full flex items-center">
                        <Link :href="route('petugas.index')" replace>
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
                            <DescriptionItem label="NIK" :value="data.nik" />
                            <DescriptionItem label="NPWP" :value="data.npwp" />
                            <DescriptionItem label="Nama" :value="data.nama" />
                            <DescriptionItem label="NIP" :value="data.nip" />
                            <DescriptionItem
                                label="Tempat Lahir"
                                :value="data.tempat_lahir"
                            />
                            <DescriptionItem
                                label="Tanggal Lahir"
                                :value="data.tanggal_lahir_formated"
                            />
                            <DescriptionItem
                                label="Alamat"
                                :value="data.alamat"
                            />
                            <DescriptionItem
                                label="Pendidikan"
                                :value="data.pendidikan"
                            />
                            <DescriptionItem
                                label="Jenis Kelamin"
                                :value="data.jk"
                            />
                            <DescriptionItem
                                label="Agama"
                                :value="data.agama"
                            />
                            <DescriptionItem
                                label="Golongan"
                                :value="data.pangkat"
                            />
                            <DescriptionItem
                                label="Jabatan"
                                :value="data.jabatan"
                            />
                            <DescriptionItem
                                label="Tingkat"
                                :value="data.tingkat"
                            />
                            <DescriptionItem
                                label="No HP"
                                :value="data.no_hp"
                            />
                            <DescriptionItem
                                label="Email"
                                :value="data.email"
                            />
                        </dl>
                    </div>
                </template>
            </ActionSection>
        </div>
    </AppLayout>
</template>
