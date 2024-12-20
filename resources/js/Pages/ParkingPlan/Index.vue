<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

// Accept props passed from the controller
const props = defineProps({
    bookingDetails: Object,
    bookings: Object
});

const downloadPDF = () => {
    const doc = new jsPDF();
    doc.text('Parking Bookings Report', 20, 20);
    doc.autoTable({
        head: [['User', 'Car Type', 'Parking Slot', 'Check-In', 'Check-Out', 'Status', 'Amount']],
        body: props.bookings.data.map(booking => [
            booking.user,
            booking.carType,
            booking.parking_slot.slot_number,
            `${booking.checkIn.date} ${booking.checkIn.time}`,
            `${booking.checkOut.date} ${booking.checkOut.time}`,
            booking.status,
            booking.amount
        ]),
    });
    doc.save('parking_bookings_report.pdf');
};

const downloadExcel = () => {
    const worksheet = XLSX.utils.json_to_sheet(props.bookings.data.map(booking => ({
        User: booking.user,
        'Car Type': booking.carType,
        'Parking Slot': booking.parking_slot.slot_number,
        'Check-In': `${booking.checkIn.date} ${booking.checkIn.time}`,
        'Check-Out': `${booking.checkOut.date} ${booking.checkOut.time}`,
        Status: booking.status,
        Amount: booking.amount
    })));
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Bookings');
    XLSX.writeFile(workbook, 'parking_bookings_report.xlsx');
};
</script>

<template>
    <div>

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
                            <span class="text-yellow-400">PARKING</span>
                            <span class="text-white">PLAN</span>
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
                        <div class="ml-3">
                            <button @click="downloadPDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Download PDF Report
                            </button>
                            <button @click="downloadExcel" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Download Excel Report
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mb-4">
                    <button @click="downloadPDF" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">
                        Download PDF
                    </button>
                    <button @click="downloadExcel" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 transition duration-200 ml-2">
                        Download Excel
                    </button>
                </div>

                <!-- Display booking information -->
                <div v-if="bookingDetails">
                    <p>Car Type: {{ bookingDetails.carType }}</p>
                    <p>Check-In: {{ bookingDetails.checkIn.date }} at {{ bookingDetails.checkIn.time }}</p>
                    <p>Check-Out: {{ bookingDetails.checkOut.date }} at {{ bookingDetails.checkOut.time }}</p>
                    <p v-if="bookingDetails.promoCode">Promo Code: {{ bookingDetails.promoCode }}</p>
                </div>
            </div>
        </div>
    </div>
</template>