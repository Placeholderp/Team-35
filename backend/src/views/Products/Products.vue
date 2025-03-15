<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Products</h1>
          <p class="mt-2 text-sm text-gray-600">
            Manage your product catalog, update inventory and pricing
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
          <button
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </button>
          <button
            @click="showAddNewModal"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Product
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <!-- Total Products Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Total Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ totalProducts.value }}
                </div>
                <span class="ml-2 text-sm font-medium text-green-600">
                  <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="sr-only">Increased by</span>
                  {{ totalProducts.trend }}%
                </span>
              </dd>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="text-sm">
            <a href="#" @click.prevent="viewAllProducts" class="font-medium text-indigo-600 hover:text-indigo-500">View all<span class="sr-only"> products</span></a>
          </div>
        </div>
      </div>

      <!-- Published Products Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Published Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ publishedProducts.value }}
                </div>
                <span class="ml-2 text-sm font-medium text-green-600">
                  <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="sr-only">Increased by</span>
                  {{ publishedProducts.trend }}%
                </span>
              </dd>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="text-sm">
            <a href="#" @click.prevent="viewPublishedProducts" class="font-medium text-indigo-600 hover:text-indigo-500">View published<span class="sr-only"> products</span></a>
          </div>
        </div>
      </div>

      <!-- Revenue Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Potential Revenue
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ totalRevenue.value }}
                </div>
                <span class="ml-2 text-sm font-medium text-green-600">
                  <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="sr-only">Increased by</span>
                  {{ totalRevenue.trend }}%
                </span>
              </dd>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="text-sm">
            <a href="#" @click.prevent="viewRevenueDetails" class="font-medium text-indigo-600 hover:text-indigo-500">View revenue<span class="sr-only"> details</span></a>
          </div>
        </div>
      </div>

      <!-- Orders Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Recent Orders
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ recentOrders.value }}
                </div>
                <span class="ml-2 text-sm font-medium text-green-600">
                  <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="sr-only">Increased by</span>
                  {{ recentOrders.trend }}%
                </span>
              </dd>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="text-sm">
            <a href="#" @click.prevent="viewOrderDetails" class="font-medium text-indigo-600 hover:text-indigo-500">View orders<span class="sr-only"> details</span></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <ProductsTable @clickEdit="editProduct" @statusChanged="refreshProducts" />
    </div>

    <!-- Product Modal -->
    <ProductModal v-model="showProductModal" :product="productModel" @close="onModalClose" />
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from "vue";
import { useRouter } from 'vue-router';
import store from "../../store";
import ProductModal from "./ProductModal.vue";
import ProductsTable from "./ProductsTable.vue";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId, formatCurrency } from "../../utils/ProductUtils";
import axiosClient from "../../axios";

const router = useRouter();
const DEFAULT_PRODUCT = {
  id: '',
  title: '',
  description: '',
  image: '',
  price: '',
  published: false
};

const productModel = ref({ ...DEFAULT_PRODUCT });
const showProductModal = ref(false);

// Helper methods for notifications
function showSuccess(message, title = 'Success') {
  store.commit('showToast', {
    type: 'success',
    message,
    title,
    delay: 5000
  });
}

function showError(message, title = 'Error') {
  store.commit('showToast', {
    type: 'error',
    message,
    title,
    delay: 5000
  });
}

// Compute trend percentages with more robust calculation
function calculateTrend(current, previous) {
  // Ensure we don't divide by zero and handle edge cases
  if (previous === 0) {
    return current > 0 ? 100 : 0;
  }
  
  // Calculate percentage change
  const trend = ((current - previous) / previous) * 100;
  
  // Round to nearest whole number
  return Math.round(trend);
}

