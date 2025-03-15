<template>
  <div>
    <!-- METRICS CARDS -->
    <div class="mb-4">
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <!-- Average Order Value -->
        <div class="col-span-1 sm:col-span-2">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              Average Order Value
            </div>
            <div class="mt-1 text-lg font-semibold text-gray-900">
              ${{ metrics.avgOrderValue }}
            </div>
            <div class="mt-1 text-sm flex items-center" :class="metrics.avgOrderValueChange >= 0 ? 'text-green-600' : 'text-red-600'">
              <svg
                v-if="metrics.avgOrderValueChange >= 0"
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
              {{ Math.abs(metrics.avgOrderValueChange) }}% from last period
            </div>
          </div>
        </div>
        <!-- Conversion Rate -->
        <div class="col-span-1">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              Conversion Rate
            </div>
            <div class="mt-1 text-lg font-semibold text-gray-900">
              {{ metrics.conversionRate }}%
            </div>
            <div class="mt-1 text-sm flex items-center" :class="metrics.conversionRateChange >= 0 ? 'text-green-600' : 'text-red-600'">
              <svg
                v-if="metrics.conversionRateChange >= 0"
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
              {{ Math.abs(metrics.conversionRateChange) }}% from last period
            </div>
          </div>
        </div>
        <!-- Total Orders -->
        <div class="col-span-1">
          <div class="flex flex-col">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">
              Total Orders
            </div>
            <div class="mt-1 text-lg font-semibold text-gray-900">
              {{ metrics.totalOrders }}
            </div>
            <div class="mt-1 text-sm flex items-center" :class="metrics.totalOrdersChange >= 0 ? 'text-green-600' : 'text-red-600'">
              <svg
                v-if="metrics.totalOrdersChange >= 0"
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
              {{ Math.abs(metrics.totalOrdersChange) }}% from last period
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- CHART CONTROLS -->
    <div class="flex justify-between items-center mb-6">
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
        <label class="text-sm text-gray-600">Group by:</label>
        <select
          v-model="groupBy"
          class="appearance-none bg-white border border-gray-300 text-gray-700 
                 py-1 px-3 pr-8 rounded-md shadow-sm focus:outline-none 
                 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
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

    <!-- CHART AREA -->
    <div v-else class="h-80 w-full">
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

    <!-- LEGEND -->
    <div class="flex justify-center mt-4 space-x-6">
      <div class="flex items-center">
        <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
        <span class="text-sm text-gray-600">New Orders</span>
      </div>
      <div class="flex items-center">
        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
        <span class="text-sm text-gray-600">Repeat Orders</span>
      </div>
    </div>

    <!-- ORDER SEGMENTS -->
    <div class="mt-8">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Order Segments</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
          v-for="(segment, index) in segments"
          :key="index"
          class="bg-white border border-gray-200 rounded-lg p-4 
                 hover:shadow-md transition-shadow"
        >
          <div class="flex items-center justify-between">
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
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <h4 class="text-md font-medium text-gray-900">{{ segment.name }}</h4>
                <p class="text-sm text-gray-500">{{ segment.description }}</p>
              </div>
            </div>
            <div class="text-xl font-semibold text-gray-900">{{ segment.count }}</div>
          </div>
          <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="h-2 rounded-full"
                :class="segment.barColor"
                :style="{ width: segment.percentage + '%' }"
              ></div>
            </div>
            <div class="mt-2 flex justify-between text-xs text-gray-500">
              <span>{{ segment.percentage }}% of total</span>
              <span>Avg. value: ${{ segment.avgValue }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PRODUCT PERFORMANCE TABLE -->
    <div class="mt-8">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Best Sellers</h3>
      <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Product
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Orders
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Avg. Price
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Total Revenue
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(product, index) in topProducts"
              :key="index"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ product.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                {{ product.orders }} ({{ product.percentage }}%)
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                ${{ product.avgPrice }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                ${{ product.totalRevenue.toLocaleString() }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, reactive, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../../axios.js";

// Make sure these paths match your actual folder/file names
import LineChart from "../../components/core/Charts/Line.vue";
import BarChart from "../../components/core/Charts/Bar.vue";

// Reactive references
const route = useRoute();
const chartData = ref({});
const loading = ref(false);
const activeChartType = ref("line");
const groupBy = ref("day");

// Chart types for toggling
const chartTypes = [
  { label: "Line", value: "line" },
  { label: "Bar", value: "bar" },
];

// Order metrics
const metrics = reactive({
  avgOrderValue: 0,
  avgOrderValueChange: 0,
  conversionRate: 0,
  conversionRateChange: 0,
  totalOrders: 0,
  totalOrdersChange: 0,
});

// Define top products data
const topProducts = ref([]);

// Order segments data
const segments = ref([]);

// Watchers
// Fetch data when route changes or on first load
watch(route, () => getData(), { immediate: true });

// Also refetch data if chart type or grouping changes
watch([activeChartType, groupBy], () => getData());

// Get data from API
function getData() {
  loading.value = true;
  
  axiosClient
    .get('/reports/orders', {
      params: {
        date_range: route.params.date || 'all',
        group_by: groupBy.value
      }
    })
    .then(({ data }) => {
      // Update metrics
      if (data.metrics) {
        metrics.avgOrderValue = data.metrics.avgOrderValue || 0;
        metrics.avgOrderValueChange = data.metrics.avgOrderValueChange || 0;
        metrics.conversionRate = data.metrics.conversionRate || 0;
        metrics.conversionRateChange = data.metrics.conversionRateChange || 0;
        metrics.totalOrders = data.metrics.totalOrders || 0;
        metrics.totalOrdersChange = data.metrics.totalOrdersChange || 0;
      }
      
      // Update segments
      if (data.segments) {
        segments.value = data.segments.map(segment => ({
          ...segment,
          bgColor: segment.type === 'small' ? 'bg-blue-100' : 
                  segment.type === 'medium' ? 'bg-green-100' : 'bg-purple-100',
          iconColor: segment.type === 'small' ? 'text-blue-600' : 
                    segment.type === 'medium' ? 'text-green-600' : 'text-purple-600',
          barColor: segment.type === 'small' ? 'bg-blue-500' : 
                   segment.type === 'medium' ? 'bg-green-500' : 'bg-purple-500',
        }));
      }
      
      // Update top products
      if (data.topProducts) {
        topProducts.value = data.topProducts;
      }
      
      // Format chart data
      if (data.chartData) {
        chartData.value = {
          labels: data.chartData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
          }),
          datasets: [
            {
              label: "New Orders",
              backgroundColor: "#3b82f6",
              borderColor: "#3b82f6",
              data: data.chartData.map(item => item.newOrders),
            },
            {
              label: "Repeat Orders",
              backgroundColor: "#10b981",
              borderColor: "#10b981",
              data: data.chartData.map(item => item.repeatOrders),
            },
          ],
        };
      }
    })
    .catch(error => {
      console.error("Error fetching order data:", error);
      // You might want to add error handling here, 
      // such as showing an error message to the user
    })
    .finally(() => {
      loading.value = false;
    });
}

// Or call getData() on mount, if you prefer
onMounted(() => {
  getData();
});
</script>