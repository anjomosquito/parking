<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import Swal from 'sweetalert2';

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

// Booking details
const bookingDetails = ref({
    planId: '',
    planName: '',
    planPrice: '',
    carType: '',
    checkIn: '',
    checkOut: '',
    promoCode: '',
    duration: 0
});

onMounted(() => {
    // Retrieve booking details from session storage
    const storedBookingDetails = sessionStorage.getItem('bookingDetails');
    if (storedBookingDetails) {
        const parsedDetails = JSON.parse(storedBookingDetails);
        bookingDetails.value = {
            ...parsedDetails,
            carType: parsedDetails.carType || '',  // ensure carType is set
            duration: 0
        };
    }
});

// Submit Function
const handleSubmit = () => {
    form.post(route('parkingPlan.store'), {
        onSuccess: () => {
            // Show success notification using SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Booking Successful!',
                text: 'Your parking booking has been successfully created.',
                confirmButtonText: 'Okay',
                background: '#1a202c',
                color: '#fff',
                confirmButtonColor: '#FBBF24',
            });
        },
        onError: () => {
            // Show error notification using SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Booking Failed!',
                text: 'There was an error with your booking. Please check your inputs and try again.',
                confirmButtonText: 'Okay',
                background: '#1a202c',
                color: '#fff',
                confirmButtonColor: '#FBBF24',
            });
        }
    });
};

onMounted(() => {
  const storedBookingData = sessionStorage.getItem('bookingData');
  if (storedBookingData) {
    const bookingData = JSON.parse(storedBookingData);
    form.carType = bookingData.carType;
    form.checkIn = bookingData.checkIn;
    form.checkOut = bookingData.checkOut;
    form.promoCode = bookingData.promoCode;
    form.parking_slot = bookingData.parkingSlot;
  }
});

const getPlanPrice = (plan) => {
  switch (plan) {
    case 'regular':
      return 200;
    case 'premium':
      return 350;
    case 'luxury':
      return 500;
    default:
      return 0;
  }
};

const selectPlan = (plan) => {
  // Get the selected plan's price
  const price = getPlanPrice(plan);
  
  // Combine booking details with selected plan
  const paymentData = {
    ...bookingDetails.value,
    selectedPlan: plan,
    planPrice: price
  };
  
  // Store updated data in session storage
  sessionStorage.setItem('bookingDetails', JSON.stringify(paymentData));
  sessionStorage.setItem('selectedPlanPrice', price);
  sessionStorage.setItem('selectedCarType', form.carType);
  
  // Redirect to Payment2 using Inertia router
  router.visit(route('payment.step2'));
};
</script>

<template>
  <div class="min-h-screen bg-black relative">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
      <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <!-- Recommended Label -->
      <div class="text-center mb-8">
        <span class="bg-yellow-400 text-black px-4 py-1 rounded-full font-semibold">
          Recommended
        </span>
      </div>

      <!-- Pricing Cards Container -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Regular Class -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
          <h2 class="text-2xl font-bold mb-4">Regular Class</h2>
          <div class="text-4xl font-bold mb-6">
            <span class="text-2xl">₱</span>200
          </div>
          <p class="text-gray-600 mb-6">
            Basic parking services for standard vehicles
          </p>
          <ul class="text-left space-y-4 mb-8">
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Standard parking spot
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Basic security
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              24/7 access
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Regular maintenance
            </li>
          </ul>
          <button @click="selectPlan('regular')" class="w-full border-2 border-yellow-400 text-black font-bold py-2 px-4 rounded hover:bg-yellow-400 hover:text-white transition duration-300">
            Select Plan
          </button>
        </div>

        <!-- Premium Class -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center transform scale-105">
          <div class="relative">
            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
              <span class="bg-yellow-400 text-black px-4 py-1 rounded-full text-sm font-semibold">
                Most Popular
              </span>
            </div>
          </div>
          <h2 class="text-2xl font-bold mb-4">Premium Class</h2>
          <div class="text-4xl font-bold mb-6">
            <span class="text-2xl">₱</span>350
          </div>
          <p class="text-gray-600 mb-6">
            Enhanced parking experience with added benefits
          </p>
          <ul class="text-left space-y-4 mb-8">
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Premium parking location
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Enhanced security
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Priority access
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Car wash service
            </li>
          </ul>
          <button @click="selectPlan('premium')" class="w-full bg-yellow-400 text-white font-bold py-2 px-4 rounded hover:bg-yellow-500 transition duration-300">
            Select Plan
          </button>
        </div>

        <!-- Luxury Class -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
          <h2 class="text-2xl font-bold mb-4">Luxury Class</h2>
          <div class="text-4xl font-bold mb-6">
            <span class="text-2xl">₱</span>500
          </div>
          <p class="text-gray-600 mb-6">
            VIP parking experience with premium amenities
          </p>
          <ul class="text-left space-y-4 mb-8">
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              VIP parking space
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              24/7 valet service
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Premium security
            </li>
            <li class="flex items-center">
              <span class="text-yellow-400 mr-2">•</span>
              Full car maintenance
            </li>
          </ul>
          <button @click="selectPlan('luxury')" class="w-full border-2 border-yellow-400 text-black font-bold py-2 px-4 rounded hover:bg-yellow-400 hover:text-white transition duration-300">
            Select Plan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.bg-hero-pattern {
  background-image: url('/images/hero-pattern.jpg');
}
</style>