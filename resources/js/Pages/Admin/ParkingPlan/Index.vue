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
            carType: booking.car_type || 'N/A',
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
                        <NavLink :href="route('admin.parking-bookings.index')"
                            :active="route().current('admin.parking-bookings.index')">
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
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        Admin
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
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
                <div class="w-full mx-auto px-4">
                    <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg">
                        <div class="p-8 text-gray-900">
                            <h2 class="text-3xl font-bold mb-8">Parking Bookings</h2>
                            <div class="bg-white rounded-lg">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="w-[15%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                User</th>
                                            <th class="w-[12%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Car Type</th>
                                            <th class="w-[10%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Parking Slot</th>
                                            <th class="w-[15%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Check In</th>
                                            <th class="w-[15%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Check Out</th>
                                            <th class="w-[13%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Status</th>
                                            <th class="w-[20%] px-8 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="booking in formattedBookings" :key="booking.id" class="hover:bg-gray-50">
                                            <td class="px-8 py-6 text-base">{{ booking.user }}</td>
                                            <td class="px-8 py-6 text-base">{{ booking.carType }}</td>
                                            <td class="px-8 py-6 text-base">{{ booking.parking_slot.slot_number }}</td>
                                            <td class="px-8 py-6 text-base">
                                                {{ booking.checkIn.date }} {{ booking.checkIn.time }}
                                            </td>
                                            <td class="px-8 py-6 text-base">
                                                {{ booking.checkOut.date }} {{ booking.checkOut.time }}
                                            </td>
                                            <td class="px-8 py-6">
                                                <span :class="{
                                                    'px-4 py-2 text-sm font-medium rounded-full': true,
                                                    'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                                                    'bg-green-100 text-green-800': booking.status === 'approved',
                                                    'bg-red-100 text-red-800': booking.status === 'rejected',
                                                    'bg-gray-100 text-gray-800': booking.status === 'completed'
                                                }">
                                                    {{ booking.status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-6 text-sm">
                                                <div class="flex space-x-2">
                                                    <button v-if="booking.status === 'pending'"
                                                        @click="updateStatus(booking.id, 'approved')"
                                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Approve
                                                    </button>
                                                    <button v-if="booking.status === 'pending'"
                                                        @click="updateStatus(booking.id, 'rejected')"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Reject
                                                    </button>
                                                    <button v-if="booking.status === 'approved'"
                                                        @click="updateStatus(booking.id, 'completed')"
                                                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Mark Complete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="formattedBookings.length === 0">
                                            <td colspan="7" class="px-8 py-6 text-center text-gray-500 text-lg">
                                                No bookings found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Links -->
                            <div class="mt-8" v-if="bookings.links && bookings.links.length > 3">
                                <div class="flex justify-center gap-4">
                                    <template v-for="(link, index) in bookings.links" :key="index">
                                        <Link v-if="link.url" :href="link.url" v-html="link.label"
                                            class="px-4 py-2 rounded-md border text-base" :class="{
                                                'bg-blue-500 text-white border-blue-600': link.active,
                                                'text-gray-600 hover:bg-gray-100 border-gray-300': !link.active
                                            }" />
                                        <span v-else v-html="link.label" class="px-4 py-2 text-gray-400 text-base" />
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