<!-- src/views/Inventory/InventoryProductsTable.vue -->
<template>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Product
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              SKU
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Current Stock
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Reorder Level
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody v-if="products.length" class="bg-white divide-y divide-gray-200">
          <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img v-if="product.image_url" :src="product.image_url" :alt="product.title" class="h-10 w-10 rounded-full object-cover">
                  <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ product.title }}</div>
                  <div class="text-sm text-gray-500">${{ formatPrice(product.price) }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ product.sku || 'N/A' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <div class="text-sm font-medium" :class="getStockClass(product)">
                {{ product.quantity }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <div class="text-sm text-gray-900">{{ product.reorder_level || 5 }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <span :class="getStatusBadgeClass(product)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ getStatusText(product) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
              <button 
                @click="$emit('adjust', product)"
                class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Adjust
              </button>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
              No products found
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import { formatCurrency } from '../../utils/ProductUtils';
  
  defineProps({
    products: {
      type: Array,
      required: true
    }
  });
  
  defineEmits(['adjust', 'refresh']);
  
  const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
  };
  
  const getStockClass = (product) => {
    if (!product.track_inventory) return 'text-gray-500';
    if (product.quantity === 0) return 'text-red-600';
    if (product.quantity <= product.reorder_level) return 'text-yellow-600';
    return 'text-green-600';
  };
  
  const getStatusText = (product) => {
    if (!product.track_inventory) return 'Not Tracked';
    if (product.quantity === 0) return 'Out of Stock';
    if (product.quantity <= product.reorder_level) return 'Low Stock';
    return 'In Stock';
  };
  
  const getStatusBadgeClass = (product) => {
    if (!product.track_inventory) return 'bg-gray-100 text-gray-800';
    if (product.quantity === 0) return 'bg-red-100 text-red-800';
    if (product.quantity <= product.reorder_level) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
  };
  </script>