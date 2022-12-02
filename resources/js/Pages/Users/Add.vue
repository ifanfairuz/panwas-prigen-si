<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("administration.users.create"), {
        errorBag: "createUser",
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout title="Users">
        <template #header> Users </template>

        <div class="py-8">
            <Form :form="form" @submit="submit" />
        </div>
    </AppLayout>
</template>
