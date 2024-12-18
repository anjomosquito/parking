<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="About Us" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-black relative">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Section Title -->
                <div class="text-center mb-12">
                    <h2 class="text-6xl font-bold">
                        <span class="text-yellow-400">About</span>
                        <span class="text-white">Us</span>
                    </h2>
                </div>

                <!-- Cards Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-black/80 p-6 rounded-lg shadow-lg backdrop-blur-sm text-white">
                        <h3 class="text-yellow-400 text-2xl font-bold mb-4">Harman / Cardon Audio</h3>
                        <p class="text-gray-300">
                            Our facility is equipped with premium Harman Kardon audio systems, providing an immersive experience. Music fills the space with crystal-clear sound, creating a perfect ambiance as you drop off your vehicle. Stay tuned for upcoming special music events.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-black/80 p-6 rounded-lg shadow-lg backdrop-blur-sm text-white">
                        <h3 class="text-yellow-400 text-2xl font-bold mb-4">Security Guard or Anti Thief</h3>
                        <p class="text-gray-300">
                            Our parking facility is equipped with advanced security systems to ensure the safety of your vehicle. Trained professionals patrol the area, monitor surveillance systems, and respond quickly to any suspicious activity.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-black/80 p-6 rounded-lg shadow-lg backdrop-blur-sm text-white">
                        <h3 class="text-yellow-400 text-2xl font-bold mb-4">Spacious Parking Lot</h3>
                        <p class="text-gray-300">
                            Our extensive parking lot offers plenty of room for vehicles of all sizes. Wide spaces and clear markings make parking a breeze, while state-of-the-art lighting ensures excellent visibility at all hours.
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-black/80 p-6 rounded-lg shadow-lg backdrop-blur-sm text-white">
                        <h3 class="text-yellow-400 text-2xl font-bold mb-4">About Parking Hub</h3>
                        <p class="text-gray-300">
                            At Parking Hub, we're dedicated to making parking hassle-free and efficient. Our web app connects drivers with convenient parking spots in real-time, offering a simple platform to locate, reserve, and manage parking spaces.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.bg-hero-pattern {
    background-image: url('Back.png');
}

/* Smooth card hover effects */
div.bg-black\/80:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease-in-out;
}

/* Text and blur styles */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
</style>
