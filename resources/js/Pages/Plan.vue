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
const formattedBookings = computed(() => {
    return props.bookings.map(booking => {
        const notes = booking.notes ? JSON.parse(booking.notes) : {};
        return {
            id: booking.id,
            carType: notes.carType || 'N/A',
            checkIn: {
                date: new Date(booking.start_time).toLocaleDateString(),
                time: new Date(booking.start_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            },
            checkOut: {
                date: new Date(booking.end_time).toLocaleDateString(),
                time: new Date(booking.end_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            },
            status: booking.status,
            promoCode: notes.promoCode || '-',
            parking_slot: booking.parking_slot
        };
    });
});
</script>

<template>
    <Head title="Parking Plan" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-semibold mb-6">My Parking Bookings</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parking Slot</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promo Code</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="booking in formattedBookings" :key="booking.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ booking.carType }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ booking.parking_slot }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ booking.checkIn.date }} {{ booking.checkIn.time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ booking.checkOut.date }} {{ booking.checkOut.time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'px-2 py-1 text-xs font-medium rounded-full': true,
                                                'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                                                'bg-green-100 text-green-800': booking.status === 'approved',
                                                'bg-red-100 text-red-800': booking.status === 'rejected',
                                                'bg-gray-100 text-gray-800': booking.status === 'completed'
                                            }">
                                                {{ booking.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ booking.promoCode }}</td>
                                    </tr>
                                    <tr v-if="formattedBookings.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No bookings found. Make your first booking from the Dashboard!
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