<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    auth: Object,
});

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-black">
            <nav class="bg-black border-b border-gray-800">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <Link :href="route('dashboard')" class="text-2xl font-bold">
                            <span class="text-yellow-400">P PARKING</span>
                            <span class="text-white">HUB</span>
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="flex items-center space-x-8">
                            <Link :href="route('dashboard')" :class="route().current('dashboard')
                                ? 'text-yellow-400 border-b-2 border-yellow-400'
                                : 'text-gray-300 hover:text-white'">
                            Home
                            </Link>

                            <Link :href="route('about.us')" :class="route().current('about.us')
                                ? 'text-yellow-400 border-b-2 border-yellow-400'
                                : 'text-gray-300 hover:text-white'">
                            About us
                            </Link>

                            <Link :href="route('parkingPlan.index')" :class="route().current('parkingPlan.index')
                                ? 'text-yellow-400 border-b-2 border-yellow-400'
                                : 'text-gray-300 hover:text-white'">
                            Plan
                            </Link>

                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="w-8 h-8 bg-gray-700 rounded-full">
                                        </button>
                                    </template>

                                    <template #content>
                                        <div
                                            class="bg-white/90 backdrop-blur-md rounded-lg shadow-xl overflow-hidden min-w-[200px]">
                                            <!-- User Name -->
                                            <div class="px-4 py-3 border-b border-gray-100">
                                                <p class="text-gray-600 text-sm font-light">
                                                    {{ $page.props.auth.user.name }}
                                                </p>
                                            </div>

                                            <!-- Menu Items -->
                                            <div class="py-1">
                                                <DropdownLink :href="route('profile.edit')"
                                                    class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition duration-150">
                                                    Profile
                                                </DropdownLink>

                                                <DropdownLink :href="route('logout')" method="post" as="button"
                                                    class="block w-full text-left px-4 py-2 text-sm text-black-600 hover:bg-black-50 transition duration-150">
                                                    Log Out
                                                </DropdownLink>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-800 focus:outline-none focus:bg-gray-800 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <Link :href="route('dashboard')"
                            class="block w-full ps-3 pe-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:text-white focus:bg-gray-800 transition duration-150 ease-in-out">
                        Home
                        </Link>
                        <Link :href="route('about.us')"
                            class="block w-full ps-3 pe-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:text-white focus:bg-gray-800 transition duration-150 ease-in-out">
                        About us
                        </Link>
                        <Link :href="route('parkingPlan.index')"
                            class="block w-full ps-3 pe-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:text-white focus:bg-gray-800 transition duration-150 ease-in-out">
                        Plan
                        </Link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-800">
                        <div class="px-4">
                            <div class="font-medium text-base text-white">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <Link :href="route('profile.edit')"
                                class="block w-full ps-3 pe-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:text-white focus:bg-gray-800 transition duration-150 ease-in-out">
                            Profile
                            </Link>
                            <Link :href="route('logout')" method="post" as="button"
                                class="block w-full text-start ps-3 pe-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-800 focus:outline-none focus:text-white focus:bg-gray-800 transition duration-150 ease-in-out">
                            Log Out
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* Dropdown specific styles */
.backdrop-blur-md {
    -webkit-backdrop-filter: blur(12px);
    backdrop-filter: blur(12px);
}

/* Animation for dropdown */
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

/* General transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Focus styles */
.focus-visible:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    --tw-ring-color: rgb(250 204 21 / 0.5);
}
</style>