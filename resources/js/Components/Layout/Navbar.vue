<script setup>
import Banner from "../Banner.vue";
// import FormSearch from "./FormSearch.vue";
import UserDropdown from "./UserDropdown.vue";
</script>

<template>
    <div class="shadow-md">
        <!-- Navbar -->
        <nav
            class="hidden bg-white md:flex-row md:flex-nowrap md:justify-start md:flex items-center p-4"
        >
            <div
                class="flex-1 items-center flex justify-between md:flex-nowrap flex-wrap md:px-8 px-4"
            >
                <!-- Brand -->
                <a
                    class="text-lg hidden lg:inline-block font-semibold"
                    href="javascript:void(0)"
                >
                    <slot name="header" />
                </a>
                <!-- Form -->
                <!-- <div
                    class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto"
                >
                    <FormSearch />
                </div> -->
                <!-- User -->
                <div
                    class="flex-col md:flex-row list-none items-center hidden md:flex"
                >
                    <UserDropdown />
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <Banner />
    </div>
</template>
