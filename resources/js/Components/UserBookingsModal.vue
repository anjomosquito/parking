<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-2xl font-semibold mb-6">User Bookings History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Car Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Slot</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="booking in bookings" :key="booking.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ booking.car_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ booking.parking_slot?.slot_number ||
                                'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDateTime(booking.start_time) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDateTime(booking.end_time) }}</td>
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
                        </tr>
                        <tr v-if="bookings.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import { format } from 'date-fns';

const props = defineProps({
    show: Boolean,
    bookings: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

const formatDateTime = (dateString) => {
    if (!dateString) return 'N/A';
    return format(new Date(dateString), 'MMM dd, yyyy HH:mm');
};
</script>
