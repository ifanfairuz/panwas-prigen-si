<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "./Form.vue";

const props = defineProps({
    data: Object,
});

const form = useForm({
    name: props.data.name,
    email: props.data.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.put(route("administration.users.update", props.data.id), {
        errorBag: "updateUser",
        preserveScroll: true,
        onSuccess: () => form.reset("password", "password_confirmation"),
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
