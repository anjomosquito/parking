
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

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
    <Head title="Parking Plan" />

    <div class="min-h-screen bg-black relative">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-center mb-16">
                <!-- Welcome Text -->
                <div>
                    <h1 class="text-3xl text-white font-medium mb-2">Welcome to</h1>
                    <h2 class="text-6xl font-bold">
                        <span class="text-yellow-400">ADMIN</span>
                        <span class="text-white">PARKING PLAN</span>
                    </h2>
                    <p class="text-xl text-gray-300 mt-4">Manage your parking system with ease</p>
                </div>

                <!-- Settings Dropdown and Navigation Links -->
                <div class="flex items-center">
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
                            Dashboard
                        </NavLink>
                        <NavLink :href="route('admin.overview')" :active="route().current('admin.overview')">
                            Overview
                        </NavLink>
                        <NavLink :href="route('admin.parking-bookings.index')" :active="route().current('admin.parking-bookings.index')">
                            Parking Plan
                        </NavLink>
                        <NavLink :href="route('admin.users')" :active="route().current('admin.users')">
                            Users
                        </NavLink>
                    </div>
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        Admin
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('admin.profile.edit')"> Profile </DropdownLink>
                                <DropdownLink :href="route('admin.logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>

            <!-- Table Section (Bookings Table) -->
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
        </div>
    </div>
</template>