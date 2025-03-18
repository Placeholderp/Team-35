<!-- ProductGrid.vue -->
<template>
    <div>
      <!-- Filters & Sorting Controls -->
      <div class="mb-6 bg-white p-4 rounded-md shadow">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-4 sm:space-y-0">
          <!-- Search -->
          <div class="w-full sm:w-64">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="search"
                @input="debounceSearch"
                type="text"
                placeholder="Search products..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
            </div>
          </div>
          
          <!-- Filters -->
          <div class="flex space-x-4">
            <!-- Stock Filter -->
            <div>
              <select 
                v-model="stockFilter"
                @change="applyFilters"
                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">All Products</option>
                <option value="in-stock">In Stock</option>
                <option value="low-stock">Low Stock</option>
                <option value="out-of-stock">Out of Stock</option>
              </select>
            </div>
            
            <!-- Sort Order -->
            <div>
              <select 
                v-model="sortBy"
                @change="applyFilters"
                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="newest">Newest</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="name-asc">Name: A to Z</option>
                <option value="name-desc">Name: Z to A</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-500" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      
      <!-- No Results -->
      <div v-else-if="filteredProducts.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
        <div class="mt-6">
          <button 
            @click="resetFilters"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Reset Filters
          </button>
        </div>
      </div>
      
      <!-- Product Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <ProductCard 
          v-for="product in filteredProducts" 
          :key="product.id" 
          :product="product"
          @view-details="viewProductDetails"
        />
      </div>
      
      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex justify-center mt-8">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
          <!-- Previous Page -->
          <button
            @click="changePage(currentPage - 1)"
            :disabled="currentPage === 1"
            :class="[
              'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium',
              currentPage === 1 
                ? 'text-gray-300 cursor-not-allowed' 
                : 'text-gray-500 hover:bg-gray-50'
            ]"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Page Numbers -->
          <template v-for="page in paginationItems" :key="page.id">
            <button
              v-if="page.type === 'page'"
              @click="changePage(page.value)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                page.value === currentPage
                  ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
              ]"
            >
              {{ page.value }}
            </button>
            <span
              v-else-if="page.type === 'ellipsis'"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
            >
              ...
            </span>
          </template>
          
          <!-- Next Page -->
          <button
            @click="changePage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            :class="[
              'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium',
              currentPage === totalPages 
                ? 'text-gray-300 cursor-not-allowed' 
                : 'text-gray-500 hover:bg-gray-50'
            ]"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from 'vue';
  import { useRouter } from 'vue-router';
  import ProductCard from './ProductCard.vue';
  import axiosClient from '../../axios';
  
  const router = useRouter();
  
  // Pagination state
  const currentPage = ref(1);
  const itemsPerPage = ref(12);
  const totalItems = ref(0);
  
  // Filter state
  const search = ref('');
  const stockFilter = ref('all');
  const sortBy = ref('newest');
  const searchTimeout = ref(null);
  
  // Data state
  const products = ref([]);
  const loading = ref(false);
  
  // Computed values
  const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));
  
  // Generate pagination items with ellipsis for large page counts
  const paginationItems = computed(() => {
    const items = [];
    const maxVisiblePages = 5;
    
    if (totalPages.value <= maxVisiblePages) {
      // Show all pages if there are few
      for (let i = 1; i <= totalPages.value; i++) {
        items.push({ type: 'page', value: i, id: `page-${i}` });
      }
    } else {
      // Always show first page
      items.push({ type: 'page', value: 1, id: 'page-1' });
      
      if (currentPage.value <= 3) {
        // Near the start
        for (let i = 2; i <= 4; i++) {
          items.push({ type: 'page', value: i, id: `page-${i}` });
        }
        items.push({ type: 'ellipsis', id: 'ellipsis-1' });
      } else if (currentPage.value >= totalPages.value - 2) {
        // Near the end
        items.push({ type: 'ellipsis', id: 'ellipsis-1' });
        for (let i = totalPages.value - 3; i <= totalPages.value - 1; i++) {
          items.push({ type: 'page', value: i, id: `page-${i}` });
        }
      } else {
        // In the middle
        items.push({ type: 'ellipsis', id: 'ellipsis-1' });
        items.push({ type: 'page', value: currentPage.value - 1, id: `page-${currentPage.value - 1}` });
        items.push({ type: 'page', value: currentPage.value, id: `page-${currentPage.value}` });
        items.push({ type: 'page', value: currentPage.value + 1, id: `page-${currentPage.value + 1}` });
        items.push({ type: 'ellipsis', id: 'ellipsis-2' });
      }
      
      // Always show last page
      items.push({ type: 'page', value: totalPages.value, id: `page-${totalPages.value}` });
    }
    
    return items;
  });
  
  // Stock filtering functions
  function isOutOfStock(product) {
    return product.track_inventory && product.quantity <= 0;
  }
  
  function isLowStock(product) {
    const threshold = product.reorder_level || 5;
    return product.track_inventory && product.quantity > 0 && product.quantity <= threshold;
  }
  
  function isInStock(product) {
    const threshold = product.reorder_level || 5;
    return product.track_inventory && product.quantity > threshold;
  }
  
  // Apply filters to get filtered products
  const filteredProducts = computed(() => {
    let result = [...products.value];
    
    // Apply stock filter
    if (stockFilter.value === 'in-stock') {
      result = result.filter(isInStock);
    } else if (stockFilter.value === 'low-stock') {
      result = result.filter(isLowStock);
    } else if (stockFilter.value === 'out-of-stock') {
      result = result.filter(isOutOfStock);
    }
    
    // Apply search filter (case insensitive)
    if (search.value) {
      const searchLower = search.value.toLowerCase();
      result = result.filter(product => 
        product.title.toLowerCase().includes(searchLower) || 
        (product.description && product.description.toLowerCase().includes(searchLower))
      );
    }
    
    // Apply sorting
    if (sortBy.value === 'price-asc') {
      result.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
    } else if (sortBy.value === 'price-desc') {
      result.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
    } else if (sortBy.value === 'name-asc') {
      result.sort((a, b) => a.title.localeCompare(b.title));
    } else if (sortBy.value === 'name-desc') {
      result.sort((a, b) => b.title.localeCompare(a.title));
    } else {
      // Default: newest first based on created_at or id as fallback
      result.sort((a, b) => {
        if (a.created_at && b.created_at) {
          return new Date(b.created_at) - new Date(a.created_at);
        }
        return b.id - a.id;
      });
    }
    
    return result;
  });
  
  // Fetch products from API
  async function fetchProducts() {
    loading.value = true;
    
    try {
      const response = await axiosClient.get('/products', { 
        params: { 
          page: currentPage.value,
          per_page: itemsPerPage.value,
          // Add any additional API parameters as needed
        } 
      });
      
      // Update state with response data
      products.value = response.data.data;
      totalItems.value = response.data.meta.total;
      
    } catch (error) {
      console.error('Error fetching products:', error);
      // Handle error state
    } finally {
      loading.value = false;
    }
  }
  
  // Debounce search input
  function debounceSearch() {
    if (searchTimeout.value) {
      clearTimeout(searchTimeout.value);
    }
    
    searchTimeout.value = setTimeout(() => {
      applyFilters();
    }, 300);
  }
  
  // Apply filters and reset to first page
  function applyFilters() {
    currentPage.value = 1;
    fetchProducts();
  }
  
  // Reset all filters
  function resetFilters() {
    search.value = '';
    stockFilter.value = 'all';
    sortBy.value = 'newest';
    applyFilters();
  }
  
  // Change page
  function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
      currentPage.value = page;
      fetchProducts();
    }
  }
  
  // View product details
  function viewProductDetails(product) {
    router.push({ name: 'app.products.view', params: { id: product.id } });
  }
  
  // Watch for filter changes
  watch([search, stockFilter, sortBy], () => {
    // This could trigger refetching from API if your backend supports filtering
    // For now, we're just using client-side filtering of the loaded products
  });
  
  // Fetch products on component mount
  onMounted(() => {
    fetchProducts();
  });
  </script>