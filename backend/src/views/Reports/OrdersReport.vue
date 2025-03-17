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

    <!-- BEST SELLERS SECTION -->
    <div class="mt-8">
      <h3 class="text-xl font-medium text-gray-900 mb-4">Best Selling Products</h3>
      
      <!-- Top Products Filtering Options -->
      <div class="flex flex-wrap items-center mb-4 gap-4">
        <div class="flex items-center">
          <label for="sort-by" class="text-sm text-gray-600 mr-2">Sort by:</label>
          <select
            id="sort-by"
            v-model="productSort"
            @change="refreshTopProducts"
            class="bg-white border border-gray-300 text-gray-700 py-1 px-3 
                  rounded-md shadow-sm focus:outline-none text-sm"
          >
            <option value="orders">Most Orders</option>
            <option value="revenue">Highest Revenue</option>
            <option value="avgPrice">Highest Average Price</option>
          </select>
        </div>
        
        <div class="flex items-center">
          <label for="time-frame" class="text-sm text-gray-600 mr-2">Time:</label>
          <select
            id="time-frame"
            v-model="productTimeFrame"
            @change="refreshTopProducts"
            class="bg-white border border-gray-300 text-gray-700 py-1 px-3 
                  rounded-md shadow-sm focus:outline-none text-sm"
          >
            <option value="all">All Time</option>
            <option value="month">This Month</option>
            <option value="quarter">This Quarter</option>
            <option value="year">This Year</option>
          </select>
        </div>
        
        <div class="flex items-center">
          <label for="limit" class="text-sm text-gray-600 mr-2">Show:</label>
          <select
            id="limit"
            v-model="productLimit"
            @change="refreshTopProducts"
            class="bg-white border border-gray-300 text-gray-700 py-1 px-3 
                  rounded-md shadow-sm focus:outline-none text-sm"
          >
            <option value="5">Top 5</option>
            <option value="10">Top 10</option>
            <option value="20">Top 20</option>
          </select>
        </div>
      </div>
      
      <!-- Best Sellers Loading State -->
      <div v-if="productsLoading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
      </div>
      
      <!-- Best Sellers Table -->
      <div v-else class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Orders
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Avg. Price
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total Revenue
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                In Stock
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="topProducts.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                No product data available for the selected criteria
              </td>
            </tr>
            <tr
              v-for="(product, index) in topProducts"
              :key="index"
              class="hover:bg-gray-50"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md flex items-center justify-center">
                    <img 
                      v-if="product.image_url" 
                      :src="product.image_url" 
                      :alt="product.name"
                      class="h-10 w-10 object-cover rounded-md"
                    />
                    <svg 
                      v-else
                      xmlns="http://www.w3.org/2000/svg" 
                      class="h-6 w-6 text-gray-400" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                    >
                      <path 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2" 
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" 
                      />
                    </svg>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ product.name }}
                    </div>
                    <div class="text-sm text-gray-500">
                      SKU: {{ product.sku || 'N/A' }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                <div>
                  {{ product.orders }}
                </div>
                <div class="text-xs text-gray-500">
                  {{ product.percentage }}% of total
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                ${{ product.avgPrice }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                ${{ product.totalRevenue.toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <span 
                  class="px-2 py-1 text-xs font-medium rounded-full"
                  :class="product.inStock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ product.inStock ? 'Yes' : 'No' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- View More Link -->
      <div class="mt-4 text-right">
        <router-link 
          :to="{ name: 'app.products.all' }" 
          class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
        >
          View all products →
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, reactive, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "../../axios.js";

// Make sure these paths match your actual folder/file names
import LineChart from "../../components/core/Charts/Line.vue";
import BarChart from "../../components/core/Charts/Bar.vue";

// Reactive references
const route = useRoute();
const router = useRouter();
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

// Best selling products variables
const topProducts = ref([]);
const productsLoading = ref(false);
const productSort = ref("revenue");
const productTimeFrame = ref("all");
const productLimit = ref("10");

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
      
      // Load top products
      refreshTopProducts();
    })
    .catch(error => {
      console.error("Error fetching order data:", error);
      // You might want to add error handling here
    })
    .finally(() => {
      loading.value = false;
    });
}

// Function to refresh top products with current filters
function refreshTopProducts() {
  productsLoading.value = true;
  
  axiosClient
    .get('/reports/products/top', {
      params: {
        date_range: route.params.date || 'all',
        sort_by: productSort.value,
        time_frame: productTimeFrame.value,
        limit: productLimit.value
      }
    })
    .then(({ data }) => {
      if (data && Array.isArray(data)) {
        topProducts.value = data;
      } else {
        topProducts.value = [];
      }
    })
    .catch(error => {
      console.error("Error fetching top products:", error);
      topProducts.value = [];
    })
    .finally(() => {
      productsLoading.value = false;
    });
}

// Call getData() on mount
onMounted(() => {
  getData();
});
</script>