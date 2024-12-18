<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Form handling with Inertia
const form = useForm({
    carType: 'Honda Civic',
    checkIn: {
        date: '',
        time: '09:00'
    },
    checkOut: {
        date: '',
        time: '09:00'
    },
    promoCode: '',
    parking_slot: 'A1'
});

// Dropdowns State
const showDropdowns = ref({
    carType: false,
    checkInTime: false,
    checkOutTime: false
});

// Calendar State
const showCalendars = ref({
    checkIn: false,
    checkOut: false
});

// Available Car Types
const carTypes = [
    'Honda Civic',
    'Toyota Camry',
    'Tesla Model 3',
    'BMW 3 Series',
    'Mercedes C-Class'
];

// Time Slots
const timeSlots = [
    '09:00', '10:00', '11:00', '12:00', '13:00', 
    '14:00', '15:00', '16:00', '17:00'
].map(time => ({
    value: time,
    label: formatTime(time)
}));

// Format Functions
function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const hours12 = hours % 12 || 12;
    return `${hours12}:${minutes} ${ampm}`;
}

function formatDate(date) {
    if (!date) return 'Select Date';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
}

// Calendar Functions
const currentDate = new Date();
const currentMonth = ref(currentDate.getMonth());
const currentYear = ref(currentDate.getFullYear());

const daysInMonth = computed(() => {
    const days = [];
    const firstDay = new Date(currentYear.value, currentMonth.value, 1).getDay();
    const lastDate = new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
    
    // Add empty cells for days before first of month
    for (let i = 0; i < firstDay; i++) {
        days.push({ date: null, disabled: true });
    }
    
    // Add days of month
    for (let i = 1; i <= lastDate; i++) {
        const date = new Date(currentYear.value, currentMonth.value, i);
        days.push({
            date,
            disabled: date < currentDate,
            formatted: date.toISOString().split('T')[0]
        });
    }
    
    return days;
});

const monthName = computed(() => {
    return new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' });
});

// Navigation Functions
function previousMonth() {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
}

function nextMonth() {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
}

// Form error handling
const formErrors = computed(() => {
    return {
        checkIn: form.errors['checkIn.date'] || form.errors['checkIn.time'],
        checkOut: form.errors['checkOut.date'] || form.errors['checkOut.time'],
        carType: form.errors.carType,
        promoCode: form.errors.promoCode
    };
});

// Validation Functions
const isValidBooking = computed(() => {
    if (!form.checkIn.date || !form.checkOut.date) return false;
    
    const checkInDate = new Date(`${form.checkIn.date}T${form.checkIn.time}`);
    const checkOutDate = new Date(`${form.checkOut.date}T${form.checkOut.time}`);
    
    return checkOutDate > checkInDate;
});

// Selection Functions
function selectDate(type, date) {
    form[type].date = date;
    showCalendars.value[type] = false;
    
    // If check-in date is after check-out date, clear check-out
    if (type === 'checkIn' && form.checkOut.date) {
        const checkInDate = new Date(date);
        const checkOutDate = new Date(form.checkOut.date);
        if (checkInDate > checkOutDate) {
            form.checkOut.date = '';
        }
    }
}

function selectTime(type, time) {
    form[type].time = time;
    showDropdowns.value[`${type}Time`] = false;
}

function selectCarType(car) {
    form.carType = car;
    showDropdowns.value.carType = false;
}

// Submit Function
const handleSubmit = () => {
    if (!form.checkIn.date || !form.checkOut.date) {
        form.setError('checkIn.date', 'Please select check-in date');
        form.setError('checkOut.date', 'Please select check-out date');
        return;
    }

    form.post(route('parking-bookings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect to the plan page after successful booking
            window.location.href = route('parkingPlan.index');
        },
    });
};

