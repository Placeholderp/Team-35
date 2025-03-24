<!-- src/views/Inventory/InventoryAdjustmentModal.vue -->
<template>
  <!-- Wrap everything in a single root div -->
  <div class="inventory-adjustment-modal-wrapper">
    <Teleport to="body">
      <TransitionRoot as="template" :show="modelValue">
        <Dialog as="div" class="fixed inset-0 z-50 overflow-y-auto" @close="closeModal">
          <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0"
              enter-to="opacity-100"
              leave="ease-in duration-200"
              leave-from="opacity-100"
              leave-to="opacity-0"
            >
              <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>
  
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <TransitionChild
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enter-to="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 translate-y-0 sm:scale-100"
              leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="relative">
                  <Spinner v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 z-10"/>
                  
                  <div class="bg-indigo-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                      <DialogTitle class="text-xl font-semibold text-white">
                        Inventory Adjustment
                      </DialogTitle>
                      <button
                        type="button"
                        @click="closeModal"
                        class="text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700 rounded-full p-1"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                  
                  <form @submit.prevent="saveAdjustment">
                    <div class="bg-white px-6 pt-6 pb-4 space-y-6">
                      <!-- Product Selection -->
                      <div v-if="!product">
                        <label for="product-select" class="block text-sm font-medium text-gray-700 mb-1">
                          Select Product
                        </label>
                        <div class="mt-1">
                          <select
                            id="product-select"
                            v-model="selectedProductId"
                            @change="loadSelectedProduct"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            required
                          >
                            <option value="">Select a product</option>
                            <option v-for="product in products" :key="product.id" :value="product.id">
                              {{ product.title }}
                            </option>
                          </select>
                        </div>
                      </div>
                      
                      <!-- Rest of the component content remains the same -->
                      <!-- Product Info -->
                      <div v-if="adjustmentProduct" class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start">
                          <div class="flex-shrink-0 h-12 w-12">
                            <img v-if="adjustmentProduct.image_url" :src="adjustmentProduct.image_url" :alt="adjustmentProduct.title" class="h-12 w-12 rounded-md object-cover">
                            <div v-else class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                              <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                          </div>
                          <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ adjustmentProduct.title }}</h3>
                            <div class="mt-1 text-sm text-gray-500">
                              <p>Current Stock: <span class="font-medium">{{ adjustmentProduct.quantity }}</span></p>
                              <p>SKU: <span class="font-medium">{{ adjustmentProduct.sku || 'N/A' }}</span></p>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Adjustment Type -->
                      <div>
                        <label for="adjustment-type" class="block text-sm font-medium text-gray-700 mb-1">
                          Adjustment Type <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <select
                            id="adjustment-type"
                            v-model="adjustmentType"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            required
                          >
                            <option value="">Select adjustment type</option>
                            <option value="purchase">Purchase</option>
                            <option value="sale">Sale</option>
                            <option value="adjustment">Inventory Adjustment</option>
                            <option value="return">Customer Return</option>
                          </select>
                        </div>
                        <p v-if="errors.adjustmentType" class="mt-1 text-sm text-red-600">
                          {{ errors.adjustmentType }}
                        </p>
                      </div>
                      
                      <!-- Quantity -->
                      <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                          Quantity <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                          <button
                            type="button"
                            @click="changeQuantity(-1)"
                            class="absolute left-0 inset-y-0 px-2 flex items-center"
                            :disabled="quantityMode === 'set'"
                          >
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                          </button>
                          <input
                            type="number"
                            id="quantity"
                            v-model.number="quantity"
                            :class="{'pl-10': quantityMode !== 'set', 'pr-10': quantityMode !== 'set'}"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md text-center"
                            min="1"
                            required
                          />
                          <button
                            type="button"
                            @click="changeQuantity(1)"
                            class="absolute right-0 inset-y-0 px-2 flex items-center"
                            :disabled="quantityMode === 'set'"
                          >
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                          </button>
                        </div>
                        <p v-if="errors.quantity" class="mt-1 text-sm text-red-600">
                          {{ errors.quantity }}
                        </p>
                      </div>
                      
                      <!-- Quantity Mode Toggle -->
                      <div>
                        <div class="flex items-center space-x-4">
                          <div class="flex items-center">
                            <input
                              id="quantity-add"
                              name="quantity-mode"
                              type="radio"
                              value="add"
                              v-model="quantityMode"
                              class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                            />
                            <label for="quantity-add" class="ml-2 block text-sm text-gray-700">
                              Add/Remove Quantity
                            </label>
                          </div>
                          <div class="flex items-center">
                            <input
                              id="quantity-set"
                              name="quantity-mode"
                              type="radio"
                              value="set"
                              v-model="quantityMode"
                              class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                            />
                            <label for="quantity-set" class="ml-2 block text-sm text-gray-700">
                              Set Exact Quantity
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Reference -->
                      <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">
                          Reference
                        </label>
                        <div class="mt-1">
                          <input
                            type="text"
                            id="reference"
                            v-model="reference"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Order #, Invoice #, etc."
                          />
                        </div>
                      </div>
                      
                      <!-- Notes -->
                      <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                          Notes
                        </label>
                        <div class="mt-1">
                          <textarea
                            id="notes"
                            v-model="notes"
                            rows="3"
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Additional information about this adjustment"
                          ></textarea>
                        </div>
                      </div>
                      
                      <!-- Preview -->
                      <div v-if="adjustmentProduct" class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-yellow-800">Preview</h4>
                        <p class="mt-1 text-sm text-yellow-700">
                          Current stock: <span class="font-medium">{{ adjustmentProduct.quantity }}</span>
                        </p>
                        <p class="text-sm text-yellow-700">
                          <span v-if="quantityMode === 'add'">
                            {{ getAdjustmentQuantity() > 0 ? 'Adding' : 'Removing' }} <span class="font-medium">{{ Math.abs(getAdjustmentQuantity()) }}</span> units
                          </span>
                          <span v-else>
                            Setting stock to <span class="font-medium">{{ quantity }}</span> units
                          </span>
                        </p>
                        <p class="text-sm text-yellow-700">
                          New stock will be: <span class="font-medium">{{ getNewStockLevel() }}</span>
                        </p>
                      </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
                      <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        :disabled="loading || !isValid"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition disabled:opacity-75 disabled:cursor-not-allowed"
                      >
                        Save Adjustment
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </TransitionChild>
          </div>
        </Dialog>
      </TransitionRoot>
    </Teleport>
  </div>
