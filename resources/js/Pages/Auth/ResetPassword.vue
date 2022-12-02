<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Logo from "@/Components/Logo.vue";
import InputError from "@/Components/Form/InputError.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import TextInput from "@/Components/Form/TextInput.vue";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.update"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Reset Password" />

    <AuthLayout>
        <div class="container mx-auto px-4 h-full">
            <div class="flex content-center items-center justify-center h-full">
                <div class="w-full lg:w-4/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-slate-200 border-0"
                    >
                        <div class="rounded-t mb-0 px-6 py-6">
                            <div class="flex flex-col items-center">
                                <Logo />
                            </div>
                            <hr class="mt-6 border-b-1 border-slate-300" />
                        </div>
                        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                            <form @submit.prevent="submit">
                                <div class="relative w-full mb-3">
                                    <label
                                        class="block uppercase text-slate-600 text-xs font-bold mb-2"
                                    >
                                        Email
                                    </label>
                                    <TextInput
                                        v-model="form.email"
                                        id="email"
                                        type="email"
                                        placeholder="Email"
                                        required
                                        autofocus
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />
                                </div>

                                <div class="relative w-full mb-3">
                                    <label
                                        class="block uppercase text-slate-600 text-xs font-bold mb-2"
                                    >
                                        Password
                                    </label>
                                    <TextInput
                                        v-model="form.password"
                                        type="password"
                                        placeholder="Password"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />
                                </div>

                                <div class="relative w-full mb-3">
                                    <label
                                        class="block uppercase text-slate-600 text-xs font-bold mb-2"
                                    >
                                        Password Confirmation
                                    </label>
                                    <TextInput
                                        v-model="form.password_confirmation"
                                        type="password"
                                        placeholder="Password"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors.password_confirmation
                                        "
                                    />
                                </div>

                                <div class="text-center mt-6">
                                    <button
                                        type="submit"
                                        class="bg-slate-800 text-white active:bg-slate-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