// Click Outside Handler
function closeAllDropdowns(except) {
    Object.keys(showDropdowns.value).forEach(key => {
        if (key !== except) showDropdowns.value[key] = false;
    });
    Object.keys(showCalendars.value).forEach(key => {
        if (key !== except) showCalendars.value[key] = false;
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-black relative" @click="closeAllDropdowns()">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-hero-pattern bg-cover bg-center">
                <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Welcome Text -->
                <div class="mb-16">
                    <h1 class="text-3xl text-white font-medium mb-2">Welcome to</h1>
                    <h2 class="text-6xl font-bold">
                        <span class="text-yellow-400">PARKING</span>
                        <span class="text-white">HUB</span>
                    </h2>
                    <p class="text-xl text-gray-300 mt-4">Parking system with premium service</p>
                </div>

                <!-- Booking Section -->
                <div class="bg-black/60 backdrop-blur-sm rounded-2xl p-8 max-w-6xl mx-auto">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl text-yellow-400 font-bold">Book Now</h3>
                    </div>
                    
                    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Car Type Selection -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">SELECT CAR TYPE</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    :value="form.carType"
                                    readonly
                                    @click.stop="showDropdowns.carType = !showDropdowns.carType"
                                    :class="[
                                        'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                        formErrors.carType ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                    ]"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center px-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                
                                <!-- Car Type Dropdown -->
                                <div v-if="showDropdowns.carType" 
                                     class="absolute z-50 w-full mt-2 bg-black border border-gray-800 rounded-lg shadow-lg">
                                    <div v-for="car in carTypes" 
                                         :key="car"
                                         @click.stop="selectCarType(car)"
                                         :class="[
                                            'px-4 py-3 text-white hover:bg-gray-800 cursor-pointer transition-colors duration-200 first:rounded-t-lg last:rounded-b-lg',
                                            form.carType === car ? 'bg-gray-800' : ''
                                         ]">
                                        {{ car }}
                                    </div>
                                </div>
                            </div>
                            <p v-if="formErrors.carType" class="mt-2 text-sm text-red-500">
                                {{ formErrors.carType }}
                            </p>
                        </div>

                        <!-- Check In -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">CHECK IN</label>
                            <div class="flex gap-3">
                                <div class="relative flex-1">
                                    <input 
                                        type="text" 
                                        :value="formatDate(form.checkIn.date)"
                                        readonly
                                        @click.stop="showCalendars.checkIn = !showCalendars.checkIn"
                                        :class="[
                                            'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                            formErrors.checkIn ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                        ]"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>

                                    <!-- Calendar Dropdown -->
                                    <div v-if="showCalendars.checkIn" 
                                         class="absolute z-50 mt-2 bg-black border border-gray-800 rounded-lg shadow-lg p-6 w-72">
                                        <div class="flex justify-between items-center mb-6">
                                            <button type="button" @click.stop="previousMonth" 
                                                    class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <span class="text-white text-lg font-medium">{{ monthName }} {{ currentYear }}</span>
                                            <button type="button" @click.stop="nextMonth" 
                                                    class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-7 gap-2">
                                            <div v-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" 
                                                 :key="day"
                                                 class="text-center text-gray-400 text-sm font-medium mb-2">
                                                {{ day }}
                                            </div>
                                            <button
                                                v-for="(day, index) in daysInMonth"
                                                :key="index"
                                                type="button"
                                                :disabled="day.disabled"
                                                @click.stop="day.date && selectDate('checkIn', day.formatted)"
                                                :class="[
                                                    'text-center py-2 rounded-md transition-colors duration-200',
                                                    day.date ? 'hover:bg-gray-800' : '',
                                                    day.formatted === form.checkIn.date ? 'bg-yellow-400 text-black font-medium' : 'text-white',
                                                    day.disabled ? 'text-gray-600 cursor-not-allowed' : 'cursor-pointer',
                                                    !day.date ? 'pointer-events-none' : ''
                                                ]"
                                            >
                                                {{ day.date ? day.date.getDate() : '' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative flex-1">
                                    <input 
                                        type="text" 
                                        :value="formatTime(form.checkIn.time)"
                                        readonly
                                        @click.stop="showDropdowns.checkInTime = !showDropdowns.checkInTime"
                                        :class="[
                                            'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                            formErrors.checkIn ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                        ]"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    <!-- Time Dropdown -->
                                    <div v-if="showDropdowns.checkInTime" 
                                         class="absolute z-50 w-full mt-2 bg-black border border-gray-800 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                        <div v-for="slot in timeSlots" 
                                             :key="slot.value"
                                             @click.stop="selectTime('checkIn', slot.value)"
                                             :class="[
                                                'px-4 py-3 text-white hover:bg-gray-800 cursor-pointer transition-colors duration-200',
                                                form.checkIn.time === slot.value ? 'bg-gray-800' : ''
                                             ]">
                                            {{ slot.label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-if="formErrors.checkIn" class="mt-2 text-sm text-red-500">
                                {{ formErrors.checkIn }}
                            </p>
                        </div>

                        <!-- Check Out -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-yellow-400 mb-3">CHECK OUT</label>
                            <div class="flex gap-3">
                                <div class="relative flex-1">
                                    <input 
                                        type="text" 
                                        :value="formatDate(form.checkOut.date)"
                                        readonly
                                        @click.stop="showCalendars.checkOut = !showCalendars.checkOut"
                                        :class="[
                                            'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                            formErrors.checkOut ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                        ]"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>

                                    <!-- Calendar Dropdown -->
                                    <div v-if="showCalendars.checkOut" 
                                         class="absolute z-50 mt-2 bg-black border border-gray-800 rounded-lg shadow-lg p-6 w-72">
                                        <div class="flex justify-between items-center mb-6">
                                            <button type="button" @click.stop="previousMonth" 
                                                    class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <span class="text-white text-lg font-medium">{{ monthName }} {{ currentYear }}</span>
                                            <button type="button" @click.stop="nextMonth" 
                                                    class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-7 gap-2">
                                            <div v-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" 
                                                 :key="day"
                                                 class="text-center text-gray-400 text-sm font-medium mb-2">
                                                {{ day }}
                                            </div>
                                            <button
                                                v-for="(day, index) in daysInMonth"
                                                :key="index"
                                                type="button"
                                                :disabled="day.disabled || (form.checkIn.date && day.formatted < form.checkIn.date)"
                                                @click.stop="day.date && selectDate('checkOut', day.formatted)"
                                                :class="[
                                                    'text-center py-2 rounded-md transition-colors duration-200',
                                                    day.date ? 'hover:bg-gray-800' : '',
                                                    day.formatted === form.checkOut.date ? 'bg-yellow-400 text-black font-medium' : 'text-white',
                                                    day.disabled || (form.checkIn.date && day.formatted < form.checkIn.date) ? 'text-gray-600 cursor-not-allowed' : 'cursor-pointer',
                                                    !day.date ? 'pointer-events-none' : ''
                                                ]"
                                            >
                                                {{ day.date ? day.date.getDate() : '' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative flex-1">
                                    <input 
                                        type="text" 
                                        :value="formatTime(form.checkOut.time)"
                                        readonly
                                        @click.stop="showDropdowns.checkOutTime = !showDropdowns.checkOutTime"
                                        :class="[
                                            'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                            formErrors.checkOut ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                        ]"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    <!-- Time Dropdown -->
                                    <div v-if="showDropdowns.checkOutTime" 
                                         class="absolute z-50 w-full mt-2 bg-black border border-gray-800 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                        <div v-for="slot in timeSlots" 
                                             :key="slot.value"
                                             @click.stop="selectTime('checkOut', slot.value)"
                                             :class="[
                                                'px-4 py-3 text-white hover:bg-gray-800 cursor-pointer transition-colors duration-200',
                                                form.checkOut.time === slot.value ? 'bg-gray-800' : ''
                                             ]">
                                            {{ slot.label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-if="formErrors.checkOut" class="mt-2 text-sm text-red-500">
                                {{ formErrors.checkOut }}
                            </p>
                        </div>

                        <!-- Promo Code -->
                        <div>
                            <label class="block text-sm font-medium text-yellow-400 mb-3">PROMO CODE (optional)</label>
                            <input 
                                type="text" 
                                v-model="form.promoCode"
                                :class="[
                                    'w-full bg-black/80 text-white rounded-lg px-4 py-3 border transition-colors duration-200',
                                    formErrors.promoCode ? 'border-red-500' : 'border-gray-800 hover:border-gray-700'
                                ]"
                                placeholder="Enter promo code"
                            >
                            <p v-if="formErrors.promoCode" class="mt-2 text-sm text-red-500">
                                {{ formErrors.promoCode }}
                            </p>
                        </div>

                        <!-- Book Now Button -->
                        <div class="md:col-span-4 mt-8 flex justify-end">
                            <button 
                                type="submit"
                                class="bg-yellow-400 text-black px-8 py-3 rounded-full font-bold hover:bg-yellow-300 text-lg
                                       transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="form.processing || !isValidBooking"
                            >
                                <span v-if="form.processing">Processing...</span>
                                <span v-else>Book Now</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.bg-hero-pattern {
    background-image: url('Back.png');
}

.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
.button, input, select {
    transition: all 0.2s ease-in-out;
}

/* Hover effects on inputs */
input:hover, input:focus {
    border-color: #4B5563;
    outline: none;
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

/* Calendar transitions */
.calendar-enter-active,
.calendar-leave-active {
    transition: all 0.2s ease-in-out;
}

.calendar-enter-from,
.calendar-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Focus styles */
button:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}

input:focus-visible {
    outline: 2px solid #FBBF24;
    outline-offset: 2px;
}
</style>