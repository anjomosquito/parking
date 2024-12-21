<template>
    <Modal :show="show" @close="closeModal">
      <div class="relative p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
  
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">User Bookings History</h2>
        
        <div class="flex justify-end mb-4">
          <button @click="generatePDF" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
            </svg>
            Download PDF
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
              <tr>
                <th class="border border-gray-300 px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                  Car Type
                </th>
                <th class="border border-gray-300 px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                  Slot
                </th>
                <th class="border border-gray-300 px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                  Check In
                </th>
                <th class="border border-gray-300 px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                  Check Out
                </th>
                <th class="border border-gray-300 px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">
                  Status
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50">
                <td class="border border-gray-300 px-6 py-4 text-sm text-gray-800 capitalize">
                  {{ booking.car_type }}
                </td>
                <td class="border border-gray-300 px-6 py-4 text-sm text-gray-800">
                  {{ booking.parking_slot?.slot_number || 'N/A' }}
                </td>
                <td class="border border-gray-300 px-6 py-4 text-sm text-gray-800">
                  {{ formatDateTime(booking.start_time) }}
                </td>
                <td class="border border-gray-300 px-6 py-4 text-sm text-gray-800">
                  {{ formatDateTime(booking.end_time) }}
                </td>
                <td class="border border-gray-300 px-6 py-4">
                  <span :class="statusClass(booking.status)">
                    {{ booking.status }}
                  </span>
                </td>
              </tr>
              <tr v-if="bookings.length === 0">
                <td colspan="5" class="border border-gray-300 px-6 py-4 text-center text-gray-500">
                  No bookings found
                </td>
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
  import jsPDF from 'jspdf';
  
  const props = defineProps({
    show: Boolean,
    bookings: {
      type: Array,
      default: () => [],
    },
    user: {
      type: Object,
      required: true
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
  
  const statusClass = (status) => {
    return {
      'px-2 py-1 text-xs font-medium rounded-full': true,
      'bg-yellow-100 text-yellow-800': status === 'pending',
      'bg-green-100 text-green-800': status === 'approved',
      'bg-red-100 text-red-800': status === 'rejected',
      'bg-gray-100 text-gray-800': status === 'completed',
    };
  };

  const generatePDF = () => {
    const doc = new jsPDF();
    
    // Add title and user info
    doc.setFontSize(20);
    doc.text('User Bookings History', 14, 20);
    
    // Add user details
    doc.setFontSize(12);
    doc.text(`Customer: ${props.user.name}`, 14, 30);
    doc.text(`Email: ${props.user.email}`, 14, 37);
    doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 14, 44);

    // Add table
    doc.autoTable({
      startY: 50,
      columns: [
        { header: 'Car Type', dataKey: 'car_type' },
        { header: 'Slot', dataKey: 'slot' },
        { header: 'Check In', dataKey: 'check_in' },
        { header: 'Check Out', dataKey: 'check_out' },
        { header: 'Status', dataKey: 'status' },
      ],
      body: props.bookings.map((booking) => ({
        car_type: booking.car_type,
        slot: booking.parking_slot?.slot_number || 'N/A',
        check_in: formatDateTime(booking.start_time),
        check_out: formatDateTime(booking.end_time),
        status: booking.status,
      })),
      theme: 'grid',
      styles: { fontSize: 8 },
      headStyles: { fillColor: [41, 128, 185], textColor: 255 },
      alternateRowStyles: { fillColor: [245, 245, 245] }
    });
    
    // Save with user name in filename
    doc.save(`bookings_${props.user.name.replace(/\s+/g, '_').toLowerCase()}.pdf`);
  };
  </script>
  
  <style scoped>
  .table-header {
    background-color: #f9fafb;
  }
  </style>