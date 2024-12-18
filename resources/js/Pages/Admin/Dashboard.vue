<template>
    <Head title="Admin Dashboard" />

    <AdminAuthenticatedLayout>
        <div class="min-h-screen bg-black relative">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Welcome Text -->
                <div class="mb-16">
                    <h1 class="text-3xl text-white font-medium mb-2">Welcome to</h1>
                    <h2 class="text-6xl font-bold">
                        <span class="text-yellow-400">ADMIN</span>
                        <span class="text-white">DASHBOARD</span>
                    </h2>
                    <p class="text-xl text-gray-300 mt-4">Manage your parking system with ease</p>
                </div>

                <!-- Navigation Cards Section -->
                <div class="bg-black/60 backdrop-blur-sm rounded-2xl p-8 max-w-6xl mx-auto">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl text-yellow-400 font-bold">Quick Access</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Parking Plan Card -->
                        <Link
                            :href="route('admin.parking-bookings.index')"
                            class="block p-6 bg-black/80 rounded-lg border border-gray-800 hover:border-yellow-400 transition-all duration-300"
                        >
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-yellow-400">Parking Plan</h5>
                            <p class="font-normal text-gray-300">
                                Manage parking bookings, view and update booking statuses
                            </p>
                        </Link>

                        <!-- Users Card -->
                        <Link
                            :href="route('admin.users')"
                            class="block p-6 bg-black/80 rounded-lg border border-gray-800 hover:border-yellow-400 transition-all duration-300"
                        >
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-yellow-400">Users</h5>
                            <p class="font-normal text-gray-300">
                                View and manage user accounts and their parking assignments
                            </p>
                        </Link>

                        <!-- Overview Card -->
                        <Link
                            :href="route('admin.overview')"
                            class="block p-6 bg-black/80 rounded-lg border border-gray-800 hover:border-yellow-400 transition-all duration-300"
                        >
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-yellow-400">Overview</h5>
                            <p class="font-normal text-gray-300">
                                Get a quick overview of system statistics and activities
                            </p>
                        </Link>
                    </div>

                    <!-- Welcome Message -->
                    <div class="mt-8 p-6 bg-black/40 rounded-lg border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-yellow-400">Welcome to your admin dashboard!</h3>
                        <p class="text-gray-300">Here you can manage parking bookings, user accounts, and view system statistics.</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<style>
.bg-hero-pattern {
    background-image: url('/Back.png');
}

.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.button, input, select {
    transition: all 0.2s ease-in-out;
}

/* Focus styles */
button:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #111827;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #4B5563;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6B7280;
}
</style>
