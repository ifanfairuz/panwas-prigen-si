<template>
    <v-select :clearable="false" :reduce="({ code }) => code">
        <template v-for="(_, name) in $slots" #[name]="slotData" :key="name">
            <slot :name="name" v-bind="slotData" />
        </template>
        <template #no-options="{ search, searching, loading }">
            Tidak ada data ditemukan
        </template>
        <template #open-indicator="{ attributes }">
            <i v-bind="attributes" class="fa fa-chevron-down"></i>
        </template>
    </v-select>
</template>
<style>
:root {
    --vs-border-color: rgb(147, 197, 253);
}
.v-select {
    @apply text-sm shadow-md rounded-md;
}
.vs__dropdown-toggle,
.vs__dropdown-menu {
    @apply bg-gray-50;
}
.vs__dropdown-menu {
    @apply rounded-b-md shadow-md;
}
.vs__dropdown-toggle {
    @apply rounded-md py-1;
}
.v-select:not(.vs--open) .vs__selected {
    @apply mt-0;
}
.vs__actions {
    @apply pt-0;
}
.vs__search::placeholder {
    @apply text-slate-400;
}
</style>
<!-- class="border-blue-300 px-2 py-2 placeholder-slate-400 text-slate-600 bg-gray-50 rounded-md text-sm shadow-md focus:outline-none w-full ease-linear transition-all duration-150" -->
