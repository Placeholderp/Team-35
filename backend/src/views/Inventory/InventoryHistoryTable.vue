<!-- src/views/Inventory/InventoryHistoryTable.vue -->
<template>
    <div>
      <!-- Filtering options -->
      <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
          <div class="w-full md:w-64">
            <label for="movement-type" class="block text-sm font-medium text-gray-700 mb-1">Movement Type</label>
            <select
              id="movement-type"
              v-model="filters.type"
              @change="applyFilters"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="">All Types</option>
              <option value="purchase">Purchase</option>
              <option value="sale">Sale</option>
              <option value="adjustment">Adjustment</option>
              <option value="return">Return</option>
            </select>
          </div>
          
          <div class="w-full md:w-64">
            <label for="date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
            <select
              id="date-range"
              v-model="filters.dateRange"
              @change="applyFilters"
              class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="all">All Time</option>
              <option value="today">Today</option>
              <option value="yesterday">Yesterday</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
            </select>
          </div>
          
          <div class="w-full md:flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
            <div class="relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                type="text"
                id="search"
                v-model="filters.search"
                @input="debounceSearch"
                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2"
                placeholder="Search by product name or SKU"
              />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Inventory Movements Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date & Time
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Movement Type
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Quantity
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Previous Stock
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                New Stock
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Reference
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                User
              </th>
            </tr>
          </thead>
          <tbody v-if="movements.length" class="bg-white divide-y divide-gray-200">
            <tr v-for="movement in movements" :key="movement.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDateTime(movement.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img v-if="movement.product?.image_url" :src="movement.product.image_url" :alt="movement.product.title" class="h-10 w-10 rounded-full object-cover">
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                      <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ movement.product?.title || 'Unknown Product' }}
                    </div>
                    <div v-if="movement.product?.sku" class="text-sm text-gray-500">
                      SKU: {{ movement.product.sku }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getMovementTypeBadgeClass(movement.type)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ formatMovementType(movement.type) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" :class="getQuantityClass(movement.quantity)">
                {{ formatQuantity(movement.quantity) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                {{ movement.previous_quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                {{ movement.new_quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ movement.reference || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ movement.created_by?.name || 'System' }}
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                No inventory movements found
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Load More Button -->
      <div v-if="movements.length" class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
        <button 
          @click="$emit('loadMore')" 
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Load More
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits } from 'vue';
  import { formatDate } from '../../utils/ProductUtils';
  
  const props = defineProps({
    movements: {
      type: Array,
      required: true
    }
  });
  
  defineEmits(['loadMore']);
  
  const filters = ref({
    type: '',
    dateRange: 'all',
    search: ''
  });
  
  const searchTimeout = ref(null);
  
  // Debounced search
  const debounceSearch = () => {
    if (searchTimeout.value) clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
      applyFilters();
    }, 300);
  };
  
  // Apply filters
  const applyFilters = () => {
    // In a real implementation, this would dispatch an action to reload data with filters
    console.log('Applying filters:', filters.value);
    
    // For now just emit an event that would be caught by parent
    // You would pass the filters as a parameter in a real implementation
  };
  
  // Format the date and time
  const formatDateTime = (dateString) => {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    return date.toLocaleString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };
  
  // Format the movement type for display
  const formatMovementType = (type) => {
    if (!type) return 'Unknown';
    
    const typeMap = {
      'purchase': 'Purchase',
      'sale': 'Sale',
      'adjustment': 'Adjustment',
      'return': 'Return',
      'initial': 'Initial Stock'
    };
    
    return typeMap[type] || type.charAt(0).toUpperCase() + type.slice(1);
  };
  
  // Get the appropriate CSS class for the movement type badge
  const getMovementTypeBadgeClass = (type) => {
    const classMap = {
      'purchase': 'bg-green-100 text-green-800',
      'sale': 'bg-blue-100 text-blue-800',
      'adjustment': 'bg-purple-100 text-purple-800',
      'return': 'bg-yellow-100 text-yellow-800',
      'initial': 'bg-gray-100 text-gray-800'
    };
    
    return classMap[type] || 'bg-gray-100 text-gray-800';
  };
  
  // Format the quantity for display with + or - sign
  const formatQuantity = (quantity) => {
    if (quantity > 0) return `+${quantity}`;
    return quantity.toString();
  };
  
  // Get the appropriate CSS class for the quantity display
  const getQuantityClass = (quantity) => {
    if (quantity > 0) return 'text-green-600';
    if (quantity < 0) return 'text-red-600';
    return 'text-gray-500';
  };
  </script>