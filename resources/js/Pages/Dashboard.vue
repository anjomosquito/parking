<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Form handling with Inertia
const form = useForm({
    carType: 'Honda Civic',
    checkIn: {
        date: '',
        time: '09:00'
    },
    checkOut: {
        date: '',
        time: '09:00'
    },
    promoCode: '',
    parking_slot: 'A1'
});

// Dropdowns State
const showDropdowns = ref({
    carType: false,
    checkInTime: false,
    checkOutTime: false
});

// Calendar State
const showCalendars = ref({
    checkIn: false,
    checkOut: false
});

// Available Car Types
const carTypes = [
    'Honda Civic',
    'Toyota Camry',
    'Tesla Model 3',
    'BMW 3 Series',
    'Mercedes C-Class'
];

// Form error handling
const formErrors = computed(() => {
    return {
        checkIn: form.errors['checkIn.date'] || form.errors['checkIn.time'],
        checkOut: form.errors['checkOut.date'] || form.errors['checkOut.time'],
        promoCode: form.errors['promoCode']
    };
});

// Submit Function
const handleSubmit = () => {
    form.post(route('parkingPlan.store'), {
        onSuccess: () => {
            // Handle success (e.g., show a success message)
            console.log('Booking successful!');
            // Optionally, show a success message to the user
            alert('Booking successful!');
        },
        onError: () => {
            // Handle error (e.g., show error messages)
            console.log('Booking failed!');
            // Optionally, show an error message to the user
            alert('Booking failed! Please check your inputs and try again.');
        }
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-black relative" @click="closeAllDropdowns()">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Welcome Text -->
                <div class="mb-16">
                    <h1 class="text-3xl text-white font-medium mb-2">Welcome to</h1>
                    <h2 class="text-6xl font-bold">
                        <span class="text-yellow-400">Your</span>
                        <span class="text-white">Dashboard</span>
                    </h2>
                </div>

                <div class="bg-black/60 backdrop-blur-sm rounded-2xl p-8 max-w-6xl mx-auto shadow-lg">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl text-yellow-400 font-bold">Book Now</h3>
                    </div>
                    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Car Type Selection -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">SELECT CAR TYPE</label>
                            <select v-model="form.carType" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200">
                                <option v-for="car in carTypes" :key="car" :value="car">{{ car }}</option>
                            </select>
                        </div>
                        <!-- Check In -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">CHECK IN</label>
                            <div class="flex gap-3">
                                <input type="date" v-model="form.checkIn.date" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200" />
                                <input type="time" v-model="form.checkIn.time" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200" />
                                <p v-if="formErrors.checkIn" class="mt-2 text-sm text-red-500">{{ formErrors.checkIn }}</p>
                            </div>
                        </div>
                        <!-- Check Out -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">CHECK OUT</label>
                            <div class="flex gap-3">
                                <input type="date" v-model="form.checkOut.date" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200" />
                                <input type="time" v-model="form.checkOut.time" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200" />
                                <p v-if="formErrors.checkOut" class="mt-2 text-sm text-red-500">{{ formErrors.checkOut }}</p>
                            </div>
                        </div>
                        <!-- Promo Code -->
                        <div>
                            <label class="block text-sm font-medium text-yellow-400 mb-3">PROMO CODE (optional)</label>
                            <input type="text" v-model="form.promoCode" class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-400 transition duration-200" />
                        </div>
                        <div class="md:col-span-2 mt-8 flex justify-end">
                            <button type="submit" class="w-full bg-yellow-400 text-white font-bold py-2 rounded-md hover:bg-yellow-500 transition duration-200 shadow-md">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
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

/* Hover effects on inputs */
input:hover, input:focus {
    border-color: #4B5563;
    outline: none;
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

/* Calendar transitions */
.calendar-enter-active,
.calendar-leave-active {
    transition: all 0.2s ease-in-out;
}

.calendar-enter-from,
.calendar-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Focus styles */
button:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}

input:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}
</style>