<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
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
                <h2 class="text-3xl font-semibold text-white">Login</h2>
            </div>

            <div class="px-4 sm:px-6">
                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email or phone number"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                            class="block w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:border-yellow-500 focus:ring focus:ring-yellow-500/50"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-gray-700 text-yellow-500 focus:ring-yellow-500" />
                            <span class="ml-2 text-sm text-gray-400">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-gray-400 hover:text-yellow-500"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 bg-yellow-500 text-black rounded-md font-semibold hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Login
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <Link 
                            :href="route('admin.login')"
                            class="text-sm text-gray-400 hover:text-yellow-500 underline"
                        >
                            Log in as Admin
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

:deep(.form-checkbox) {
    color: rgb(234, 179, 8);
    border-color: rgb(75, 85, 99);
}

:deep(.form-checkbox:focus) {
    border-color: rgb(234, 179, 8);
    box-shadow: 0 0 0 2px rgba(234, 179, 8, 0.2);
}
</style>