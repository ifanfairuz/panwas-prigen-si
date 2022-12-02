<script setup>
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Logo from "@/Components/Logo.vue";
import InputError from "@/Components/Form/InputError.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import TextInput from "@/Components/Form/TextInput.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

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
                            <div class="mb-4 text-sm text-gray-600">
                                lupa kata sandi Anda? Tidak masalah. isi alamat
                                email Anda dan kami akan mengirim email kepada
                                Anda tautan setel ulang kata sandi.
                            </div>
                            <div
                                v-if="status"
                                class="mb-4 font-medium text-sm text-green-600"
                            >
                                {{ status }}
                            </div>
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

                                <div class="text-center mt-6">
                                    <button
                                        type="submit"
                                        class="bg-slate-800 text-white active:bg-slate-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Kirim Link Reset Password
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
