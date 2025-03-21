<!-- src/components/inventory/EnhancedBulkAdjustmentModal.vue -->
<template>
  <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div 
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
        aria-hidden="true"
        @click="$emit('close')"
      ></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
        <div class="bg-indigo-700 px-6 py-4">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-white" id="modal-title">
              Bulk Stock Adjustment
            </h3>
            <button
              type="button"
              @click="$emit('close')"
              class="text-white hover:text-gray-200 focus:outline-none"
            >
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        
        <div class="bg-white px-6 py-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Selection Summary -->
            <div class="col-span-1 md:col-span-3 bg-indigo-50 rounded-lg p-4">
              <div class="flex items-center justify-between">
                <h4 class="font-medium text-indigo-700">Selection</h4>
                <span class="text-sm bg-indigo-100 text-indigo-800 py-1 px-2 rounded-full">
                  {{ selectedProductIds.length }} product{{ selectedProductIds.length !== 1 ? 's' : '' }} selected
                </span>
              </div>
              
              <div v-if="selectedProductIds.length === 0" class="mt-2 text-sm text-gray-500">
                No products selected. Please select at least one product.
              </div>
              
              <div v-else class="mt-2 flex flex-wrap gap-2">
                <span 
                  v-for="product in selectedProducts.slice(0, 5)" 
                  :key="product.id"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                >
                  {{ product.title }}
                  <button 
                    type="button" 
                    @click="removeProduct(product.id)"
                    class="ml-1 text-indigo-500 hover:text-indigo-600 focus:outline-none"
                  >
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </span>
                
                <span v-if="selectedProductIds.length > 5" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                  +{{ selectedProductIds.length - 5 }} more
                </span>
                
                <button 
                  v-if="selectedProductIds.length > 0"
                  type="button" 
                  @click="selectedProductIds = []"
                  class="ml-auto text-xs text-indigo-600 hover:text-indigo-900"
                >
                  Clear all
                </button>
              </div>
            </div>
            
            <!-- Search -->
            <div>
              <label for="search-filter" class="block text-sm font-medium text-gray-700 mb-1">
                Search Products
              </label>
              <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input
                  id="search-filter"
                  type="text"
                  v-model="searchFilter"
                  class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Search by name or SKU"
                />
              </div>
            </div>
            
            <!-- Stock Status Filter -->
            <div>
              <label for="stock-status-filter" class="block text-sm font-medium text-gray-700 mb-1">
                Filter by Stock Status
              </label>
              <select
                id="stock-status-filter"
                v-model="stockStatusFilter"
                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">All Stock Statuses</option>
                <option value="in_stock">In Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
              </select>
            </div>
          </div>
          
          <!-- Product Selection -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Select Products
            </label>
            
            <div class="border border-gray-300 rounded-md overflow-hidden">
              <div class="max-h-60 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50 sticky top-0">
                    <tr>
                      <th scope="col" class="px-3 py-2">
                        <input
                          type="checkbox"
                          :checked="isAllProductsSelected"
                          @change="toggleAllProducts"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                      </th>
                      <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Product
                      </th>
                      <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Current Stock
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                      <td class="px-3 py-2 whitespace-nowrap">
                        <input
                          type="checkbox"
                          :value="product.id"
                          v-model="selectedProductIds"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-8 w-8">
                            <img v-if="product.image_url" :src="product.image_url" :alt="product.title" class="h-8 w-8 rounded-full object-cover">
                            <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                          </div>
                          <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ product.title }}</div>
                            <div class="text-xs text-gray-500">SKU: {{ product.sku || 'N/A' }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium" :class="getStockClass(product)">
                        {{ product.quantity }}
                      </td>
                    </tr>
                    <tr v-if="filteredProducts.length === 0" class="hover:bg-gray-50">
                      <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">
                        No products found matching your filters.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Adjustment Type -->
            <div>
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
            <div>
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
          </div>
          
          <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Reference -->
              <div>
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
              <div>
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
            </div>
          </div>
          
          <!-- Preview -->
          <div v-if="selectedProducts.length > 0" class="mb-6 bg-gray-50 p-4 rounded-md">
            <div class="flex items-center justify-between mb-3">
              <h4 class="font-medium text-gray-700">Adjustment Preview</h4>
              
              <div class="text-sm">
                <!-- Status indicator for operation safety -->
                <span 
                  v-if="hasStockWarnings"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                >
                  <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                  </svg>
                  Warning
                </span>
                <span 
                  v-else
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                >
                  <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                  </svg>
                  Safe
                </span>
              </div>
            </div>
            
            <div class="max-h-40 overflow-y-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Product
                    </th>
                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Current
                    </th>
                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Change
                    </th>
                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      New
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="product in selectedProducts" :key="product.id" class="hover:bg-gray-50">
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                      {{ product.title }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 text-right">
                      {{ product.quantity }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-right">
                      <span :class="getChangeClass(product)">
                        {{ showChange(product) }}
                      </span>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-right">
                      <span :class="getNewQuantityClass(product)">
                        {{ calculateNewQuantity(product) }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div v-if="errorMessage" class="mb-6 bg-red-50 p-4 rounded-md text-red-700 text-sm">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <div class="mt-2 text-sm text-red-700">
                  {{ errorMessage }}
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 flex justify-between">
          <button 
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </button>
          
          <div class="flex space-x-3">
            <button 
              v-if="selectedProductIds.length > 0"
              type="button"
              @click="applyToSelection"
              :disabled="loading || hasInvalidStockLevels"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-75 disabled:cursor-not-allowed"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Apply to {{ selectedProductIds.length }} {{ selectedProductIds.length === 1 ? 'Product' : 'Products' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from 'vue';
import { validateBulkAdjustment } from '../../utils/ValidationUtils';
import { useInventoryStatus } from '../../composables/useInventoryStatus';
import InventoryService from '../../services/InventoryService';

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

const emit = defineEmits(['close', 'success']);

// State
const selectedProductIds = ref([]);
const categoryFilter = ref('');
const searchFilter = ref('');
const stockStatusFilter = ref('');
const adjustmentType = ref('add');
const quantity = ref(1);
const reference = ref('');
const notes = ref('');
const loading = ref(false);
const errorMessage = ref('');

// Computed properties
const filteredProducts = computed(() => {
  let result = [...props.products];
  
  // Apply category filter
  if (categoryFilter.value) {
    result = result.filter(product => 
      product.category_id === categoryFilter.value ||
      (product.category && product.category.id === categoryFilter.value)
    );
  }
  
  // Apply search filter
  if (searchFilter.value) {
    const searchTerm = searchFilter.value.toLowerCase();
    result = result.filter(product => 
      product.title.toLowerCase().includes(searchTerm) ||
      (product.sku && product.sku.toLowerCase().includes(searchTerm))
    );
  }
  
  // Apply stock status filter
  if (stockStatusFilter.value) {
    switch (stockStatusFilter.value) {
      case 'in_stock':
        result = result.filter(product => 
          product.track_inventory && 
          product.quantity > 0 && 
          product.quantity > (product.reorder_level || 5)
        );
        break;
      case 'low_stock':
        result = result.filter(product => 
          product.track_inventory && 
          product.quantity > 0 && 
          product.quantity <= (product.reorder_level || 5)
        );
        break;
      case 'out_of_stock':
        result = result.filter(product => 
          product.track_inventory && 
          product.quantity === 0
        );
        break;
    }
  }
  
  return result;
});

const selectedProducts = computed(() => {
  return props.products.filter(product => 
    selectedProductIds.value.includes(product.id)
  );
});

const isAllProductsSelected = computed(() => {
  return filteredProducts.value.length > 0 && 
         filteredProducts.value.every(product => selectedProductIds.value.includes(product.id));
});

const quantityLabel = computed(() => {
  switch (adjustmentType.value) {
    case 'add': return 'Quantity to Add';
    case 'remove': return 'Quantity to Remove';
    case 'set': return 'Set Quantity To';
    default: return 'Quantity';
  }
});

// Check if any products would have negative stock after adjustment
const hasInvalidStockLevels = computed(() => {
  if (adjustmentType.value !== 'remove') return false;
  
  return selectedProducts.value.some(product => {
    return product.quantity < quantity.value;
  });
});

// Check if there are any stock warnings (invalid levels or drastic changes)
const hasStockWarnings = computed(() => {
  if (hasInvalidStockLevels.value) return true;
  
  // Check for drastic stock changes (e.g., increasing by more than 200%)
  return selectedProducts.value.some(product => {
    const newQty = calculateNewQuantity(product);
    const currentQty = product.quantity || 0;
    
    if (currentQty === 0) return false;
    
    // If new quantity is more than 3x the current quantity
    if (adjustmentType.value === 'add' || adjustmentType.value === 'set') {
      return newQty > currentQty * 3;
    }
    
    // If removing more than 90% of current stock
    if (adjustmentType.value === 'remove') {
      return quantity.value > currentQty * 0.9;
    }
    
    return false;
  });
});

// Methods
function toggleAllProducts() {
  if (isAllProductsSelected.value) {
    // Deselect all visible products
    const filteredIds = filteredProducts.value.map(p => p.id);
    selectedProductIds.value = selectedProductIds.value.filter(id => !filteredIds.includes(id));
  } else {
    // Select all visible products
    const filteredIds = filteredProducts.value.map(p => p.id);
    selectedProductIds.value = [...new Set([...selectedProductIds.value, ...filteredIds])];
  }
}

function removeProduct(productId) {
  selectedProductIds.value = selectedProductIds.value.filter(id => id !== productId);
}

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
  if (diff < 0) return diff.toString();
  return 'No change';
}

function getChangeClass(product) {
  const currentQty = product.quantity || 0;
  const newQty = calculateNewQuantity(product);
  const diff = newQty - currentQty;
  
  if (diff > 0) return 'text-green-600';
  if (diff < 0) return 'text-red-600';
  return 'text-gray-500';
}

function getNewQuantityClass(product) {
  const newQty = calculateNewQuantity(product);
  
  if (newQty === 0) return 'text-red-600';
  if (newQty <= (product.reorder_level || 5)) return 'text-yellow-600';
  return 'text-green-600';
}

function getStockClass(product) {
  if (!product.track_inventory) return 'text-gray-500';
  if (product.quantity === 0) return 'text-red-600';
  if (product.quantity <= (product.reorder_level || 5)) return 'text-yellow-600';
  return 'text-green-600';
}
// Add this to the state section of the script
const selectedProductId = ref(null);
const adjustmentProduct = ref(null);
const products = computed(() => props.products); // Reference the props.products directly

// This function loads a specific product, looking first in local data before making API call
async function loadSelectedProduct() {
  if (!selectedProductId.value) return;
  
  // First look in existing products array
  const selectedProduct = products.value.find(p => p.id == selectedProductId.value);
  
  if (selectedProduct) {
    adjustmentProduct.value = selectedProduct;
    return;
  }
  
  // Only fetch if not found in existing data
  loading.value = true;
  errorMessage.value = '';
  
  try {
    const response = await InventoryService.getProduct(selectedProductId.value);
    adjustmentProduct.value = response.data;
  } catch (error) {
    console.error('Error loading product:', error);
    
    // Handle error using existing error display pattern
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = 'Failed to load product details.';
    }
    
    adjustmentProduct.value = null;
  } finally {
    loading.value = false;
  }
}

async function applyToSelection() {
  if (selectedProductIds.value.length === 0) {
    errorMessage.value = 'Please select at least one product';
    return;
  }
  
  if (quantity.value <= 0) {
    errorMessage.value = 'Quantity must be greater than zero';
    return;
  }
  
  if (hasInvalidStockLevels.value) {
    errorMessage.value = 'Some products would have negative stock after this adjustment';
    return;
  }
  
  // Validate form
  const adjustmentData = {
    selectedProductIds: selectedProductIds.value,
    adjustmentType: adjustmentType.value,
    quantity: quantity.value,
    reference: reference.value,
    notes: notes.value
  };
  
  const { isValid, errors } = validateBulkAdjustment(adjustmentData);
  
  if (!isValid) {
    errorMessage.value = Object.values(errors)[0];
    return;
  }
  
  // Clear any previous error
  errorMessage.value = '';
  loading.value = true;
  
  try {
    // Prepare the adjustments
    const adjustments = selectedProductIds.value.map(productId => {
      const product = props.products.find(p => p.id === productId);
      if (!product) return null;
      
      // Determine the actual quantity change based on adjustment type
      let adjustmentQty = parseInt(quantity.value, 10) || 0;
      let adjustmentTypeApi = 'purchase'; // Default for adding stock
      
      if (adjustmentType.value === 'remove') {
        // For removal, make sure we don't remove more than available
        adjustmentQty = Math.min(adjustmentQty, product.quantity || 0);
        adjustmentTypeApi = 'adjustment';
      } else if (adjustmentType.value === 'set') {
        adjustmentQty = adjustmentQty - (product.quantity || 0);
        if (adjustmentQty === 0) return null; // Skip if no change
        adjustmentTypeApi = adjustmentQty > 0 ? 'purchase' : 'adjustment';
        // For adjustment (negative), convert to positive number as the API may expect
        if (adjustmentQty < 0) {
          adjustmentQty = Math.abs(adjustmentQty);
        }
      }
      
      // Ensure we're not sending any problematic values
      if (adjustmentQty === 0) return null;
      
      return {
        product_id: parseInt(productId, 10), // Ensure product_id is an integer
        quantity: Math.abs(adjustmentQty), // Ensure quantity is positive
        type: adjustmentTypeApi,
        reference: reference.value || `Bulk ${adjustmentType.value} adjustment`,
        notes: notes.value || `Adjusted ${Math.abs(adjustmentQty)} pieces via bulk operation`
      };
    }).filter(Boolean); // Remove null entries
    
    // Check if we have any adjustments to make
    if (adjustments.length === 0) {
      errorMessage.value = 'No valid adjustments to make. Please check your selections.';
      loading.value = false;
      return;
    }
    
    // Log the payload for debugging
    console.log('Sending adjustments:', adjustments);
    
    // Execute the bulk adjustment
    const response = await InventoryService.bulkAdjustInventory(adjustments);
    
    emit('success', {
      results: response.data,
      adjustmentType: adjustmentType.value,
      quantity: quantity.value,
      productCount: adjustments.length
    });
  } catch (error) {
    console.error('Error adjusting inventory:', error);
    
    // Log detailed error information if available
    if (error.response) {
      console.error('Error response:', error.response.data);
    }
    
    // Show appropriate error message
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message;
    } else if (error.response && error.response.data && error.response.data.errors) {
      // Handle Laravel validation errors format
      const firstError = Object.values(error.response.data.errors)[0];
      errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError;
    } else {
      errorMessage.value = 'Failed to communicate with the server. Please try again.';
    }
  } finally {
    loading.value = false;
  }
}
</script>