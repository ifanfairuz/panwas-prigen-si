<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import Logo from "@/Components/Logo.vue";
import InputError from "@/Components/InputError.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Masuk" />

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
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="inline-flex items-center cursor-pointer"
                                    >
                                        <input
                                            v-model="form.remember"
                                            name="remember"
                                            id="remember-checkbox"
                                            type="checkbox"
                                            class="form-checkbox border-0 rounded text-slate-700 ml-1 w-5 h-5 ease-linear transition-all duration-150"
                                        />
                                        <span
                                            class="ml-2 text-sm font-semibold text-slate-600"
                                        >
                                            Ingat Saya
                                        </span>
                                    </label>
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
                                        Masuk
                                    </button>
                                </div>
                            </form>
                            <div class="flex flex-wrap mt-2 relative">
                                <div class="w-1/2">
                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-slate-800"
                                    >
                                        <small>Lupa Password ?</small>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
