<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Analytics & Reports</h1>
        <p class="text-gray-600 mt-1">View business performance and key metrics</p>
      </div>
      
      <!-- Date Filter Dropdown -->
      <div class="mt-4 md:mt-0 w-full md:w-64">
        <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1">
          Time Period
        </label>
        <CustomInput
          id="date-filter"
          type="select"
          v-model="chosenDate"
          @change="onDatePickerChange"
          :select-options="dateOptions"
          class="shadow-sm"
        />
      </div>
    </div>
    
    
    
    <!-- Report Content -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
      </div>
      <div v-else>
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <!-- Card #1 -->
          <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-6 py-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                <svg
                  class="h-6 w-6 text-indigo-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <!-- Toggle icon based on active tab -->
                  <path
                    v-if="isActive('reports.orders')"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                  />
                  <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857
                       M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                       M7 20H2v-2a3 3 0 015.356-1.857
                       M7 20v-2c0-.656.126-1.283.356-1.857m0 0
                       a5.002 5.002 0 019.288 0
                       M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    Total {{ isActive('reports.orders') ? 'Orders' : 'Customers' }}
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      {{ getTotalCount() }}
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
          
          <!-- Card #2 -->
          <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-6 py-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                <svg
                  class="h-6 w-6 text-green-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ isActive('reports.orders') ? 'Average Order Value' : 'Growth Rate' }}
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      {{ getAverageMetric() }}
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
          
          <!-- Card #3 -->
          <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-6 py-4">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                <svg
                  class="h-6 w-6 text-blue-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0
                       00-2 2v6a2 2 0 002 2h2a2 2 0
                       002-2zm0 0V9a2 2 0 012-2h2a2 2 0
                       012 2v10m-6 0a2 2 0 002 2h2a2 2 0
                       002-2m0 0V5a2 2 0 012-2h2a2 2 0
                       012 2v14a2 2 0 01-2 2h-2a2 2 0
                       01-2-2z"
                  />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ getPeriodText() }}
                  </dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">
                      {{ getComparisonMetric() }}
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Chart -->
        <div class="relative">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">
              {{ isActive('reports.orders') ? 'Orders' : 'Customers' }} Over Time
            </h2>
            <div class="flex space-x-2">
              <button
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm
                       text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="downloadCSV"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-1"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0
                       003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                  />
                </svg>
                Export
              </button>
            </div>
          </div>
          
          <!-- Child route outlet for the chart/graph -->
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "../../axios.js";
import CustomInput from "../../components/core/CustomInput.vue";

// Access your Vuex store
const store = useStore();

// Access the router and current route
const router = useRouter();
const route = useRoute();

/**
 * If you already have date options in your database or store, 
 * you can fetch them there. This computed simply returns 
 * `store.state.dateOptions`. Remove or modify as needed.
 */
const dateOptions = computed(() => {
  return store.state.dateOptions || [];
});

// The chosen date range is stored in route.params.date; default to 'all' if none
const chosenDate = ref(route.params.date || "all");

// Loading state
const loading = ref(false);

/**
 * Summary data for the top metrics cards.
 * Initialize to zero or empty; the real data will come from 
 * your backend when fetchSummaryData() is called.
 */
const summaryData = ref({
  orders: {
    total: 0,
    average: 0,
    comparison: 0
  },
  customers: {
    total: 0,
    growth: 0,
    comparison: 0
  }
});

/**
 * Watch the route for changes and update chosenDate accordingly. 
 * Then fetch new data from your backend for that date range.
 */
watch(
  () => route.params.date,
  (newVal) => {
    if (newVal) {
      chosenDate.value = newVal;
      fetchSummaryData();
    }
  },
  { immediate: true }
);

/**
 * Triggered when the <CustomInput> for date range changes.
 * Navigates to the same route (orders/customers) but with new date param.
 */
function onDatePickerChange() {
  loading.value = true;
  router
    .push({ name: route.name, params: { ...route.params, date: chosenDate.value } })
    .finally(() => {
      loading.value = false;
    });
}

/**
 * Utility to see if the current route matches a name.
 */
function isActive(routeName) {
  return route.name === routeName;
}

/**
 * Fetch summary data from your real database via an API.
 * This example calls '/reports/summary'—replace with your real endpoint.
 */
function fetchSummaryData() {
  loading.value = true;
  axiosClient
    .get('/reports/summary', {
      params: {
        date_range: route.params.date || 'all'
      }
    })
    .then(({ data }) => {
      if (data) {
        summaryData.value = data;
      }
    })
    .catch(error => {
      console.error("Error fetching summary data:", error);
    })
    .finally(() => {
      loading.value = false;
    });
}

/**
 * Dynamically return the total count for Orders or Customers
 */
function getTotalCount() {
  if (isActive("reports.orders")) {
    return summaryData.value.orders.total?.toLocaleString() ?? "0";
  }
  return summaryData.value.customers.total?.toLocaleString() ?? "0";
}

/**
 * Dynamically return the second metric: 
 * - Orders: Average Order Value
 * - Customers: Growth Rate
 */
function getAverageMetric() {
  if (isActive("reports.orders")) {
    return `$${summaryData.value.orders.average?.toFixed(2) ?? "0.00"}`;
  }
  return `+${summaryData.value.customers.growth?.toFixed(1) ?? "0.0"}%`;
}

/**
 * Dynamically return the chosen period text (all, 30d, 60d, etc.).
 * Customize or remove this mapping if you get real text from your database/store.
 */
function getPeriodText() {
  const periodMap = {
    all: "All Time",
    "30d": "Last 30 Days",
    "60d": "Last 60 Days",
    "90d": "Last Quarter",
    "12m": "Last Year",
  };
  return periodMap[chosenDate.value] || "Selected Period";
}

/**
 * Dynamically return the third card metric:
 * - Orders: % comparison
 * - Customers: % comparison
 */
function getComparisonMetric() {
  if (isActive("reports.orders")) {
    return `+${summaryData.value.orders.comparison?.toFixed(1) ?? "0.0"}%`;
  }
  return `+${summaryData.value.customers.comparison?.toFixed(1) ?? "0.0"}%`;
}

/**
 * Example download CSV function. 
 * Customize the endpoint and file name as needed for your backend.
 */
function downloadCSV() {
  const reportType = isActive("reports.orders") ? "orders" : "customers";
  
  axiosClient({
    url: `/reports/${reportType}/export`,
    method: 'GET',
    params: {
      date_range: route.params.date || 'all'
    },
    responseType: 'blob'
  })
    .then(response => {
      const blob = new Blob([response.data], { type: 'text/csv' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = url;
      a.download = `${reportType}_report_${new Date().toISOString().split('T')[0]}.csv`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
    })
    .catch(error => {
      console.error("Error downloading CSV:", error);
      alert("Failed to download CSV. Please try again.");
    });
}

// Fetch data immediately when component mounts
onMounted(() => {
  fetchSummaryData();
});
</script>

<style scoped>
/* Add or modify styles as needed */
</style>
