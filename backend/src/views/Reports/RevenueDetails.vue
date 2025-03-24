<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Revenue Details</h1>
          <p class="mt-2 text-sm text-gray-600">
            Track potential and actual revenue from your product catalog
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
          <button
            @click="router.push({ name: 'app.products' })"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Products
          </button>
          <button
            @click="refreshData"
            :disabled="isLoading"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            {{ isLoading ? 'Refreshing...' : 'Refresh' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Revenue Summary Cards -->
    <div class="mb-8">
      <dl class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Total Products Value</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ formatNumber(totalProductsValue) }}</dd>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Published Products Value</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ formatNumber(publishedProductsValue) }}</dd>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Average Product Price</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ formatNumber(averagePrice) }}</dd>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Highest Priced Product</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ formatNumber(highestPrice) }}</dd>
        </div>
      </dl>
    </div>

    <!-- Price Distribution Chart using Bar.vue -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Price Distribution
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Overview of product pricing across your catalog
        </p>
      </div>
      <div class="p-6">
        <div class="h-64">
          <BarChart 
            :data="priceDistributionChartData" 
            :height="250"
            class="w-full"
          />
        </div>
      </div>
    </div>

    <!-- Top Products by Value -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Top Products by Value
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Your highest priced products
        </p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Price
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Last Updated
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Actions</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in topProducts" :key="product.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                    <img v-if="product.image_url" :src="product.image_url" :alt="product.title" class="h-10 w-10 object-cover">
                    <svg v-else class="h-10 w-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ product.title }}
                    </div>
                    <div class="text-sm text-gray-500">
                      ID: {{ product.id }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 font-medium">${{ formatNumber(product.price) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                    product.published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ product.published ? 'Published' : 'Draft' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(product.updated_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="editProduct(product)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  Edit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from 'vue-router';
import store from "../../store";
import BarChart from "../../components/core/Charts/Bar.vue";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId, formatCurrency } from "../../utils/ProductUtils";

const router = useRouter();
const isLoading = ref(false);

// Helper methods for formatting
function formatNumber(value) {
  return parseFloat(value || 0).toFixed(2);
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Store computed properties
const products = computed(() => store.state.products.data || []);

// Calculate revenue metrics
const totalProductsValue = computed(() => {
  return products.value.reduce((sum, product) => sum + parseFloat(product.price || 0), 0);
});

const publishedProductsValue = computed(() => {
  return products.value
    .filter(product => normalizePublished(product.published))
    .reduce((sum, product) => sum + parseFloat(product.price || 0), 0);
});

const averagePrice = computed(() => {
  if (!products.value.length) return 0;
  return totalProductsValue.value / products.value.length;
});

const highestPrice = computed(() => {
  if (!products.value.length) return 0;
  return Math.max(...products.value.map(product => parseFloat(product.price || 0)));
});

// Calculate price distribution ranges
const priceRanges = computed(() => {
  if (!products.value || products.value.length === 0) return [];

  // Find min and max prices to establish range
  let minPrice = Math.min(...products.value.map(p => parseFloat(p.price || 0)));
  let maxPrice = Math.max(...products.value.map(p => parseFloat(p.price || 0)));
  
  // Add a small buffer to ensure the highest price is included
  maxPrice = maxPrice + 1;
  
  // Round down/up to create nice ranges
  minPrice = Math.floor(minPrice / 10) * 10;
  maxPrice = Math.ceil(maxPrice / 10) * 10;
  
  // Create 5 price ranges
  const rangeSize = Math.ceil((maxPrice - minPrice) / 5);
  
  // Initialize ranges
  const ranges = [];
  for (let i = 0; i < 5; i++) {
    const min = minPrice + (i * rangeSize);
    const max = min + rangeSize;
    ranges.push({
      min,
      max,
      count: 0
    });
  }
  
  // Count products in each range
  products.value.forEach(product => {
    const price = parseFloat(product.price || 0);
    for (const range of ranges) {
      if (price >= range.min && price <= range.max) { // Changed from < to <= to include max value
        range.count++;
        break;
      }
    }
  });
  
  return ranges;
});

// Chart data for price distribution
const priceDistributionChartData = computed(() => {
  return {
    labels: priceRanges.value.map(range => `$${range.min}-$${range.max}`),
    datasets: [
      {
        label: 'Number of Products',
        data: priceRanges.value.map(range => range.count),
        backgroundColor: '#6366F1', // Indigo color to match your existing UI
        hoverBackgroundColor: '#4F46E5',
      }
    ]
  };
});

// Get the maximum count for scaling the chart
const maxCount = computed(() => {
  if (!priceRanges.value.length) return 0;
  const max = Math.max(...priceRanges.value.map(range => range.count));
  return max === 0 ? 1 : max; // Avoid division by zero
});

// Get top products by price
const topProducts = computed(() => {
  return [...products.value]
    .sort((a, b) => parseFloat(b.price || 0) - parseFloat(a.price || 0))
    .slice(0, 10);
});

onMounted(() => {
  refreshData();
});

function refreshData() {
  isLoading.value = true;
  // Add a timestamp to force cache invalidation
  store.dispatch('getProducts', { 
    force: true, 
    per_page: 100,
    _t: new Date().getTime() 
  })
  .then(() => {
    // Show success message
    store.commit('showToast', 'Data refreshed successfully');
  })
  .catch(error => {
    console.error('Error refreshing data:', error);
    store.commit('showToast', 'Failed to refresh data', 'error');
  })
  .finally(() => {
    isLoading.value = false;
  });
}

function editProduct(product) {
  router.push({ name: 'app.products', query: { edit: cleanId(product.id) } });
}
</script>