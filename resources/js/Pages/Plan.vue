<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    bookings: {
        type: Array,
        default: () => []
    }
});

// Format the booking data to display car type from notes
const formatBooking = (booking) => {
    const notes = booking.notes ? JSON.parse(booking.notes) : {};
    return {
        id: booking.id,
        carType: booking.car_type || 'N/A',
        checkIn: {
            date: new Date(booking.start_time).toLocaleDateString(),
            time: new Date(booking.start_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        },
        checkOut: {
            date: new Date(booking.end_time).toLocaleDateString(),
            time: new Date(booking.end_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        },
        parking_slot: booking.parking_slot,
        status: booking.status,
        amount: booking.amount || 'N/A'
    };
};

const formattedBookings = computed(() => {
    return props.bookings.map(formatBooking);
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString([], { hour: '2-digit', minute: '2-digit', hour12: false });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        default:
            return '';
    }
};

const cancelBooking = (id) => {
    // implement cancel booking logic here
};
</script>

<template>
    <Head title="Parking Plan" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-6">My Parking Bookingsss</h2>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Slot</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Car Type</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Start Time</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">End Time</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Amount</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Status</th>
                                        <th class="px-6 py-3 bg-gray-100 border-b text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="booking in formattedBookings" :key="booking.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 border-b">{{ booking.parking_slot.slot_number }}</td>
                                        <td class="px-6 py-4 border-b">{{ booking.carType }}</td>
                                        <td class="px-6 py-4 border-b">{{ formatDateTime(booking.checkIn.date + ' ' + booking.checkIn.time) }}</td>
                                        <td class="px-6 py-4 border-b">{{ formatDateTime(booking.checkOut.date + ' ' + booking.checkOut.time) }}</td>
                                        <td class="px-6 py-4 border-b">₱{{ booking.amount || '0.00' }}</td>
                                        <td class="px-6 py-4 border-b">
                                            <span :class="getStatusClass(booking.status)">
                                                {{ booking.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b">
                                            <button @click="cancelBooking(booking.id)" 
                                                    class="text-red-600 hover:text-red-800"
                                                    v-if="booking.status === 'pending'">
                                                Cancel
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>