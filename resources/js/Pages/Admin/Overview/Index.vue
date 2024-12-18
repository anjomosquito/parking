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
      <div class="grid grid-cols-2 gap-8">
        <!-- Parking Occupancy -->
        <div class="bg-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Parking Occupancy</h3>
          <button class="bg-yellow-400 text-black font-bold py-2 px-4 rounded">Row 1</button>
        </div>

        <!-- Loyalty Card Customers -->
        <div class="bg-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Loyalty Card Customers</h3>
          <div class="mb-2">
            <span class="block text-sm">MH 27 CA 2305</span>
            <span class="block text-sm">John Paul Cabag</span>
            <span class="block text-green-600">Active</span>
          </div>
          <div class="mb-2">
            <span class="block text-sm">MH 41 WP 7689</span>
            <span class="block text-sm">Esau Orit</span>
            <span class="block text-green-600">Active</span>
          </div>
        </div>

        <!-- Total Customers Today -->
        <div class="bg-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Total Customers Today</h3>
          <span class="text-4xl text-yellow-400 font-bold">74</span>
        </div>

        <!-- Parking Lots Occupied -->
        <div class="bg-white rounded-lg p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Parking Lots Occupied</h3>
          <span class="text-4xl text-yellow-400 font-bold">13/50</span>
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
</script>

<style>
.bg-hero-pattern {
  background-image: url('Back.png');
}
</style>