</template>
  
<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue';
import axiosClient from '../../axios';
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store/index.js";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  product: {
    type: Object,
    default: null
  }
});

// Add changePassword to emits array
const emit = defineEmits([
  'update:modelValue', 
  'close', 
  'saved',
  'changePassword',
  'vnodeUnmounted'
]);

const loading = ref(false);
const products = ref([]);
const selectedProductId = ref('');
const adjustmentProduct = ref(null);
const adjustmentType = ref('');
const quantity = ref(1);
const quantityMode = ref('add');
const reference = ref('');
const notes = ref('');
const errors = ref({
  adjustmentType: '',
  quantity: ''
});

// Calculate the new stock level based on the adjustment
const getNewStockLevel = () => {
  if (!adjustmentProduct.value) return 0;
  
  if (quantityMode.value === 'set') {
    return quantity.value;
  } else {
    return adjustmentProduct.value.quantity + getAdjustmentQuantity();
  }
};

// Get the adjustment quantity with sign (positive or negative)
const getAdjustmentQuantity = () => {
  if (quantityMode.value === 'set') {
    return quantity.value - (adjustmentProduct.value?.quantity || 0);
  }
  
  // Handle add/subtract mode
  let value = quantity.value;
  
  // For sales and some adjustments, make the quantity negative
  if (['sale', 'adjustment'].includes(adjustmentType.value) && !reference.value.includes('return')) {
    value = -value;
  }
  
  return value;
};

