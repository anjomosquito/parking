<template>

    <Head title="Users" />

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
                        <span class="text-yellow-400">User</span>
                        <span class="text-white">Management</span>
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

            <!-- Table Section (Users Table) -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                #</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Vehicle</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Parking</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>


                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Bookings</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(user, index) in users.data" :key="user.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1
                                                }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                                <div class="text-sm text-gray-500">{{ user.email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ user.latest_booking?.car_type || 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ user.latest_booking?.parking_slot.slot_number || 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span v-if="user.latest_booking"
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                    :class="getStatusColor(user.latest_booking.status)">
                                                    {{ user.latest_booking.status }}
                                                </span>
                                                <span v-else>N/A</span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button @click="showUserBookings(user)"
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    View Bookings
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                <div class="flex justify-between">
                                    <div v-if="users.prev_page_url">
                                        <Link :href="users.prev_page_url"
                                            class="px-3 py-2 bg-white border rounded-md text-sm">
                                        Previous
                                        </Link>
                                    </div>
                                    <div v-if="users.next_page_url">
                                        <Link :href="users.next_page_url"
                                            class="px-3 py-2 bg-white border rounded-md text-sm">
                                        Next
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Booking</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this booking? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <Link v-if="selectedBooking" :href="route('admin.parking-bookings.destroy', selectedBooking.id)"
                        method="delete" as="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Delete
                    </Link>
                    <button @click="closeDeleteModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <UserBookingsModal :show="showBookingsModal" :bookings="selectedUserBookings" @close="showBookingsModal = false" />

</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref } from 'vue';
import UserBookingsModal from '@/Components/UserBookingsModal.vue';
import axios from 'axios';

const props = defineProps({
    users: {
        type: Object,
        required: true
    }
});

const showDeleteModal = ref(false);
const selectedBooking = ref(null);
const showBookingsModal = ref(false);
const selectedUserBookings = ref([]);

const confirmDelete = (booking) => {
    selectedBooking.value = booking;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    selectedBooking.value = null;
    showDeleteModal.value = false;
};

const showUserBookings = async (user) => {
    try {
        const response = await axios.get(route('api.user.bookings', user.id));
        selectedUserBookings.value = response.data;
        showBookingsModal.value = true;
    } catch (error) {
        console.error('Error fetching user bookings:', error);
    }
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        completed: 'bg-gray-100 text-gray-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<style>
.bg-hero-pattern {
    background-image: url('/Back.png');
}

.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.button,
input,
select {
    transition: all 0.2s ease-in-out;
}

/* Focus styles */
button:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #111827;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #4B5563;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6B7280;
}
</style>
