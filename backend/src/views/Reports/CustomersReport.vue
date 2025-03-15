<template>
  <div>
    <!-- TOP METRICS -->
    <div class="mb-8">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Lifetime Value -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              Customer Lifetime Value
            </div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">
              ${{ metrics.lifetimeValue }}
            </div>
            <div
              class="mt-2 text-sm flex items-center"
              :class="metrics.lifetimeValueChange >= 0 ? 'text-green-600' : 'text-red-600'"
            >
              <svg
                v-if="metrics.lifetimeValueChange >= 0"
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
                  d="M5 10l7-7m0 0l7 7m-7-7v18"
                />
              </svg>
              <svg
                v-else
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
                  d="M19 14l-7 7m0 0l-7-7m7 7V3"
                />
              </svg>
              {{ Math.abs(metrics.lifetimeValueChange) }}% from last period
            </div>
          </div>
        </div>

        <!-- Retention Rate -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              Retention Rate
            </div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">
              {{ metrics.retentionRate }}%
            </div>
            <div
              class="mt-2 text-sm flex items-center"
              :class="metrics.retentionRateChange >= 0 ? 'text-green-600' : 'text-red-600'"
            >
              <svg
                v-if="metrics.retentionRateChange >= 0"
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
                  d="M5 10l7-7m0 0l7 7m-7-7v18"
                />
              </svg>
              <svg
                v-else
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
                  d="M19 14l-7 7m0 0l-7-7m7 7V3"
                />
              </svg>
              {{ Math.abs(metrics.retentionRateChange) }}% from last period
            </div>
          </div>
        </div>

        <!-- New Customers -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              New Customers
            </div>
            <div class="mt-2 text-2xl font-semibold text-gray-900">
              {{ metrics.newCustomers }}
            </div>
            <div
              class="mt-2 text-sm flex items-center"
              :class="metrics.newCustomersChange >= 0 ? 'text-green-600' : 'text-red-600'"
            >
              <svg
                v-if="metrics.newCustomersChange >= 0"
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
                  d="M5 10l7-7m0 0l7 7m-7-7v18"
                />
              </svg>
              <svg
                v-else
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
                  d="M19 14l-7 7m0 0l-7-7m7 7V3"
                />
              </svg>
              {{ Math.abs(metrics.newCustomersChange) }}% from last period
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CHART SECTION -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Acquisition Trends</h3>

      <!-- CHART CONTROLS -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="space-x-2">
          <button
            v-for="(type, index) in chartTypes"
            :key="index"
            @click="activeChartType = type.value"
            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors"
            :class="activeChartType === type.value
              ? 'bg-indigo-100 text-indigo-700'
              : 'text-gray-600 hover:bg-gray-100'"
          >
            {{ type.label }}
          </button>
        </div>

        <div class="flex items-center space-x-4">
          <label class="text-sm text-gray-600 whitespace-nowrap">Group by:</label>
          <select
            v-model="groupBy"
            class="appearance-none bg-white border border-gray-300 text-gray-700 py-1.5 px-3 pr-8
                   rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 
                   focus:border-indigo-500 text-sm"
          >
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
          </select>
        </div>
      </div>

      <!-- LOADING INDICATOR -->
      <div v-if="loading" class="flex justify-center items-center h-80">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
      </div>

      <!-- ERROR STATE -->
      <div v-else-if="error" class="flex flex-col justify-center items-center h-80">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-600 text-lg">{{ errorMessage }}</p>
        <button @click="getData" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
          Try Again
        </button>
      </div>

      <!-- CHART AREA -->
      <div v-else-if="hasChartData" class="h-80 w-full mb-4">
        <LineChart
          v-if="activeChartType === 'line'"
          :data="chartData"
          :height="320"
        />
        <BarChart
          v-else
          :data="chartData"
          :height="320"
        />
      </div>

      <!-- NO DATA STATE -->
      <div v-else class="flex justify-center items-center h-80">
        <p class="text-gray-500 text-lg">No data available for the selected period</p>
      </div>

      <!-- LEGEND -->
      <div v-if="hasChartData" class="flex justify-center space-x-8">
        <div class="flex items-center">
          <div class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></div>
          <span class="text-sm text-gray-600">New Customers</span>
        </div>
        <div class="flex items-center">
          <div class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></div>
          <span class="text-sm text-gray-600">Returning Customers</span>
        </div>
      </div>
    </div>

    <!-- CUSTOMER SEGMENTS -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-5">Customer Segments</h3>
      
      <!-- SEGMENTS LOADING -->
      <div v-if="loading" class="flex justify-center items-center h-40">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
      </div>
      
      <!-- SEGMENTS ERROR -->
      <div v-else-if="error" class="flex justify-center items-center h-40">
        <p class="text-gray-500">Unable to load segment data</p>
      </div>
      
      <!-- SEGMENTS NO DATA -->
      <div v-else-if="!segments.length" class="flex justify-center items-center h-40">
        <p class="text-gray-500">No segment data available</p>
      </div>
      
      <!-- SEGMENTS DATA -->
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(segment, index) in segments"
          :key="index"
          class="bg-gray-50 rounded-lg p-5 hover:shadow-md transition-shadow"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div
                class="h-10 w-10 rounded-full flex items-center justify-center"
                :class="segment.bgColor"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  :class="segment.iconColor"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <!-- Icon for customers -->
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                       c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                       a3 3 0 015.356-1.857M7 20v-2
                       c0-.656.126-1.283.356-1.857m0 0
                       a5.002 5.002 0 019.288 0
                       M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3
                       a2 2 0 11-4 0 2 2 0 014 0zM7 10
                       a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <h4 class="text-md font-medium text-gray-900">
                  {{ segment.name }}
                </h4>
                <p class="text-sm text-gray-500">{{ segment.description }}</p>
              </div>
            </div>
            <div class="text-xl font-semibold text-gray-900">
              {{ segment.count }}
            </div>
          </div>
          <div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div
                class="h-2.5 rounded-full"
                :class="segment.barColor"
                :style="{ width: segment.percentage + '%' }"
              ></div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-gray-500">
              <span>{{ segment.percentage }}% of total</span>
              <span>Avg. spend: ${{ segment.avgSpend }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, reactive, computed } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../../axios.js";