// Validation
const isValid = computed(() => {
  return (
    adjustmentProduct.value &&
    adjustmentType.value &&
    quantity.value > 0 && 
    (quantityMode.value === 'set' || getNewStockLevel() >= 0)
  );
});

// Watch for product prop changes
watch(() => props.product, (newProduct) => {
  if (newProduct) {
    adjustmentProduct.value = newProduct;
    selectedProductId.value = newProduct.id;
  }
}, { immediate: true });

// Load available products
const loadProducts = async () => {
  try {
    const response = await axiosClient.get('/products', { 
      params: { per_page: 100 } 
    });
    products.value = response.data.data;
  } catch (error) {
    console.error('Error loading products:', error);
  }
};

// Load a product when selected
const loadSelectedProduct = async () => {
  if (!selectedProductId.value) return;
  
  loading.value = true;
  try {
    const response = await axiosClient.get(`/products/${selectedProductId.value}`);
    adjustmentProduct.value = response.data;
  } catch (error) {
    console.error('Error loading product:', error);
    store.commit('showToast', {
      type: 'error',
      message: 'Failed to load product details',
      title: 'Error'
    });
  } finally {
    loading.value = false;
  }
};

// Change quantity with the +/- buttons
const changeQuantity = (delta) => {
  if (quantityMode.value === 'set') return;
  
  let newValue = quantity.value + delta;
  if (newValue < 1) newValue = 1;
  quantity.value = newValue;
};

// Submit the adjustment
const saveAdjustment = async () => {
  // Clear previous errors
  errors.value = {};
  
  // Validate form
  if (!adjustmentType.value) {
    errors.value.adjustmentType = 'Please select an adjustment type';
    return;
  }
  
  if (!quantity.value || quantity.value <= 0) {
    errors.value.quantity = 'Please enter a valid quantity';
    return;
  }
  
  // In case of remove stock, ensure we don't go below zero
  if (quantityMode.value === 'add' && getNewStockLevel() < 0) {
    errors.value.quantity = `Cannot remove more than the current stock (${adjustmentProduct.value.quantity})`;
    return;
  }
  
  loading.value = true;
  
  try {
    // Calculate the actual quantity change
    const adjustmentQuantity = getAdjustmentQuantity();
    
    // Send the request
    await axiosClient.post('/inventory/adjust', {
      product_id: adjustmentProduct.value.id,
      quantity: adjustmentQuantity,
      type: adjustmentType.value,
      reference: reference.value,
      notes: notes.value
    });
    
    emit('saved');
    closeModal();
  } catch (error) {
    console.error('Error saving adjustment:', error);
    
    // Handle API validation errors
    if (error.response && error.response.data && error.response.data.errors) {
      const apiErrors = error.response.data.errors;
      
      // Map API errors to our local error object
      if (apiErrors.quantity) {
        errors.value.quantity = apiErrors.quantity[0];
      }
      
      if (apiErrors.type) {
        errors.value.adjustmentType = apiErrors.type[0];
      }
    } else {
      // Generic error
      store.commit('showToast', {
        type: 'error',
        message: error.response?.data?.message || 'Failed to save adjustment',
        title: 'Error'
      });
    }
  } finally {
    loading.value = false;
  }
};

// Close the modal
const closeModal = () => {
  // Reset form
  adjustmentType.value = '';
  quantity.value = 1;
  quantityMode.value = 'add';
  reference.value = '';
  notes.value = '';
  errors.value = {};
  
  // Only reset the product if not provided by props
  if (!props.product) {
    selectedProductId.value = '';
    adjustmentProduct.value = null;
  }
  
  emit('update:modelValue', false);
  emit('close');
};

// Initialize
onMounted(() => {
  loadProducts();
});
</script>