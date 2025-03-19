<!-- BulkStockAdjustment.vue -->
<template>
    <div class="bg-white rounded-lg shadow p-4">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Stock Adjustment</h3>
      
      <!-- Product Selection -->
      <div class="mb-4">
        <label for="products-select" class="block text-sm font-medium text-gray-700 mb-1">
          Select Products
        </label>
        <div class="relative">
          <select
            id="products-select"
            v-model="selectedProductIds"
            multiple
            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
          >
            <option 
              v-for="product in filteredProducts" 
              :key="product.id" 
              :value="product.id"
            >
              {{ product.title }} (Current: {{ product.quantity || 0 }})
            </option>
          </select>
        </div>
        <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple products</p>
      </div>
      
      <!-- Category Filter -->
      <div class="mb-4">
        <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">
          Filter by Category
        </label>
        <select
          id="category-filter"
          v-model="categoryFilter"
          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="">All Categories</option>
          <option 
            v-for="category in categories" 
            :key="category.id" 
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>
      
      <!-- Adjustment Type -->
      <div class="mb-4">
        <label for="adjustment-type" class="block text-sm font-medium text-gray-700 mb-1">
          Adjustment Type
        </label>
        <select
          id="adjustment-type"
          v-model="adjustmentType"
          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="add">Add to Stock</option>
          <option value="set">Set Exact Quantity</option>
          <option value="remove">Remove from Stock</option>
        </select>
      </div>
      
      <!-- Quantity to Adjust -->
      <div class="mb-4">
        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
          {{ quantityLabel }}
        </label>
        <div class="flex items-center">
          <button 
            @click="decreaseQuantity"
            type="button"
            class="p-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100"
            :disabled="quantity <= 1"
          >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <input
            id="quantity"
            type="number"
            v-model.number="quantity"
            min="1"
            class="block w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-center"
            placeholder="1"
          />
          <button 
            @click="increaseQuantity"
            type="button"
            class="p-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100"
          >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Reference -->
      <div class="mb-4">
        <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
          Reference (Optional)
        </label>
        <input
          id="reference"
          type="text"
          v-model="reference"
          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="e.g., New shipment received"
        />
      </div>
      
      <!-- Notes -->
      <div class="mb-4">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
          Notes (Optional)
        </label>
        <textarea
          id="notes"
          v-model="notes"
          rows="2"
          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Additional details about this adjustment"
        ></textarea>
      </div>
      
      <!-- Preview -->
      <div v-if="selectedProducts.length > 0" class="mb-4 bg-gray-50 p-3 rounded-md">
        <h4 class="font-medium text-gray-700 mb-2">Adjustment Preview</h4>
        <div class="max-h-40 overflow-y-auto">
          <div 
            v-for="product in selectedProducts" 
            :key="product.id"
            class="flex justify-between py-1 border-b border-gray-200 last:border-0"
          >
            <span class="text-sm text-gray-700">{{ product.title }}</span>
            <span class="text-sm font-medium">
              {{ calculateNewQuantity(product) }} 
              <span class="text-gray-500">({{ showChange(product) }})</span>
            </span>
          </div>
        </div>
      </div>
      
      <div v-if="errorMessage" class="mb-4 bg-red-50 p-3 rounded-md text-red-700 text-sm">
        {{ errorMessage }}
      </div>
      
      <div class="flex justify-end space-x-3">
        <button 
          type="button"
          @click="$emit('cancel')"
          class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Cancel
        </button>
        <button 
          type="button"
          @click="applyAdjustment"
          :disabled="loading || selectedProductIds.length === 0"
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-75 disabled:cursor-not-allowed"
        >
          <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Apply to {{ selectedProductIds.length }} {{ selectedProductIds.length === 1 ? 'Product' : 'Products' }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, defineProps, defineEmits, watch } from 'vue';
  import axiosClient from '../../axios';
  import store from '../../store';
  import { getStockStatusClass } from '../../utils/InventoryUtils';
  
  const props = defineProps({
    products: {
      type: Array,
      required: true
    },
    categories: {
      type: Array,
      default: () => []
    }
  });
  
  const emit = defineEmits(['cancel', 'success']);
  
  // State
  const selectedProductIds = ref([]);
  const categoryFilter = ref('');
  const adjustmentType = ref('add');
  const quantity = ref(1);
  const reference = ref('');
  const notes = ref('');
  const loading = ref(false);
  const errorMessage = ref('');
  
  // Computed properties
  const filteredProducts = computed(() => {
    if (!categoryFilter.value) {
      return props.products;
    }
    
    return props.products.filter(product => 
      product.category_id === categoryFilter.value ||
      (product.category && product.category.id === categoryFilter.value)
    );
  });
  
  const selectedProducts = computed(() => {
    return props.products.filter(product => 
      selectedProductIds.value.includes(product.id)
    );
  });
  
  const quantityLabel = computed(() => {
    switch (adjustmentType.value) {
      case 'add': return 'Quantity to Add';
      case 'remove': return 'Quantity to Remove';
      case 'set': return 'Set Quantity To';
      default: return 'Quantity';
    }
  });
  
  // Methods
  function increaseQuantity() {
    quantity.value++;
  }
  
  function decreaseQuantity() {
    if (quantity.value > 1) {
      quantity.value--;
    }
  }
  
  function calculateNewQuantity(product) {
    const currentQty = product.quantity || 0;
    
    switch (adjustmentType.value) {
      case 'add':
        return currentQty + quantity.value;
      case 'remove':
        return Math.max(0, currentQty - quantity.value);
      case 'set':
        return quantity.value;
      default:
        return currentQty;
    }
  }
  
  function showChange(product) {
    const currentQty = product.quantity || 0;
    const newQty = calculateNewQuantity(product);
    const diff = newQty - currentQty;
    
    if (diff > 0) return `+${diff}`;
    if (diff < 0) return diff;
    return 'No change';
  }
  
  async function applyAdjustment() {
    if (selectedProductIds.value.length === 0) {
      errorMessage.value = 'Please select at least one product';
      return;
    }
    
    if (quantity.value <= 0) {
      errorMessage.value = 'Quantity must be greater than zero';
      return;
    }
    
    // Clear any previous error
    errorMessage.value = '';
    loading.value = true;
    
    try {
      // Process each product sequentially
      const results = [];
      
      for (const productId of selectedProductIds.value) {
        const product = props.products.find(p => p.id === productId);
        if (!product) continue;
        
        // Determine the actual quantity change based on adjustment type
        let adjustmentQty = quantity.value;
        let adjustmentTypeApi = 'purchase'; // Default for adding stock
        
        if (adjustmentType.value === 'remove') {
          adjustmentQty = -quantity.value;
          adjustmentTypeApi = 'adjustment';
        } else if (adjustmentType.value === 'set') {
          adjustmentQty = quantity.value - (product.quantity || 0);
          adjustmentTypeApi = adjustmentQty >= 0 ? 'purchase' : 'adjustment';
        }
        
        // Skip if no change for 'set' type
        if (adjustmentType.value === 'set' && adjustmentQty === 0) {
          results.push({
            productId,
            success: true,
            message: 'No change needed',
            skipped: true
          });
          continue;
        }
        
        // Call API to adjust inventory
        const response = await axiosClient.post(`/products/${productId}/adjust-inventory`, {
          product_id: productId,
          quantity: adjustmentQty,
          type: adjustmentTypeApi,
          reference: reference.value || `Bulk ${adjustmentType.value} adjustment`,
          notes: notes.value || `Adjusted ${Math.abs(adjustmentQty)} pieces via bulk operation`
        });
        
        results.push({
          productId,
          success: response.data.success,
          message: response.data.message,
          product: response.data.data?.product,
          newQuantity: response.data.data?.new_quantity
        });
      }
      
      // Check if all operations were successful
      const allSuccess = results.every(r => r.success);
      const successCount = results.filter(r => r.success).length;
      const skipCount = results.filter(r => r.skipped).length;
      
      if (allSuccess) {
        emit('success', {
          results,
          adjustmentType: adjustmentType.value,
          quantity: quantity.value,
          successCount,
          skipCount
        });
        
        // Show success toast
        store.commit('showToast', {
          type: 'success',
          message: `Successfully adjusted ${successCount} products${skipCount > 0 ? ` (${skipCount} skipped)` : ''}`,
          title: 'Success'
        });
      } else {
        // Some operations failed
        errorMessage.value = `Some adjustments failed. ${successCount} of ${selectedProductIds.value.length} completed successfully.`;
      }
    } catch (error) {
      console.error('Error adjusting inventory:', error);
      
      // Show appropriate error message
      if (error.response && error.response.data && error.response.data.message) {
        errorMessage.value = error.response.data.message;
      } else {
        errorMessage.value = 'Failed to communicate with the server. Please try again.';
      }
      
      // Show error toast
      store.commit('showToast', {
        type: 'error',
        message: errorMessage.value,
        title: 'Error'
      });
    } finally {
      loading.value = false;
    }
  }
  </script>