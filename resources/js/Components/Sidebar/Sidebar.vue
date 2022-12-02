<script setup>
import { ref } from "vue";
import UserDropdown from "../Layout/UserDropdown.vue";
import Logo from "@/Components/Logo.vue";
// import FormSearch from "../Layout/FormSearch.vue";
import NavLink from "./NavLink.vue";
import NavHead from "./NavHead.vue";

let show = ref(false);

const setShow = (v) => (show.value = v);
</script>

<template>
    <nav
        class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-4"
    >
        <div
            class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto"
        >
            <!-- Toggler -->
            <button
                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                type="button"
                @click="setShow(true)"
            >
                <i class="fas fa-bars"></i>
            </button>
            <!-- Brand -->
            <div class="hidden justify-center md:flex flex-wrap list-none">
                <Logo :small="true" />
            </div>
            <!-- User -->
            <div class="md:hidden items-center flex flex-wrap list-none">
                <UserDropdown />
            </div>

            <!-- Collapse -->
            <div
                class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded"
                :class="[show ? 'bg-white m-2 mt-0 py-3 px-4' : 'hidden']"
            >
                <!-- Collapse header -->
                <div class="md:min-w-full md:hidden block">
                    <div class="flex flex-wrap items-center">
                        <div class="flex-1 flex">
                            <Logo :small="true" />
                        </div>
                        <button
                            type="button"
                            class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                            @click="setShow(false)"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- Form -->
                <!-- <div class="mt-6 mb-4 md:hidden">
                    <FormSearch />
                </div> -->

                <!-- Divider -->
                <hr class="my-3 mb-4 md:min-w-full" />

                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <NavLink
                        href="/dashboard"
                        :active="route().current('dashboard')"
                    >
                        <i class="fas fa-tv mr-2 text-sm"></i>
                        Dashboard
                    </NavLink>
                </ul>

                <!-- Heading -->
                <NavHead>Surat</NavHead>

                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <NavLink
                        :href="route('surat.masuk.index')"
                        :active="route().current('surat.masuk.*')"
                    >
                        <i class="fas fa-envelope-open mr-2 text-sm"></i>
                        Surat Masuk
                    </NavLink>
                    <NavLink
                        :href="route('surat.keluar.index')"
                        :active="route().current('surat.keluar.*')"
                    >
                        <i class="fas fa-envelope mr-2 text-sm"></i>
                        Surat Keluar
                    </NavLink>
                </ul>

                <!-- Heading -->
                <NavHead>Pembukuan</NavHead>

                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <NavLink
                        :href="route('keuangan.index')"
                        :active="route().current('keuangan.*')"
                    >
                        <i class="fas fa-usd mr-2 text-sm"></i>
                        Keuangan
                    </NavLink>
                </ul>

                <template v-if="$page.props.isAdministrator">
                    <!-- Heading -->
                    <NavHead>Administrator</NavHead>

                    <!-- Navigation -->
                    <ul
                        class="md:flex-col md:min-w-full flex flex-col list-none"
                    >
                        <NavLink
                            :href="route('administration.users.index')"
                            :active="route().current('administration.users.*')"
                        >
                            <i class="fas fa-users mr-2 text-sm"></i>
                            Users
                        </NavLink>
                    </ul>
                </template>
            </div>
        </div>
    </nav>
</template>
