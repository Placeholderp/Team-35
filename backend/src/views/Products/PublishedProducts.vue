<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Published Products</h1>
          <p class="mt-2 text-sm text-gray-600">
            View all your products that are live and visible to customers
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
            @click="refreshProducts"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Summary -->
    <div class="mb-6">
      <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Total Published Products</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ totalPublished }}</dd>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Average Price</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ averagePrice }}</dd>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg px-4 py-5 sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Potential Revenue</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ totalRevenue }}</dd>
        </div>
      </dl>
    </div>

    <!-- Filter and search controls -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="w-full md:w-64">
          <label for="perPage" class="block text-sm font-medium text-gray-700 mb-1">Products per page</label>
          <select
            id="perPage"
            v-model="perPage"
            @change="getProducts(null)"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
        
        <div class="w-full md:w-64">
          <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-1">Sort by</label>
          <select
            id="sortBy"
            v-model="sortField"
            @change="getProducts(null)"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="title">Title</option>
            <option value="price">Price</option>
            <option value="updated_at">Last Updated</option>
            <option value="id">ID</option>
          </select>
        </div>
        
        <div class="w-full md:w-64">
          <label for="sortDirection" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
          <select
            id="sortDirection"
            v-model="sortDirection"
            @change="getProducts(null)"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>
        </div>
        
        <div class="w-full md:flex-1">
          <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="text"
              id="search"
              v-model="search"
              @input="debounceSearch"
              class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2"
              placeholder="Search published products..."
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="products.loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Published products grid -->
    <div v-else-if="productsToShow.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div v-for="product in productsToShow" :key="product.id" class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition">
        <div class="h-48 overflow-hidden bg-gray-200">
          <img v-if="product.image_url" :src="product.image_url" :alt="product.title" class="w-full h-full object-cover">
          <div v-else class="flex items-center justify-center h-full bg-gray-100">
            <svg class="h-16 w-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
              <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>

        <div class="p-4">
          <h3 class="text-lg font-medium text-gray-900 truncate">{{ product.title }}</h3>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ product.description || 'No description available' }}</p>
          <div class="mt-4 flex justify-between items-center">
            <span class="font-medium text-indigo-600">${{ parseFloat(product.price).toFixed(2) }}</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
              <span class="h-2 w-2 mr-1 rounded-full bg-green-400"></span>
              Published
            </span>
          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <span class="text-xs text-gray-500">ID: {{ product.id }}</span>
            <div class="flex space-x-2">
              <button
                @click="editProduct(product)"
                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
              </button>
              <button
                @click="unpublishProduct(product)"
                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
              >
                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Unpublish
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else class="bg-white py-12 px-6 rounded-lg shadow text-center">
      <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">No published products found</h3>
      <p class="mt-2 text-sm text-gray-500">
        Publish some products to make them visible to your customers.
      </p>
      <div class="mt-6">
        <button
          @click="router.push({ name: 'app.products' })"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Back to Products
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="!products.loading && productsToShow.length > 0" class="mt-6 flex justify-center">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <a v-for="(link, i) of products.links" 
           :key="i"
           :disabled="!link.url"
           href="#"
           @click.prevent="getForPage($event, link)"
           :class="[
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
              link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === products.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : ''
           ]"
           v-html="link.label"
        ></a>
      </nav>
    </div>
    
    <!-- Product Modal -->
    <ProductModal v-model="showProductModal" :product="productModel" @close="onModalClose" />
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "../../axios";
import store from "../../store";
import ProductModal from "../Products/ProductModal.vue";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId, prepareProductFormData } from "../../utils/ProductUtils";

const route = useRoute();
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
const search = ref("");
const perPage = ref(50);
const sortField = ref("updated_at");
const sortDirection = ref("desc");
const searchTimeout = ref(null);

// Store computed properties
const products = computed(() => store.state.products);

// Fixed computed property for filtering published products
const productsToShow = computed(() => {
  if (!products.value.data || !Array.isArray(products.value.data)) {
    return [];
  }
  
  return products.value.data.filter(product => {
    // Use the utility function to safely normalize published status
    return normalizePublished(product.published);
  });
});

// Calculate stats
const totalPublished = computed(() => {
  return productsToShow.value.length;
});

const averagePrice = computed(() => {
  if (!productsToShow.value.length) return '0.00';
  const total = productsToShow.value.reduce((sum, product) => sum + parseFloat(product.price || 0), 0);
  return (total / productsToShow.value.length).toFixed(2);
});

const totalRevenue = computed(() => {
  if (!productsToShow.value.length) return '0.00';
  const total = productsToShow.value.reduce((sum, product) => sum + parseFloat(product.price || 0), 0);
  return total.toFixed(2);
});

// Process URL query parameters
onMounted(() => {
  // Get query parameters from route
  if (route.query.per_page) {
    perPage.value = parseInt(route.query.per_page) || 50;
  }
  
  if (route.query.sort_field) {
    sortField.value = route.query.sort_field;
  }
  
  if (route.query.sort_direction) {
    sortDirection.value = route.query.sort_direction;
  }
  
  if (route.query.search) {
    search.value = route.query.search;
  }
  
  // Load products on mount with appropriate filters
  refreshProducts();
});

// Watch for changes to refresh data
watch([perPage, sortField, sortDirection], () => {
  refreshProducts();
});

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

// Debounced search
function debounceSearch() {
  if (searchTimeout.value) clearTimeout(searchTimeout.value);
  
  searchTimeout.value = setTimeout(() => {
    refreshProducts();
  }, 300);
}

// Get products with the current filters - load all products first, then filter client-side
function refreshProducts() {
  store.dispatch('getProducts', {
    force: true,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

// Pagination
function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) return;
  store.dispatch('getProducts', { url: link.url });
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
  
  productModel.value = productToEdit;
  showProductModal.value = true;
}

function unpublishProduct(product) {
  if (!confirm(`Are you sure you want to unpublish "${product.title}"?`)) return;
  
  const id = cleanId(product.id);
  const updatedProduct = {
    ...product,
    id,
    published: false
  };
  
  const formData = prepareProductFormData(updatedProduct, true);
  
  axiosClient.post(`/products/${id}`, formData)
    .then(() => {
      refreshProducts();
      showSuccess(`Product "${product.title}" has been unpublished.`);
    })
    .catch((error) => {
      showError(`Failed to unpublish product. ${error.response?.data?.message || 'Please try again.'}`);
    });
}

function onModalClose() {
  // Force refresh the products with cache busting
  refreshProducts();
  
  // Reset the product model
  productModel.value = { ...DEFAULT_PRODUCT };
  showProductModal.value = false;
}
</script>