// Fetch historical data to get realistic trends
async function fetchHistoricalData() {
  try {
    // Fetch data from last month or last period
    const response = await axiosClient.get('/products/historical-stats');
    
    // Update store with historical data
    store.commit('updateHistoricalMetrics', response.data);
  } catch (error) {
    console.error('Error fetching historical data:', error);
    // Fallback to local storage or default values
    const fallbackHistorical = JSON.parse(localStorage.getItem('historicalMetrics') || '{}');
    store.commit('updateHistoricalMetrics', fallbackHistorical);
  }
}

// Computed properties for dashboard stats with dynamic calculations
const totalProducts = computed(() => {
  const total = store.state.products.total || 0;
  const previousTotal = store.state.historicalMetrics?.totalProducts || 0;
  
  return {
    value: total,
    trend: calculateTrend(total, previousTotal)
  };
});

const publishedProducts = computed(() => {
  if (!store.state.products.data) return { 
    value: 0, 
    trend: 0 
  };
  
  const published = store.state.products.data.filter(p => normalizePublished(p.published)).length;
  const previousPublished = store.state.historicalMetrics?.publishedProducts || 0;
  
  return {
    value: published,
    trend: calculateTrend(published, previousPublished)
  };
});

const totalRevenue = computed(() => {
  if (!store.state.products.data) return { 
    value: formatCurrency(0), 
    trend: 0 
  };
  
  const revenueCalculation = store.state.products.data.reduce((sum, product) => {
    return sum + (parseFloat(product.price) || 0);
  }, 0);
  
  const previousRevenue = store.state.historicalMetrics?.totalRevenue || 0;
  
  return {
    value: formatCurrency(revenueCalculation),
    trend: calculateTrend(revenueCalculation, previousRevenue)
  };
});

const recentOrders = computed(() => {
  const total = store.state.orders.total || 0;
  const previousOrders = store.state.historicalMetrics?.recentOrders || 0;
  
  return {
    value: total,
    trend: calculateTrend(total, previousOrders)
  };
});

// Function to refresh products data
function refreshProducts() {
  store.dispatch('getProducts');
}

// Make the notification methods available globally
onMounted(() => {
  window.$notification = {
    success: showSuccess,
    error: showError,
    info: (message, title = 'Information') => {
      store.commit('showToast', {
        type: 'info',
        message,
        title,
        delay: 5000
      });
    },
    warning: (message, title = 'Warning') => {
      store.commit('showToast', {
        type: 'warning',
        message,
        title,
        delay: 5000
      });
    }
  };
  
  // Fetch historical data
  fetchHistoricalData();
  
  // Load products on mount
  refreshProducts();
  
  // Load orders data
  store.dispatch('getOrders', {});
});

// Clean up global reference on unmount
onUnmounted(() => {
  window.$notification = null;
});

function showAddNewModal() {
  productModel.value = { ...DEFAULT_PRODUCT }; 
  showProductModal.value = true;
}

function editProduct(p) {
  // Ensure we have an ID
  if (!p || !p.id) {
    showError('Invalid product - missing ID');
    return;
  }
  
  // Create a safe copy with cleaned ID and normalized published status
  const productToEdit = { 
    ...p,
    id: cleanId(p.id),
    published: normalizePublished(p.published)
  };
  
  // Double check the ID was properly cleaned
  if (productToEdit.id.includes(':')) {
    productToEdit.id = cleanId(productToEdit.id);
  }
  
  productModel.value = productToEdit;
  showProductModal.value = true;
}

function onModalClose() {
  // Force refresh the products with cache busting
  store.dispatch('getProducts', { force: true });
  
  // Reset the product model
  productModel.value = { ...DEFAULT_PRODUCT };
  showProductModal.value = false;
}

// View functions that navigate to different pages
function viewAllProducts(event) {
  router.push({ name: 'app.products.all' });
}

function viewPublishedProducts(event) {
  router.push({ name: 'app.products.published' });
}

function viewRevenueDetails(event) {
  router.push({ name: 'reports.revenue' });
}

function viewOrderDetails(event) {
  router.push({ name: 'app.orders' });
}
</script>