<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-black flex flex-col justify-center">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <div class="text-2xl font-bold">
                <span class="bg-yellow-500 px-2 py-1 rounded">P</span>
                <span class="text-yellow-500">PARKING</span>
                <span class="text-white">HUB</span>
            </div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-semibold text-white">Sign Up</h2>
            </div>

            <div class="px-4 sm:px-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <TextInput
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Name"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2 text-red-500 text-sm" :message="form.errors.name" />
                    </div>

                    <div>
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2 text-red-500 text-sm" :message="form.errors.email" />
                    </div>

                    <div>
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="Password"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2 text-red-500 text-sm" :message="form.errors.password" />
                    </div>

                    <div>
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm Password"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2 text-red-500 text-sm" :message="form.errors.password_confirmation" />
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 bg-yellow-500 text-black rounded-md font-semibold hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Sign Up
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <Link
                            :href="route('login')"
                            class="text-sm text-gray-400 hover:text-yellow-500"
                        >
                            Already have an account? Log in
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Override default styles */
:deep(.form-input) {
    background-color: rgb(31, 41, 55, 0.5);
    border-color: rgb(75, 85, 99);
    color: white;
}

:deep(.form-input:focus) {
    border-color: rgb(234, 179, 8);
    box-shadow: 0 0 0 2px rgba(234, 179, 8, 0.2);
}

:deep(.text-red-500) {
    color: #ef4444;
}
</style>