<template>

  <Head title="Admin Overview" />

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
            <span class="text-white">OVERVIEW</span>
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

      <!-- Parking Occupancy Section -->
      <div class="bg-white rounded-lg p-8 shadow-md mb-8 w-full">
        <h3 class="text-xl font-bold mb-4">Parking Occupancy</h3>
        <div class="grid grid-cols-10 gap-2">
          <template v-for="i in 50" :key="i">
            <button :class="[
              'py-2 px-4 rounded font-bold text-sm',
              parkingSlots.includes(`A${i}`)
                ? 'bg-red-500 text-white hover:bg-red-600'
                : 'bg-green-500 text-white hover:bg-green-600'
            ]" @click="toggleSlot(i)">
              A{{ i }}
            </button>
          </template>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg p-6 shadow-md flex flex-col items-center justify-center w-64 h-64">
          <h3 class="text-xl font-bold mb-4">Parking Lots Status</h3>
          <div class="flex flex-col items-center">
            
            <div>
              <p class="text-sm text-gray-600">Occupied</p>
              <span class="text-4xl text-yellow-400 font-bold">{{ todayStats.total_spaces }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-md flex flex-col items-center justify-center w-64 h-64">
          <h3 class="text-xl font-bold mb-4">Total Customers Today</h3>
          <span class="text-4xl text-yellow-400 font-bold">{{ todayStats.total_bookings_today }}</span>
        </div>
      </div>

      <!-- User List Table -->
      <div class="mt-8">
        <div class="bg-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Registered Users</h3>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    User
                    Info</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Contact</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bookings</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Latest
                    Booking</th>
                  <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in activeUsers" :key="user.id">
                  <!-- User Info -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">Joined {{ new Date(user.created_at).toLocaleDateString() }}</div>
                  </td>

                  <!-- Contact -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ user.email }}</div>
                    <div class="text-sm text-gray-500">{{ user.phone }}</div>
                  </td>

                  <!-- Bookings -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">Total: {{ user.total_bookings }}</div>
                    <div class="text-sm text-gray-500">Approved: {{ user.approved_bookings }}</div>
                    <div class="text-sm text-gray-500">Success Rate: {{ user.booking_rate }}%</div>
                  </td>

                  <!-- Latest Booking -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="user.latest_booking" class="text-sm">
                      <div class="text-gray-900">{{ user.latest_booking.car_type }}</div>
                      <div class="text-gray-500">From: {{ new Date(user.latest_booking.start_time).toLocaleString() }}
                      </div>
                      <div class="text-gray-500">To: {{ new Date(user.latest_booking.end_time).toLocaleString() }}</div>
                    </div>
                    <div v-else class="text-sm text-gray-500">No bookings yet</div>
                  </td>

                  <!-- Status -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span v-if="user.is_active"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Currently
                      Active</span>
                    <span v-else-if="user.latest_booking" :class="[
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                      user.latest_booking.status === 'approved'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-yellow-100 text-yellow-800'
                    ]">
                      {{ user.latest_booking.status }}
                    </span>
                    <span v-else
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script setup>
import { Head } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { ref } from 'vue';

defineProps({
  todayStats: {
    type: Object,
    default() {
      return {};
    }
  },
  weeklyStats: {
    type: Array,
    default() {
      return [];
    }
  },
  activeUsers: {
    type: Array,
    default() {
      return [];
    }
  },
  parkingSlots: {
    type: Array,
    default() {
      return [];
    }
  }
});

const toggleSlot = (slotNumber) => {
  // This is just visual feedback - actual state is managed by the backend
  const slot = `A${slotNumber}`;
  console.log(`Slot ${slot} clicked`);
};
</script>

<style>
.bg-hero-pattern {
  background-image: url('Back.png');
}
</style>
