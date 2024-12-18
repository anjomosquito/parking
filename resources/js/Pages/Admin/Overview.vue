<template>
    <AdminAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Overview</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Today's Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Parking Spaces</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            {{ todayStats.occupied_spaces }} / {{ todayStats.total_spaces }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">Currently Occupied</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Today's Bookings</h3>
                        <p class="text-3xl font-bold text-green-600">
                            {{ todayStats.total_bookings_today }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">New Bookings Today</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Users</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ todayStats.active_users }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">Users with Active Bookings</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Daily Revenue</h3>
                        <p class="text-3xl font-bold text-yellow-600">
                            ${{ todayStats.daily_revenue.toFixed(2) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">Revenue Today</p>
                    </div>
                </div>

                <!-- Weekly Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Weekly Bookings</h3>
                    <div class="h-64">
                        <canvas ref="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    todayStats: {
        type: Object,
        required: true
    },
    weeklyStats: {
        type: Array,
        required: true
    }
});

const weeklyChart = ref(null);

onMounted(() => {
    const ctx = weeklyChart.value.getContext('2d');
    
    const dates = props.weeklyStats.map(stat => stat.date);
    const bookings = props.weeklyStats.map(stat => stat.total_bookings);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Number of Bookings',
                data: bookings,
                borderColor: 'rgb(79, 70, 229)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
