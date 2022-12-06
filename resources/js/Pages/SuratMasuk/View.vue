<script setup>
import { stringify } from "query-string";
import AppLayout from "@/Layouts/AppLayout.vue";
import ActionSection from "@/Components/Section/ActionSection.vue";
import DescriptionItem from "@/Components/Description/DescriptionItem.vue";
import DescriptionItemFile from "@/Components/Description/DescriptionItemFile.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const props = defineProps({
    data: Object,
});

const action = (url, type) => {
    return `${url}?${stringify({ refid: props.data?.id, type })}`;
};
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
                                provider="dropbox"
                                :filename="`${data.dari} - ${data.nomor}`"
                                :value="data.doc"
                            />
                            <DescriptionItem label="Aksi">
                                <div class="grid gap-2">
                                    <Link
                                        :href="
                                            route('surat.masuk.edit', data.id)
                                        "
                                    >
                                        <PrimaryButton>
                                            <i class="fa fa-pencil mr-1"></i>
                                            Edit
                                        </PrimaryButton>
                                    </Link>
                                    <Link
                                        :href="
                                            action(
                                                route('surat.keluar.add'),
                                                'tugas_panwas'
                                            )
                                        "
                                    >
                                        <PrimaryButton>
                                            <i
                                                class="fa fa-envelope-circle-check mr-1"
                                            ></i>
                                            Gen Surat Tugas
                                        </PrimaryButton>
                                    </Link>
                                    <Link
                                        :href="
                                            action(
                                                route('surat.keluar.add'),
                                                'keluar_panwas'
                                            )
                                        "
                                    >
                                        <PrimaryButton>
                                            <i
                                                class="fa fa-envelope-circle-check mr-1"
                                            ></i>
                                            Gen Surat Keluar
                                        </PrimaryButton>
                                    </Link>
                                </div>
                            </DescriptionItem>
                        </dl>
                    </div>
                </template>
            </ActionSection>
        </div>
    </AppLayout>
</template>
