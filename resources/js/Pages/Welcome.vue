<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-black">
        <!-- Navigation -->
        <nav class="bg-black border-b border-gray-800 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold flex items-center">
                            <span class="text-yellow-400 mr-1">P</span>
                            <span class="text-yellow-400">PARKING</span>
                            <span class="text-white ml-2">HUB</span>
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-8">
                        <Link href="#" class="text-yellow-400 pb-1 border-b-2 border-yellow-400">Home</Link>
                        <Link href="#" class="text-gray-300 hover:text-white">About us</Link>
                        <Link href="#" class="text-gray-300 hover:text-white">Plan</Link>
                        
                        <div v-if="canLogin" class="flex items-center">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="bg-yellow-400 text-black px-4 py-1.5 rounded-md hover:bg-yellow-500"
                            >Dashboard</Link>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="bg-yellow-400 text-black px-4 py-1.5 rounded-md hover:bg-yellow-500"
                                >Login</Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="text-gray-300 hover:text-white ml-6"
                                >Register</Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section with Background -->
        <div class="relative min-h-screen">
            <!-- Background Image Overlay -->
            <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div class="text-left mb-20">
                        <h2 class="text-3xl text-white font-medium mb-2">Welcome to</h2>
                        <h1 class="text-6xl font-bold">
                            <span class="text-yellow-400">PARKING</span>
                            <span class="text-white ml-3">HUB</span>
                        </h1>
                        <p class="mt-4 text-xl text-gray-300">Parking system with premium service</p>
                    </div>

                    <!-- Booking Form -->
                    <div class="mt-12 bg-black/60 backdrop-blur-sm rounded-2xl p-8 max-w-6xl">
                        <h2 class="text-3xl text-yellow-400 font-bold text-center mb-8">Book Now</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-yellow-400 mb-2">SELECT VEHICLE TYPE</label>
                                <div class="relative">
                                    <select class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800 appearance-none">
                                        <option>Four Wheelers</option>
                                        <option>Two Wheelers</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-yellow-400 mb-2">CHECK IN</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" placeholder="Select Date" class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="relative flex-1">
                                        <input type="text" value="09:00 AM" readonly class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-yellow-400 mb-2">CHECK OUT</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" placeholder="Select Date" class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="relative flex-1">
                                        <input type="text" value="09:00 AM" readonly class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-yellow-400 mb-2">PROMO CODE <span class="text-gray-400">(optional)</span></label>
                                <input type="text" class="w-full bg-black/80 text-white rounded px-4 py-3 border border-gray-800">
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <Link 
                                :href="route('login')" 
                                class="bg-yellow-400 text-black px-8 py-3 rounded-full font-bold text-lg hover:bg-yellow-500 inline-block text-center"
                            >
                                Book Now
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-hero-pattern {
    background-image: url('Back.png');
}

.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.button, input, select {
    transition: all 0.2s ease-in-out;
}

/* Hover effects */
input:hover, input:focus, select:hover, select:focus {
    border-color: #4B5563;
    outline: none;
}

/* Remove default select styling */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>