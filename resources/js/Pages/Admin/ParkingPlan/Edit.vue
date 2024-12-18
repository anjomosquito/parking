<script setup>
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    booking: {
        type: Object,
        required: true
    }
});

const form = useForm({
    carType: props.booking.carType,
    checkIn: props.booking.checkIn,
    checkOut: props.booking.checkOut,
    parking_slot: props.booking.parking_slot,
    promoCode: props.booking.promoCode,
    status: props.booking.status
});

const statuses = [
    'pending',
    'approved',
    'rejected',
    'completed'
];

const handleSubmit = () => {
    form.patch(route('admin.parking-bookings.update', props.booking.id));
};
</script>

<template>
    <Head title="Edit Booking" />

    <AdminAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Booking
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <!-- Car Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Car Type</label>
                                <input
                                    type="text"
                                    v-model="form.carType"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>

                            <!-- Check In -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check In Date</label>
                                    <input
                                        type="date"
                                        v-model="form.checkIn.date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check In Time</label>
                                    <input
                                        type="time"
                                        v-model="form.checkIn.time"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                            </div>

                            <!-- Check Out -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check Out Date</label>
                                    <input
                                        type="date"
                                        v-model="form.checkOut.date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check Out Time</label>
                                    <input
                                        type="time"
                                        v-model="form.checkOut.time"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                            </div>

                            <!-- Parking Slot -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Parking Slot</label>
                                <input
                                    type="text"
                                    v-model="form.parking_slot"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>

                            <!-- Promo Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Promo Code</label>
                                <input
                                    type="text"
                                    v-model="form.promoCode"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option v-for="status in statuses" :key="status" :value="status">
                                        {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link
                                    :href="route('admin.users')"
                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    :disabled="form.processing"
                                >
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>
