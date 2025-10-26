<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import FullScreenLayout from "@/components/layout/FullScreenLayout.vue";
import GoogleIcon from "@/icons/GoogleIcon.vue";
import InputLabel from "@/components/FormElements/InputLabel.vue";
import InputText from "@/components/FormElements/InputText.vue";
import OpenEye from "@/icons/OpenEye.vue";
import CloseEye from "@/icons/CloseEye.vue";
import InputCheckbox from "@/components/FormElements/InputCheckbox.vue";
import Button from "@/components/buttons/Button.vue";
import InputError from "@/components/FormElements/InputError.vue";

const showPassword = ref(false);
const form = useForm({
    email: "",
    password: "",
    keepLoggedIn: false,
});

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const handleSubmit = () => {
    form.post("signin", {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <FullScreenLayout>
        <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
            <div
                class="relative flex flex-col justify-center w-full h-screen lg:flex-row dark:bg-gray-900"
            >
                <div class="flex flex-col flex-1 w-full lg:w-1/2">
                    <div
                        class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto"
                    >
                        <div>
                            <div class="mb-5 sm:mb-8">
                                <h1
                                    class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
                                >
                                    Sign In
                                </h1>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    Enter your email and password to sign in!
                                </p>
                            </div>
                            <div>
                                <div class="grid grid-cols-1 gap-5">
                                    <button
                                        class="inline-flex items-center justify-center gap-3 py-3 text-sm font-normal text-gray-700 transition-colors bg-gray-100 rounded-lg px-7 hover:bg-gray-200 hover:text-gray-800 dark:bg-white/5 dark:text-white/90 dark:hover:bg-white/10"
                                    >
                                        <GoogleIcon />
                                        Sign in with Google
                                    </button>
                                </div>
                                <div class="relative py-3 sm:py-5">
                                    <div
                                        class="absolute inset-0 flex items-center"
                                    >
                                        <div
                                            class="w-full border-t border-gray-200 dark:border-gray-800"
                                        ></div>
                                    </div>
                                    <div
                                        class="relative flex justify-center text-sm"
                                    >
                                        <span
                                            class="p-2 text-gray-400 bg-white dark:bg-gray-900 sm:px-5 sm:py-2"
                                            >Or</span
                                        >
                                    </div>
                                </div>
                                <form @submit.prevent="handleSubmit">
                                    <div class="space-y-5">
                                        <!-- Email -->
                                        <div>
                                            <InputLabel
                                                label="Email"
                                                for="email"
                                            />
                                            <InputText
                                                v-model="form.email"
                                                type="email"
                                                id="email"
                                                placeholder="johndoe@example.com"
                                            />
                                            <InputError
                                                :message="form.errors.email"
                                            />
                                        </div>
                                        <!-- Password -->
                                        <div>
                                            <InputLabel
                                                label="Password"
                                                for="password"
                                            />
                                            <div class="relative">
                                                <InputText
                                                    v-model="form.password"
                                                    :type="
                                                        showPassword
                                                            ? 'text'
                                                            : 'password'
                                                    "
                                                    id="password"
                                                    placeholder="Enter your password"
                                                />
                                                <span
                                                    @click="
                                                        togglePasswordVisibility
                                                    "
                                                    class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400"
                                                >
                                                    <OpenEye
                                                        v-if="!showPassword"
                                                    />
                                                    <CloseEye v-else />
                                                </span>
                                            </div>
                                            <InputError
                                                :message="form.errors.password"
                                            />
                                        </div>
                                        <!-- Checkbox -->
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div>
                                                <InputCheckbox
                                                    label="Keep me login"
                                                    v-model="form.keepLoggedIn"
                                                />
                                            </div>
                                            <Link
                                                href="/reset-password"
                                                class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400"
                                                >Forgot password?</Link
                                            >
                                        </div>
                                        <!-- Button -->
                                        <div>
                                            <Button type="submit">
                                                Sign in
                                            </Button>
                                        </div>
                                    </div>
                                </form>
                                <div class="mt-5">
                                    <p
                                        class="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start"
                                    >
                                        Don't have an account?
                                        <Link
                                            href="/signup"
                                            class="text-brand-500 hover:text-brand-600 dark:text-brand-400"
                                            >Sign Up</Link
                                        >
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>
