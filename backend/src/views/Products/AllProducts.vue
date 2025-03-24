<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">All Products</h1>
          <p class="mt-2 text-sm text-gray-600">
            View and manage your complete product catalog
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

    <!-- Filter and search controls -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="w-full md:w-64">
          <label for="perPage" class="block text-sm font-medium text-gray-700 mb-1">Products per page</label>
          <select
            id="perPage"
            v-model="perPage"
            @change="getProducts()"
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
            @change="getProducts()"
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
            @change="getProducts()"
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
              placeholder="Search products by title, description..."
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Products table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <ProductsTable @clickEdit="editProduct" @statusChanged="refreshProducts" />
    </div>

    <!-- Pagination -->
    <div v-if="!products.loading && products.data && products.data.length > 0" class="mt-4 flex justify-center">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <a v-for="(link, i) of products.links" 
           :key="i"
           :disabled="!link.url || link.active"
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
import { ref, computed, onMounted, watch, onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../../store";
import ProductModal from "../Products/ProductModal.vue";
import ProductsTable from "../Products/ProductsTable.vue";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId } from "../../utils/ProductUtils";

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
const perPage = ref(100); // Default to 100 per page since this is the "all" view
const sortField = ref("updated_at");
const sortDirection = ref("desc");
const searchTimeout = ref(null);

// Store computed properties
const products = computed(() => store.state.products);

// Process URL query parameters
onMounted(() => {
  // Get query parameters from route
  if (route.query.per_page) {
    perPage.value = parseInt(route.query.per_page) || 100;
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

// Clean up any timers on component unmount
onBeforeUnmount(() => {
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value);
  }
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
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value);
  }
  
  searchTimeout.value = setTimeout(() => {
    refreshProducts();
  }, 300);
}

// Get products with the current filters
function getProducts() {
  store.dispatch('getProducts', {
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    force: true
  });
}

// Call the refresh function
function refreshProducts() {
  getProducts();
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
    showError('Error preparing product for edit');
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