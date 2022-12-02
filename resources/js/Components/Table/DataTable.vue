<template>
    <EasyDataTable
        theme-color="#2563eb"
        alternating
        buttons-pagination
        show-index
    >
        <template
            v-for="(_, name) in $slots"
            v-slot:[name]="slotData"
            :key="name"
        >
            <slot :name="name" v-bind="slotData" />
        </template>
    </EasyDataTable>
</template>
