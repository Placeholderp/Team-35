
<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg">
      <!-- Product Image -->
      <div class="h-48 relative">
        <img 
          v-if="product.image_url" 
          :src="getImageUrl(product.image_url)" 
          :alt="product.title"
          class="w-full h-full object-cover"
        />
        <div 
          v-else
          class="w-full h-full bg-gray-100 flex items-center justify-center"
        >
          <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        
        <!-- Stock Badge -->
        <div class="absolute top-2 right-2">
          <span 
            :class="getStockBadgeClass(product)"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
          >
            {{ getStockBadgeText(product) }}
          </span>
        </div>
      </div>
      
      <!-- Product Details -->
      <div class="p-4">
        <!-- Title -->
        <h3 class="text-lg font-semibold text-gray-800 mb-1 truncate">{{ product.title }}</h3>
        
        <!-- Price -->
        <p class="text-xl font-bold text-indigo-600 mb-2">{{ formatCurrency(product.price) }}</p>
        
        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
          {{ product.description || 'No description available' }}
        </p>
        
        <!-- Stock Details -->
        <div class="flex items-center justify-between mt-2">
          <div class="flex items-center">
            <template v-if="product.track_inventory">
              <svg 
                :class="getStockIconClass(product)" 
                class="h-4 w-4 mr-1" 
                fill="currentColor" 
                viewBox="0 0 20 20"
              >
                <path v-if="isOutOfStock(product)" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                <path v-else-if="isLowStock(product)" fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                <path v-else fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <span :class="getStockTextClass(product)" class="text-xs font-medium">
                {{ product.quantity }} units
              </span>
            </template>
            <span v-else class="text-xs text-gray-500">Not tracked</span>
          </div>
          
          <button 
            @click="$emit('view-details', product)"
            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            View Details
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import { formatCurrency, getImageUrl } from '../../utils/ProductUtils';
  
  defineEmits(['view-details']);
  
  const props = defineProps({
    product: {
      type: Object,
      required: true
    }
  });
  
  // Stock level thresholds
  const LOW_STOCK_THRESHOLD = 5;
  
  // Stock status helpers
  function isOutOfStock(product) {
    return product.track_inventory && product.quantity <= 0;
  }
  
  function isLowStock(product) {
    return product.track_inventory && product.quantity > 0 && product.quantity <= (product.reorder_level || LOW_STOCK_THRESHOLD);
  }
  
  function isInStock(product) {
    return product.track_inventory && product.quantity > (product.reorder_level || LOW_STOCK_THRESHOLD);
  }
  
  // Badge helpers
  function getStockBadgeText(product) {
    if (!product.track_inventory) return 'Status Unknown';
    if (isOutOfStock(product)) return 'Out of Stock';
    if (isLowStock(product)) return 'Low Stock';
    return 'In Stock';
  }
  
  function getStockBadgeClass(product) {
    if (!product.track_inventory) return 'bg-gray-100 text-gray-800';
    if (isOutOfStock(product)) return 'bg-red-100 text-red-800';
    if (isLowStock(product)) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
  }
  
  // Stock text and icon helpers
  function getStockTextClass(product) {
    if (isOutOfStock(product)) return 'text-red-600';
    if (isLowStock(product)) return 'text-yellow-600';
    return 'text-green-600';
  }
  
  function getStockIconClass(product) {
    if (isOutOfStock(product)) return 'text-red-500';
    if (isLowStock(product)) return 'text-yellow-500';
    return 'text-green-500';
  }
  </script>
  
  <style scoped>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  </style>