<template>
    <Modal :show="show" @close="closeModal">
      <div class="relative p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
  
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">User Bookings History</h2>
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
  
  const props = defineProps({
    show: Boolean,
    bookings: {
      type: Array,
      default: () => [],
    },
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
  </script>
  
  <style scoped>
  .table-header {
    background-color: #f9fafb;
  }
  </style>
  