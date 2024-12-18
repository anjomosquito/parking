<script setup>
import { Head } from '@inertiajs/vue3';

// Accept props passed from the controller
const props = defineProps({
    bookingDetails: Object
});
</script>

<template>
    <div>
        <Head title="Parking Plan" />
        
        <h1>Your Booking Details</h1>
        
        <!-- Display booking information -->
        <div v-if="bookingDetails">
            <p>Car Type: {{ bookingDetails.carType }}</p>
            <p>Check-In: {{ bookingDetails.checkIn.date }} at {{ bookingDetails.checkIn.time }}</p>
            <p>Check-Out: {{ bookingDetails.checkOut.date }} at {{ bookingDetails.checkOut.time }}</p>
            <p v-if="bookingDetails.promoCode">Promo Code: {{ bookingDetails.promoCode }}</p>
        </div>
    </div>
</template>