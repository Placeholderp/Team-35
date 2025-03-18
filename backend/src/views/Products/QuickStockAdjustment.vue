<!-- QuickStockAdjustment.vue -->
<template>
    <div class="bg-white rounded-lg shadow p-4">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Add Stock to Inventory</h3>
      
      <div class="mb-4">
        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
          Quantity to Add
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
      
      <div class="mb-4">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
          Notes (Optional)
        </label>
        <textarea
          id="notes"
          v-model="notes"
          rows="2"
          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Additional details about this stock addition"
        ></textarea>
      </div>
      
      <div class="mb-4 bg-gray-50 p-3 rounded-md">
        <p class="text-sm text-gray-700">
          <span class="font-medium">Current Stock:</span> {{ product.quantity || 0 }} pieces
        </p>
        <p class="text-sm text-gray-700">
          <span class="font-medium">After Adjustment:</span> {{ product.quantity + quantity }} pieces
        </p>
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
          @click="addStock"
          :disabled="loading"
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-75 disabled:cursor-not-allowed"
        >
          <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Add to Stock
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits } from 'vue';
  import axiosClient from '../../axios';
  
  const props = defineProps({
    product: {
      type: Object,
      required: true
    }
  });
  
  const emit = defineEmits(['cancel', 'success']);
  
  const quantity = ref(1);
  const reference = ref('');
  const notes = ref('');
  const loading = ref(false);
  const errorMessage = ref('');
  
  function increaseQuantity() {
    quantity.value++;
  }
  
  function decreaseQuantity() {
    if (quantity.value > 1) {
      quantity.value--;
    }
  }
  
  async function addStock() {
    if (quantity.value <= 0) {
      errorMessage.value = 'Quantity must be greater than zero';
      return;
    }
    
    // Clear any previous error
    errorMessage.value = '';
    loading.value = true;
    
    try {
      // Match the API endpoint and parameters with your Laravel controller
      const response = await axiosClient.post(`/products/${props.product.id}/adjust-inventory`, {
        product_id: props.product.id,
        quantity: quantity.value, // Positive value for adding stock
        type: 'purchase',
        reference: reference.value || 'Manual stock addition',
        notes: notes.value || `Added ${quantity.value} pieces to inventory`
      });
      
      // Use the server's response data
      if (response.data.success) {
        emit('success', {
          product: response.data.data.product,
          addedQuantity: quantity.value,
          newTotal: response.data.data.new_quantity
        });
      } else {
        errorMessage.value = response.data.message || 'Failed to add stock';
      }
      
    } catch (error) {
      console.error('Error adding stock:', error);
      
      // Show appropriate error message
      if (error.response && error.response.data && error.response.data.message) {
        errorMessage.value = error.response.data.message;
      } else {
        errorMessage.value = 'Failed to communicate with the server. Please try again.';
      }
    } finally {
      loading.value = false;
    }
  }
  </script>