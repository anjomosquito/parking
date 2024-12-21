<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

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
  const action = newStatus === 'approved' ? 'approve' : 'reject';

  Swal.fire({
    title: `Are you sure you want to ${action} this booking?`,
    text: `This action will ${newStatus === 'approved' ? 'approve' : 'reject'} the booking.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: `Yes, ${action} it!`,
    cancelButtonText: 'Cancel',
  }).then((result) => {
    if (result.isConfirmed) {
      form.status = newStatus;
      form.patch(route('admin.parking-bookings.update-status', bookingId), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire(
            `${newStatus === 'approved' ? 'Approved!' : 'Rejected!'}`,
            `The booking has been ${newStatus === 'approved' ? 'approved' : 'rejected'}.`,
            'success'
          );
          form.reset();
        },
        onError: () => {
          Swal.fire(
            'Error',
            'An error occurred while updating the status. Please try again.',
            'error'
          );
        },
      });
    }
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
      parking_slot: booking.parking_slot,
      amount: booking.amount
    };
  });
});

const downloadPDF = () => {
  const doc = new jsPDF();
  doc.setFontSize(18);
  doc.text('Parking Bookings Report', 14, 22);
  doc.setFontSize(11);
  doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 30);
  doc.autoTable({
    startY: 40,
    head: [['User', 'Car Type', 'Parking Slot', 'Check-In', 'Check-Out', 'Status', 'Amount']],
    body: formattedBookings.value.map(booking => [
      booking.user,
      booking.carType,
      booking.parking_slot.slot_number,
      `${booking.checkIn.date} ${booking.checkIn.time}`,
      `${booking.checkOut.date} ${booking.checkOut.time}`,
      booking.status,
      booking.amount
    ]),
    theme: 'grid',
    headStyles: { fillColor: [41, 128, 185], textColor: [255, 255, 255] },
    footStyles: { fillColor: [41, 128, 185], textColor: [255, 255, 255] },
  });
  doc.save('parking_bookings_report.pdf');
};

const downloadExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(formattedBookings.value.map(booking => ({
    User: booking.user,
    'Car Type': booking.carType,
    'Parking Slot': booking.parking_slot.slot_number,
    'Check-In': `${booking.checkIn.date} ${booking.checkIn.time}`,
    'Check-Out': `${booking.checkOut.date} ${booking.checkOut.time}`,
    Status: booking.status,
    Amount: booking.amount
  })));

  // Add a title row
  XLSX.utils.sheet_add_aoa(worksheet, [['Parking Bookings Report']], { origin: 'A1' });
  XLSX.utils.sheet_add_aoa(worksheet, [['Generated on: ' + new Date().toLocaleDateString()]], { origin: 'A2' });

  // Merge cells for the title
  worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 6 } }];

  // Set header styles
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
    const cellAddress = XLSX.utils.encode_cell({ r: 2, c: C });
    if (!worksheet[cellAddress]) continue;
    worksheet[cellAddress].s = {
      font: { bold: true },
      fill: { fgColor: { rgb: "2980B9" } },
      alignment: { horizontal: "center" }
    };
  }

  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Bookings');
  XLSX.writeFile(workbook, 'parking_bookings_report.xlsx');
};
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
            <NavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')"
              class="text-yellow-500 text-lg">
              Dashboard
            </NavLink>

            <NavLink :href="route('admin.overview')" :active="route().current('admin.overview')"
              class="text-yellow-500 text-lg">
              Overview
            </NavLink>

            <NavLink :href="route('admin.parking-bookings.index')"
              :active="route().current('admin.parking-bookings.index')" class="text-yellow-500 text-lg">
              Parking Plan
            </NavLink>

            <NavLink :href="route('admin.users')" :active="route().current('admin.users')"
              class="text-yellow-500 text-lg">
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
                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                      fill="currentColor">
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
                <div class="flex justify-end mb-4">
                  <button @click="downloadPDF"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">
                    Download PDF
                  </button>
                  <button @click="downloadExcel"
                    class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 transition duration-200 ml-2">
                    Download Excel
                  </button>
                </div>
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          User
                        </th>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Car Type
                        </th>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Parking Slot
                        </th>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Check-In
                        </th>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Check-Out
                        </th>
                        <th
                          class="w-[10%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Status
                        </th>
                        <th
                          class="w-[10%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Amount
                        </th>
                        <th
                          class="w-[15%] px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="booking in formattedBookings" :key="booking.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-base">{{ booking.user }}</td>
                        <td class="px-6 py-4 text-base">{{ booking.carType }}</td>
                        <td class="px-6 py-4 text-base">{{ booking.parking_slot.slot_number }}
                        </td>
                        <td class="px-6 py-4 text-base">
                          {{ booking.checkIn.date }} {{ booking.checkIn.time }}
                        </td>
                        <td class="px-6 py-4 text-base">
                          {{ booking.checkOut.date }} {{ booking.checkOut.time }}
                        </td>
                        <td class="px-6 py-4">
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
                        <td class="px-6 py-4 text-base">{{ booking.amount }}</td>
                        <td class="px-6 py-4 text-sm">
                          <div class="flex space-x-2">
                            <button v-if="booking.status === 'pending'" @click="updateStatus(booking.id, 'approved')"
                              class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">
                              Approve
                            </button>
                            <button v-if="booking.status === 'pending'" @click="updateStatus(booking.id, 'rejected')"
                              class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">
                              Reject
                            </button>
                            <button v-if="booking.status === 'approved'" @click="updateStatus(booking.id, 'completed')"
                              class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600">
                              Mark Complete
                            </button>
                          </div>
                        </td>
                      </tr>
                      <tr v-if="formattedBookings.length === 0">
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 text-lg">
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
  </div>
</template>