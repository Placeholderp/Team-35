<!-- src/views/Inventory/InventoryDashboard.vue -->
<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Inventory Management</h1>
          <p class="mt-2 text-sm text-gray-600">
            Track and manage product inventory levels
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
          <router-link
            :to="{name: 'app.products'}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Products
          </router-link>
          <button
            @click="refreshData"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
          </button>
          <button
            @click="showAdjustmentModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Adjustment
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
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
                  {{ stats.totalProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Products In Stock
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.inStockProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Low Stock Alerts
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.lowStockProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Out of Stock
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.outOfStockProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex" aria-label="Tabs">
          <button
            @click="activeTab = 'all'"
            :class="[
              activeTab === 'all'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm'
            ]"
          >
            All Products
          </button>
          <button
            @click="activeTab = 'low'"
            :class="[
              activeTab === 'low'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm'
            ]"
          >
            Low Stock
          </button>
          <button
            @click="activeTab = 'out'"
            :class="[
              activeTab === 'out'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm'
            ]"
          >
            Out of Stock
          </button>
          <button
            @click="activeTab = 'history'"
            :class="[
              activeTab === 'history'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm'
            ]"
          >
            Inventory History
          </button>
        </nav>
      </div>
    </div>

    <!-- Tab Content -->
    <div v-if="activeTab === 'all'" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <InventoryProductsTable 
        :products="products" 
        @adjust="openAdjustmentModal" 
        @refresh="refreshData"
      />
    </div>

    <div v-if="activeTab === 'low'" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <InventoryProductsTable 
        :products="lowStockProducts" 
        @adjust="openAdjustmentModal" 
        @refresh="refreshData"
      />
    </div>

    <div v-if="activeTab === 'out'" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <InventoryProductsTable 
        :products="outOfStockProducts" 
        @adjust="openAdjustmentModal" 
        @refresh="refreshData"
      />
    </div>

    <div v-if="activeTab === 'history'" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <InventoryHistoryTable 
        :movements="inventoryMovements" 
        @loadMore="loadMoreHistory"
      />
    </div>

    <!-- Inventory Adjustment Modal -->
    <InventoryAdjustmentModal 
      v-if="showAdjustmentModal"
      v-model="showAdjustmentModal"
      :product="selectedProduct"
      @close="closeAdjustmentModal"
      @saved="onAdjustmentSaved"
    />
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axiosClient from '../../axios';
import store from '../../store';
import InventoryProductsTable from './InventoryProductsTable.vue';
import InventoryHistoryTable from './InventoryHistoryTable.vue';
import InventoryAdjustmentModal from './InventoryAdjustmentModal.vue';
import Toast from "../../components/core/Toast.vue";
import { cleanId } from "../../utils/ProductUtils";

const route = useRoute();
const products = ref([]);
const inventoryMovements = ref([]);
const activeTab = ref('all');
const showAdjustmentModal = ref(false);
const selectedProduct = ref(null);
const isLoading = ref(false);
const historyPage = ref(1);
const stats = ref({
  totalProducts: 0,
  inStockProducts: 0,
  lowStockProducts: 0,
  outOfStockProducts: 0
});

// Computed properties
const lowStockProducts = computed(() => {
  return products.value.filter(product => 
    product.track_inventory && 
    product.quantity > 0 && 
    product.quantity <= (product.reorder_level || 5)
  );
});

const outOfStockProducts = computed(() => {
  return products.value.filter(product => 
    product.track_inventory && 
    product.quantity === 0
  );
});

// Fetch all products
const fetchProducts = async () => {
  isLoading.value = true;
  try {
    const response = await axiosClient.get('/products', { 
      params: { per_page: 100 } 
    });
    products.value = response.data.data;
    
    // Calculate stats
    stats.value.totalProducts = response.data.meta.total;
    stats.value.inStockProducts = products.value.filter(p => p.quantity > 0).length;
    stats.value.lowStockProducts = lowStockProducts.value.length;
    stats.value.outOfStockProducts = outOfStockProducts.value.length;
    
    // Check if we have a productId in query params
    const productId = route.query.productId;
    if (productId) {
      findAndSelectProduct(productId);
    }
  } catch (error) {
    console.error('Error fetching products:', error);
    store.commit('showToast', {
      type: 'error',
      message: 'Failed to load products',
      title: 'Error'
    });
  } finally {
    isLoading.value = false;
  }
};

// Find and select a product by ID
const findAndSelectProduct = (productId) => {
  if (!productId) return;
  
  // Clean the ID first
  const cleanedId = cleanId(productId);
  
  const product = products.value.find(p => String(cleanId(p.id)) === String(cleanedId));
  if (product) {
    openAdjustmentModal(product);
  } else {
    // If product is not found in the list, try to fetch it directly
    fetchProductById(cleanedId);
  }
};

// Fetch a single product by ID
const fetchProductById = async (productId) => {
  try {
    const response = await axiosClient.get(`/products/${productId}`);
    if (response.data) {
      openAdjustmentModal(response.data);
    }
  } catch (error) {
    console.error('Error fetching product:', error);
    store.commit('showToast', {
      type: 'error',
      message: 'Failed to load the requested product',
      title: 'Error'
    });
  }
};

// Fetch inventory movements
const fetchInventoryMovements = async (page = 1) => {
  try {
    const response = await axiosClient.get('/inventory/movements', {
      params: { page }
    });
    
    if (page === 1) {
      inventoryMovements.value = response.data.data;
    } else {
      inventoryMovements.value = [...inventoryMovements.value, ...response.data.data];
    }
  } catch (error) {
    console.error('Error fetching inventory movements:', error);
    store.commit('showToast', {
      type: 'error',
      message: 'Failed to load inventory history',
      title: 'Error'
    });
  }
};

// Load more history items
const loadMoreHistory = () => {
  historyPage.value++;
  fetchInventoryMovements(historyPage.value);
};

// Refresh all data
const refreshData = () => {
  fetchProducts();
  historyPage.value = 1;
  fetchInventoryMovements(1);
};

// Open adjustment modal with selected product
const openAdjustmentModal = (product) => {
  selectedProduct.value = product;
  showAdjustmentModal.value = true;
};

// Close adjustment modal
const closeAdjustmentModal = () => {
  selectedProduct.value = null;
  showAdjustmentModal.value = false;
};

// Handle successful adjustment
const onAdjustmentSaved = () => {
  refreshData();
  closeAdjustmentModal();
  store.commit('showToast', {
    type: 'success',
    message: 'Inventory successfully adjusted',
    title: 'Success'
  });
};

// Watch for changes in the route query
watch(() => route.query.productId, (newProductId) => {
  if (newProductId) {
    findAndSelectProduct(newProductId);
  }
});

// Initial data load
onMounted(() => {
  refreshData();
});
</script>