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
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
          <button
            @click="exportProducts"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </button>
          <router-link
            :to="{name: 'app.inventory'}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
            </svg>
            Inventory Management
          </router-link>
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
            <router-link 
              :to="{name: 'app.products.all'}" 
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              View all<span class="sr-only"> products</span>
            </router-link>
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
            <router-link 
              :to="{name: 'app.products.published'}" 
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              View published<span class="sr-only"> products</span>
            </router-link>
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
            <router-link 
              :to="{name: 'reports.revenue'}" 
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              View revenue<span class="sr-only"> details</span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Inventory Status Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Inventory Status
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ inventoryStatus.value }}
                </div>
                <span v-if="inventoryStatus.alerts > 0" class="ml-2 text-sm font-medium text-red-600">
                  <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                  <span class="sr-only">Alert:</span>
                  {{ inventoryStatus.alerts }} alerts
                </span>
              </dd>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="text-sm">
            <router-link 
              :to="{name: 'app.inventory'}" 
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              Manage inventory<span class="sr-only"> details</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <ProductsTable @clickEdit="editProduct" @statusChanged="refreshProducts" @manageInventory="goToInventory" />
    </div>

    <!-- Product Modal -->
    <ProductModal v-model="showProductModal" :product="productModel" @close="onModalClose" />
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from 'vue-router';
import store from "../../store";
import ProductModal from "./ProductModal.vue";
import ProductsTable from "./ProductsTable.vue";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId, formatCurrency } from "../../utils/ProductUtils";

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

// Computed properties for dashboard stats with memoized results
const totalProducts = computed(() => {
  return {
    value: store.state.products.total || 0,
    trend: 5 // Static placeholder value
  };
});

const publishedProducts = computed(() => {
  if (!store.state.products.data || store.state.products.data.length === 0) {
    return { 
      value: 0, 
      trend: 3 // Static placeholder value
    };
  }
  
  const published = store.state.products.data.filter(p => normalizePublished(p.published)).length;
  
  return {
    value: published,
    trend: 3 // Static placeholder value
  };
});

const totalRevenue = computed(() => {
  if (!store.state.products.data || store.state.products.data.length === 0) {
    return { 
      value: formatCurrency(0), 
      trend: 4 // Static placeholder value
    };
  }
  
  const revenueCalculation = store.state.products.data.reduce((sum, product) => {
    const price = parseFloat(product.price);
    return sum + (isNaN(price) ? 0 : price);
  }, 0);
  
  return {
    value: formatCurrency(revenueCalculation),
    trend: 4 // Static placeholder value
  };
});

// New computed property for inventory status
const inventoryStatus = computed(() => {
  if (!store.state.products.data || store.state.products.data.length === 0) {
    return {
      value: 'N/A',
      alerts: 0
    };
  }
  
  // Count products with inventory tracking
  const trackedProducts = store.state.products.data.filter(p => p.track_inventory).length;
  
  // Count low stock and out of stock products
  const lowStockProducts = store.state.products.data.filter(p => 
    p.track_inventory && 
    p.quantity > 0 && 
    p.quantity <= (p.reorder_level || 5)
  ).length;
  
  const outOfStockProducts = store.state.products.data.filter(p =>
    p.track_inventory && 
    p.quantity === 0
  ).length;
  
  return {
    value: trackedProducts > 0 ? 'Active' : 'Not Tracked',
    alerts: lowStockProducts + outOfStockProducts
  };
});

const recentOrders = computed(() => {
  return {
    value: store.state.orders?.total || 0,
    trend: 2 // Static placeholder value
  };
});

// Function to refresh products data
function refreshProducts() {
  store.dispatch('getProducts', { force: true });
}

// Navigate to inventory management for a specific product
function goToInventory(product) {
  router.push({
    name: 'app.inventory',
    query: { productId: product.id } // Pass the product ID as a query parameter
  });
}

// Make the notification methods available globally (with safer global access)
onMounted(() => {
  // Create safe notification methods
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
  
  // Load products on mount
  refreshProducts();
  
  // Load orders data
  store.dispatch('getOrders', {});
});

// Add this function to the script section of Products.vue
// Add this function to the script section of Products.vue
function enableInventoryTracking() {
  if (!confirm("This will enable inventory tracking for all products. Continue?")) {
    return;
  }
  
  store.commit('setLoading', true);
  
  // Get all products that don't have tracking enabled
  const untracked = store.state.products.data.filter(p => !p.track_inventory);
  
  if (untracked.length === 0) {
    showSuccess('All products already have inventory tracking enabled.');
    store.commit('setLoading', false);
    return;
  }
  
  // Create a counter for successful updates
  let successCount = 0;
  let totalToUpdate = untracked.length;
  
  // Process each product
  const updatePromises = untracked.map(product => {
    const cleanedId = cleanId(product.id);
    
    // Create form data with tracking enabled
    const formData = prepareProductFormData({
      ...product,
      track_inventory: true,
      quantity: 0,
      reorder_level: 5
    }, true);
    
    // Update the product
    return axiosClient.post(`/products/${cleanedId}`, formData)
      .then(() => {
        successCount++;
        return true;
      })
      .catch(error => {
        console.error(`Error updating product ${cleanedId}:`, error);
        return false;
      });
  });
  
  // Wait for all updates to complete
  Promise.all(updatePromises)
    .then(() => {
      // Refresh product list with cache-busting
      return store.dispatch('getProducts', { force: true });
    })
    .then(() => {
      showSuccess(`Enabled inventory tracking for ${successCount} of ${totalToUpdate} products.`);
    })
    .catch(error => {
      showError('An error occurred while updating products.');
      console.error('Error in batch update:', error);
    })
    .finally(() => {
      store.commit('setLoading', false);
    });
}

// Clean up global reference on unmount
onBeforeUnmount(() => {
  // Make sure we clean up the global notification object
  if (window.$notification) {
    window.$notification = null;
  }
});

// Export products function - placeholder
function exportProducts() {
  try {
    showSuccess('Products export started. You will be notified when the export is ready for download.');
    // Actual export implementation would go here
  } catch (error) {
    console.error('Error exporting products:', error);
    showError('Failed to export products. Please try again later.');
  }
}

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
  
  try {
    // Create a safe copy with cleaned ID and normalized published status
    const productToEdit = { 
      ...p,
      id: cleanId(p.id),
      published: normalizePublished(p.published)
    };
    
    productModel.value = productToEdit;
    showProductModal.value = true;
  } catch (error) {
    console.error('Error preparing product for edit:', error);
    showError('Failed to prepare product for editing');
  }
}

function onModalClose() {
  // Force refresh the products with cache busting
  refreshProducts();
  
  // Reset the product model
  productModel.value = { ...DEFAULT_PRODUCT };
  showProductModal.value = false;
}
</script>