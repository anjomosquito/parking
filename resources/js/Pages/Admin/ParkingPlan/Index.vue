
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    bookings: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            total: 0
        })
    }
});

const form = useForm({
    status: ''
});

const updateStatus = (bookingId, newStatus) => {
    form.status = newStatus;
    form.patch(route('admin.parking-bookings.update-status', bookingId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const formattedBookings = computed(() => {
    return props.bookings.data.map(booking => {
        const notes = booking.notes ? JSON.parse(booking.notes) : {};
        return {
            id: booking.id,
            user: booking.user?.name || 'Unknown User',
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
    <Head title="Admin - Parking Bookings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Parking Bookings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parking Slot</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="booking in formattedBookings" :key="booking.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ booking.user }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <button
                                                    v-if="booking.status === 'pending'"
                                                    @click="updateStatus(booking.id, 'approved')"
                                                    class="text-green-600 hover:text-green-900"
                                                >
                                                    Approve
                                                </button>
                                                <button
                                                    v-if="booking.status === 'pending'"
                                                    @click="updateStatus(booking.id, 'rejected')"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Reject
                                                </button>
                                                <button
                                                    v-if="booking.status === 'approved'"
                                                    @click="updateStatus(booking.id, 'completed')"
                                                    class="text-gray-600 hover:text-gray-900"
                                                >
                                                    Mark Complete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="formattedBookings.length === 0">
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No bookings found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-4" v-if="bookings.links && bookings.links.length > 3">
                            <div class="flex justify-between">
                                <template v-for="(link, index) in bookings.links" :key="index">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-3 py-1 rounded border"
                                        :class="{
                                            'bg-blue-500 text-white': link.active,
                                            'text-gray-600 hover:bg-gray-100': !link.active
                                        }"
                                    />
                                    <span
                                        v-else
                                        v-html="link.label"
                                        class="px-3 py-1 text-gray-400"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>