// Import chart components

import LineChart from "../../components/core/Charts/Line.vue";
import BarChart from "../../components/core/Charts/Bar.vue";

const route = useRoute();
const chartData = ref({});
const loading = ref(false);
const error = ref(false);
const errorMessage = ref("Failed to load data");
const activeChartType = ref("line");
const groupBy = ref("day");

// Chart type toggles
const chartTypes = [
  { label: "Line", value: "line" },
  { label: "Bar", value: "bar" },
];

// Metrics
const metrics = reactive({
  lifetimeValue: "0.00",
  lifetimeValueChange: 0,
  retentionRate: 0,
  retentionRateChange: 0,
  newCustomers: 0,
  newCustomersChange: 0,
});

// Segments
const segments = ref([]);

// Computed property to check if chart data exists
const hasChartData = computed(() => {
  return chartData.value && 
         chartData.value.labels && 
         chartData.value.labels.length > 0 &&
         chartData.value.datasets &&
         chartData.value.datasets.length > 0;
});

// Watch for data changes
watch(route, () => getData(), { immediate: true });
watch([activeChartType, groupBy], () => getData());

// Fetch data
function getData() {
  loading.value = true;
  error.value = false;

  axiosClient
    .get("/reports/customers", {
      params: {
        date_range: route.params.date || "all",
        group_by: groupBy.value
      }
    })
    .then(({ data }) => {
      // Reset state before updating with new data
      resetState();
      
      // Update metrics
      if (data.metrics) {
        metrics.lifetimeValue = data.metrics.lifetimeValue || "0.00";
        metrics.lifetimeValueChange = data.metrics.lifetimeValueChange || 0;
        metrics.retentionRate = data.metrics.retentionRate || 0;
        metrics.retentionRateChange = data.metrics.retentionRateChange || 0;
        metrics.newCustomers = data.metrics.newCustomers || 0;
        metrics.newCustomersChange = data.metrics.newCustomersChange || 0;
      }

      // Update segments (new vs. repeat vs. loyal)
      if (data.segments && Array.isArray(data.segments)) {
        segments.value = data.segments.map(segment => ({
          ...segment,
          bgColor:
            segment.type === "new"
              ? "bg-indigo-100"
              : segment.type === "repeat"
              ? "bg-emerald-100"
              : "bg-violet-100",
          iconColor:
            segment.type === "new"
              ? "text-indigo-600"
              : segment.type === "repeat"
              ? "text-emerald-600"
              : "text-violet-600",
          barColor:
            segment.type === "new"
              ? "bg-indigo-500"
              : segment.type === "repeat"
              ? "bg-emerald-500"
              : "bg-violet-500",
        }));
      }

      // Format chart data
      if (data.chartData && Array.isArray(data.chartData) && data.chartData.length > 0) {
        chartData.value = {
          labels: data.chartData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
          }),
          datasets: [
            {
              label: "New Customers",
              backgroundColor: "#6366f1", // indigo-500
              borderColor: "#6366f1",
              data: data.chartData.map(item => item.newCustomers || 0),
            },
            {
              label: "Returning Customers",
              backgroundColor: "#10b981", // emerald-500
              borderColor: "#10b981",
              data: data.chartData.map(item => item.returningCustomers || 0),
            },
          ],
        };
      } else {
        // Clear chart data if none returned
        chartData.value = { labels: [], datasets: [] };
      }
    })
    .catch(err => {
      console.error("Error fetching customer data:", err);
      error.value = true;
      errorMessage.value = err.response?.data?.message || "Failed to load customer data";
      resetState();
    })
    .finally(() => {
      loading.value = false;
    });
}

// Reset state to default values
function resetState() {
  // Clear chart data
  chartData.value = { labels: [], datasets: [] };
  
  // Clear segments
  segments.value = [];
  
  // Reset metrics to default values
  metrics.lifetimeValue = "0.00";
  metrics.lifetimeValueChange = 0;
  metrics.retentionRate = 0;
  metrics.retentionRateChange = 0;
  metrics.newCustomers = 0;
  metrics.newCustomersChange = 0;
}

// Mount
onMounted(() => {
  getData();
});
</script>

<style scoped>
/* Any scoped styles you need */
select {
  /* Add a custom select dropdown style */
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}
</style>