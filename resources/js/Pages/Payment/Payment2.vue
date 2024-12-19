<template>
    <div class="min-h-screen bg-black relative">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Title and Price -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Choose Payment Method</h1>
                <p class="text-3xl font-bold text-yellow-400">₱{{ bookingDetails.planPrice }}</p>
            </div>

            <!-- Payment Container -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <!-- Payment Method Selection -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <!-- GCash Option -->
                    <div class="border-2 rounded-xl p-6 cursor-pointer transition-all duration-300 flex flex-col items-center justify-center"
                        :class="selectedMethod === 'gcash' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-200'"
                        @click="selectMethod('gcash')">
                        <img src="/images/gcash-logo.png" alt="GCash" class="h-8 w-auto mb-2">
                        <span class="text-gray-600">GCash</span>
                    </div>

                    <!-- PayPal Option -->
                    <div class="border-2 rounded-xl p-6 cursor-pointer transition-all duration-300 flex flex-col items-center justify-center"
                        :class="selectedMethod === 'paypal' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-200'"
                        @click="selectMethod('paypal')">
                        <img src="/images/paypal-logo.png" alt="PayPal" class="h-8 w-auto mb-2">
                        <span class="text-gray-600">PayPal</span>
                    </div>
                </div>

                <div v-if="selectedMethod" class="space-y-6">
                    <!-- GCash Form -->
                    <template v-if="selectedMethod === 'gcash'">
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">GCash Number</label>
                            <input type="text" v-model="paymentDetails.gcashNumber" placeholder="09XX XXX XXXX"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                            <input type="text" v-model="paymentDetails.fullName" placeholder="Enter your full name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Email Address</label>
                            <input type="email" v-model="paymentDetails.email" placeholder="Enter your email address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </template>

                    <!-- PayPal Form -->
                    <template v-if="selectedMethod === 'paypal'">
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">PayPal Email</label>
                            <input type="email" v-model="paymentDetails.paypalEmail"
                                placeholder="Enter your PayPal email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Full Name</label>
                            <input type="text" v-model="paymentDetails.fullName" placeholder="Enter your full name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Transaction ID</label>
                            <input type="text" v-model="paymentDetails.transactionId"
                                placeholder="Enter PayPal transaction ID"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </template>

                    <button @click="processPayment"
                        class="w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                        Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import axios from 'axios';

const bookingDetails = ref({
    planId: '',
    planName: '',
    planPrice: '',
    carType: '',
    checkIn: '',
    checkOut: '',
    promoCode: '',
    duration: 0,
    parkingSlot: ''
});

const getBookingDetailsFromSessionStorage = () => {
    const storedBookingDetails = sessionStorage.getItem('bookingDetails');
    if (storedBookingDetails) {
        const parsedDetails = JSON.parse(storedBookingDetails);
        bookingDetails.value = {
            ...parsedDetails,
            parkingSlot: '' // Reset parking slot
        };
    }
};

onMounted(() => {
    getBookingDetailsFromSessionStorage();
});

const selectedMethod = ref(null);
const paymentDetails = ref({
    gcashNumber: '',
    fullName: '',
    email: '',
    paypalEmail: '',
    transactionId: ''
});

const selectMethod = (method) => {
    selectedMethod.value = method;
    paymentDetails.value = {
        gcashNumber: '',
        fullName: '',
        email: '',
        paypalEmail: '',
        transactionId: ''
    };
};

const processPayment = () => {
    if (!selectedMethod.value) {
        Swal.fire({
            icon: 'error',
            title: 'Payment Method Required',
            text: 'Please select a payment method to continue.'
        });
        return;
    }

    // Generate a unique parking slot
    axios.get('/api/generate-parking-slot').then(response => {
        const parkingSlot = response.data.slot_number;
        bookingDetails.value.parkingSlot = parkingSlot;

        // Mark the slot as occupied after successful payment
        router.post(route('payment.process'), {
            bookingDetails: bookingDetails.value,
            paymentMethod: selectedMethod.value,
            paymentDetails: paymentDetails.value,
        }, {
            onSuccess: () => {
                // Mark the parking slot as occupied
                axios.post(`/api/mark-slot-occupied/${parkingSlot}`);

                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    text: `Your booking has been confirmed. Parking Slot: ${parkingSlot}`,
                    confirmButtonColor: '#1d4ed8',
                }).then(() => {
                    window.location.href = route('dashboard');
                });
            },
            onError: (errors) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed',
                    text: Object.values(errors)[0] || 'There was an error processing your payment.'
                });
            }
        });
    }).catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Parking Slot Error',
            text: 'Unable to generate a parking slot. Please try again.'
        });
    });
};
</script>

<style scoped>
.bg-hero-pattern {
    background-image: url('/images/hero-pattern.jpg');
}
</style>