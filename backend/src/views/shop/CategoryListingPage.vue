<template>
    <div class="bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-16 text-center">
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900" v-if="activeCategory">
            {{ activeCategory.name }}
          </h1>
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900" v-else>
            Shop by Category
          </h1>
          <p class="mt-4 max-w-xl mx-auto text-base text-gray-500" v-if="activeCategory">
            {{ activeCategory.description }}
          </p>
          <p class="mt-4 max-w-xl mx-auto text-base text-gray-500" v-else>
            Browse our collection of products by category
          </p>
        </div>
  
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb" v-if="breadcrumbs.length > 0">
          <ol class="flex items-center space-x-2">
            <li>
              <div>
                <router-link to="/" class="text-gray-400 hover:text-gray-500">
                  <svg class="flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                  </svg>
                  <span class="sr-only">Home</span>
                </router-link>
              </div>
            </li>
  
            <li v-for="(breadcrumb, index) in breadcrumbs" :key="index">
              <div class="flex items-center">
                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                </svg>
                <router-link 
                  :to="{ name: 'category', params: { slug: breadcrumb.slug } }" 
                  :class="[
                    'ml-2 text-sm font-medium', 
                    index === breadcrumbs.length - 1 
                      ? 'text-gray-700' 
                      : 'text-gray-500 hover:text-gray-700'
                  ]"
                  :aria-current="index === breadcrumbs.length - 1 ? 'page' : undefined"
                >
                  {{ breadcrumb.name }}
                </router-link>
              </div>
            </li>
          </ol>
        </nav>
  
        <!-- Categories List -->
        <div v-if="!activeCategory || subcategories.length > 0" class="mb-12">
          <h2 class="text-2xl font-bold text-gray-900 mb-6" v-if="activeCategory">
            Browse {{ activeCategory.name }} Categories
          </h2>
          <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
            <div v-for="category in displayCategories" :key="category.id" class="group">
              <router-link :to="{ name: 'category', params: { slug: category.slug } }">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                  <img 
                    v-if="category.image_url" 
                    :src="category.image_url" 
                    :alt="category.name" 
                    class="w-full h-full object-center object-cover group-hover:opacity-75"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-indigo-100 group-hover:bg-indigo-200 transition">
                    <svg class="h-16 w-16 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0h10a2 2 0 012 2v2M7 7h10" />
                    </svg>
                  </div>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ category.name }}</h3>
                <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ category.description || 'Browse products in this category' }}</p>
              </router-link>
            </div>
          </div>
        </div>
  
        <!-- Products Grid -->
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-6" v-if="products.length > 0">
            {{ activeCategory ? activeCategory.name + ' Products' : 'Featured Products' }}
          </h2>
          
          <div v-if="loading" class="flex justify-center py-12">
            <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
          
          <div v-else-if="products.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">
              We couldn't find any products in this category.
            </p>
          </div>
          
          <div v-else class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
            <div v-for="product in products" :key="product.id" class="group">
              <router-link :to="{ name: 'product', params: { slug: product.slug } }">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.title" 
                    class="w-full h-full object-center object-cover group-hover:opacity-75"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-gray-100 group-hover:bg-gray-200 transition">
                    <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ product.title }}</h3>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ formatCurrency(product.price) }}</p>
              </router-link>
            </div>
          </div>
          
          <!-- Pagination for products -->
          <div class="mt-8 flex justify-center" v-if="totalPages > 1">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
              >
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              
              <button
                v-for="page in paginationPages"
                :key="page"
                @click="changePage(page)"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  page === currentPage
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
              
              <button
                @click="changePage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
              >
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axiosClient from '../axios';
  import { formatCurrency } from '../utils/ProductUtils';
  import { getCategoryPath } from '../utils/CategoryUtils';
  
  const route = useRoute();
  const router = useRouter();
  
  // State
  const categories = ref([]);
  const activeCategory = ref(null);
  const products = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const currentPage = ref(1);
  const perPage = ref(12);
  const totalProducts = ref(0);
  
  // Computed
  const subcategories = computed(() => {
    if (!activeCategory.value) return categories.value.filter(c => !c.parent_id); // Root categories
    return categories.value.filter(c => c.parent_id === activeCategory.value.id);
  });
  
  const displayCategories = computed(() => {
    return activeCategory.value ? subcategories.value : categories.value.filter(c => !c.parent_id);
  });
  
  const breadcrumbs = computed(() => {
    if (!activeCategory.value) return [];
    return getCategoryPath(categories.value, activeCategory.value.id);
  });
  
  const totalPages = computed(() => {
    return Math.ceil(totalProducts.value / perPage.value);
  });
  
  const paginationPages = computed(() => {
    const pages = [];
    const maxVisiblePages = 5;
    
    if (totalPages.value <= maxVisiblePages) {
      // Show all pages if total is less than max visible
      for (let i = 1; i <= totalPages.value; i++) {
        pages.push(i);
      }
    } else {
      // Calculate which pages to show
      let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1);
      
      // Adjust if we're near the end
      if (endPage === totalPages.value) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
      }
    }
    
    return pages;
  });
  
  // Methods
  function loadCategories() {
    loading.value = true;
    
    axiosClient.get('/categories', {
      params: {
        is_active: true,
        per_page: 100,
        include: 'children'
      }
    })
    .then(response => {
      categories.value = response.data.data;
      findActiveCategory();
    })
    .catch(err => {
      console.error('Error loading categories:', err);
      error.value = 'Failed to load categories. Please try again.';
    })
    .finally(() => {
      loading.value = false;
    });
  }
  
  function findActiveCategory() {
    if (!route.params.slug) {
      activeCategory.value = null;
      return;
    }
    
    activeCategory.value = categories.value.find(c => c.slug === route.params.slug);
    
    // If we can't find the category, redirect to main shop page
    if (!activeCategory.value) {
      router.push({ name: 'shop' });
    }
  }
  
  function loadProducts() {
    loading.value = true;
    
    const params = {
      per_page: perPage.value,
      page: currentPage.value,
      include: 'category',
      published: true,
      sort_field: 'updated_at',
      sort_direction: 'desc'
    };
    
    // Add category filter if we have an active category
    if (activeCategory.value) {
      params.category_id = activeCategory.value.id;
    }
    
    axiosClient.get('/products', { params })
      .then(response => {
        products.value = response.data.data;
        totalProducts.value = response.data.meta.total;
      })
      .catch(err => {
        console.error('Error loading products:', err);
        error.value = 'Failed to load products. Please try again.';
      })
      .finally(() => {
        loading.value = false;
      });
  }
  
  function changePage(page) {
    if (page < 1 || page > totalPages.value) return;
    
    currentPage.value = page;
    loadProducts();
    
    // Scroll to top of product list
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  
  // Watchers
  watch(() => route.params.slug, (newSlug) => {
    // Reset pagination when category changes
    currentPage.value = 1;
    
    // Update active category
    findActiveCategory();
    
    // Load products for this category
    loadProducts();
  });
  
  // Lifecycle
  onMounted(() => {
    loadCategories();
    loadProducts();
  });
  </script>