<!-- ProductDetail.vue -->
<!-- ProductDetail.vue -->
<template>
  <div v-if="loading" class="flex justify-center items-center py-12">
    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-500" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  
  <div v-else-if="product" class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="flex flex-col md:flex-row">
      <!-- Product Image Section -->
      <div class="md:w-2/5 relative">
        <img 
          v-if="product.image_url" 
          :src="getImageUrl(product.image_url, true)" 
          :alt="product.title"
          class="w-full h-full object-cover object-center"
          @error="handleImageError"
        />
        <div 
          v-else
          class="w-full h-full min-h-[300px] bg-gray-100 flex items-center justify-center"
        >
          <svg class="h-24 w-24 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        
        <!-- Stock Badge -->
        <div class="absolute top-4 right-4">
          <span 
            :class="getStockBadgeClass(product)"
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
          >
            {{ getStockBadgeText(product) }}
          </span>
        </div>
      </div>
      
      <!-- Product Details Section -->
      <div class="md:w-3/5 p-6 md:p-8">
        <div class="flex flex-col h-full">
          <!-- Header -->
          <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ product.title }}</h1>
            <p class="text-3xl font-bold text-indigo-600">{{ formatCurrency(product.price) }}</p>
          </div>
          
          <!-- Description -->
          <div class="mb-6 flex-grow">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Description</h2>
            <p class="text-gray-600">{{ product.description || 'No description available' }}</p>
          </div>
          
          <!-- Stock Information -->
          <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Stock Information</h2>
            <div class="bg-gray-50 rounded-lg p-4">
              <div v-if="product.track_inventory" class="flex flex-col space-y-2">
                <!-- Current Stock Level -->
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Current Stock:</span>
                  <span :class="getStockTextClass(product)" class="font-medium">
                    {{ product.quantity }} units
                  </span>
                </div>
                
                <!-- Stock Status -->
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Status:</span>
                  <span :class="getStockTextClass(product)" class="font-medium">
                    {{ getStockStatusText(product) }}
                  </span>
                </div>
                
                <!-- Reorder Level -->
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Reorder Level:</span>
                  <span class="font-medium">{{ product.reorder_level || 5 }} units</span>
                </div>
                
                <!-- Stock Progress Bar -->
                <div class="mt-2">
                  <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div 
                      :class="getStockBarColor(product)"
                      class="h-2.5 rounded-full" 
                      :style="{ width: getStockPercentage(product) }"
                    ></div>
                  </div>
                </div>
              </div>
              <div v-else class="text-gray-500">
                Inventory tracking is not enabled for this product.
              </div>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <button
              @click="$emit('edit-product', product)"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Product
            </button>
            
            <button
              v-if="product.track_inventory"
              @click="showAdjustInventoryModal = true"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
              </svg>
              Adjust Inventory
            </button>

            <!-- Add Stock Button -->
            <button
              v-if="product.track_inventory"
              @click="showAddStockModal = true"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Add Stock
            </button>
            
            <button
              v-else
              @click="enableInventoryTracking"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Enable Inventory Tracking
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Inventory movements section -->
    <div v-if="product.track_inventory && inventoryMovements.length > 0" class="border-t border-gray-200">
      <div class="px-6 py-4 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-700">Inventory Movement History</h3>
        <p class="text-sm text-gray-500">Recent changes to product inventory</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Before/After</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="movement in inventoryMovements" :key="movement.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(movement.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="getMovementTypeClass(movement.type)"
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ movement.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span 
                  :class="movement.quantity > 0 ? 'text-green-600' : 'text-red-600'"
                  class="font-medium"
                >
                  {{ movement.quantity > 0 ? '+' : '' }}{{ movement.quantity }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ movement.previous_quantity }} → {{ movement.new_quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ movement.reference || '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ movement.notes || '—' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <div v-else class="bg-white rounded-lg shadow-lg p-8 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-2 text-lg font-medium text-gray-900">Product not found</h3>
    <p class="mt-1 text-sm text-gray-500">The product you're looking for doesn't exist or has been removed.</p>
    <div class="mt-6">
      <button 
        @click="$router.push({ name: 'app.products' })"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Go back to products
      </button>
    </div>
  </div>
  
  <!-- Adjust Inventory Modal -->
  <div v-if="showAdjustInventoryModal" class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showAdjustInventoryModal = false"></div>
      
      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Adjust Inventory
              </h3>
              <div class="mt-4">
                <form @submit.prevent="submitInventoryAdjustment">
                  <div class="grid grid-cols-1 gap-y-4">
                    <!-- Quantity Adjustment -->
                    <div>
                      <label for="adjustment-quantity" class="block text-sm font-medium text-gray-700">
                        Quantity Change
                      </label>
                      <div class="mt-1 flex rounded-md shadow-sm">
                        <div class="relative flex items-stretch flex-grow">
                          <div class="absolute inset-y-0 left-0 flex items-center">
                            <select
                              v-model="adjustmentDirection"
                              class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-3 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-l-md"
                            >
                              <option value="increase">+</option>
                              <option value="decrease">-</option>
                            </select>
                          </div>
                          <input
                            type="number"
                            id="adjustment-quantity"
                            v-model="adjustmentQuantity"
                            min="1"
                            required
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-16 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Quantity"
                          />
                        </div>
                      </div>
                      <p class="mt-1 text-xs text-gray-500">
                        Current quantity: <strong>{{ product.quantity }}</strong> units
                      </p>
                      <p v-if="newQuantity < 0" class="mt-1 text-xs text-red-500">
                        Warning: This will result in negative inventory.
                      </p>
                    </div>

                    <!-- Adjustment Type -->
                    <div>
                      <label for="adjustment-type" class="block text-sm font-medium text-gray-700">
                        Adjustment Type
                      </label>
                      <select
                        id="adjustment-type"
                        v-model="adjustmentType"
                        required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                      >
                        <option value="purchase">Purchase</option>
                        <option value="sale">Sale</option>
                        <option value="return">Return</option>
                        <option value="adjustment">Manual Adjustment</option>
                        <option value="loss">Loss/Damage</option>
                      </select>
                    </div>

                    <!-- Reference -->
                    <div>
                      <label for="adjustment-reference" class="block text-sm font-medium text-gray-700">
                        Reference (Optional)
                      </label>
                      <input
                        type="text"
                        id="adjustment-reference"
                        v-model="adjustmentReference"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Order #, Invoice #, etc."
                      />
                    </div>

                    <!-- Notes -->
                    <div>
                      <label for="adjustment-notes" class="block text-sm font-medium text-gray-700">
                        Notes (Optional)
                      </label>
                      <textarea
                        id="adjustment-notes"
                        v-model="adjustmentNotes"
                        rows="3"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Additional details about this adjustment"
                      ></textarea>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="submitInventoryAdjustment"
            :disabled="isAdjusting"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            <svg v-if="isAdjusting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Save Adjustment
          </button>
          <button
            type="button"
            @click="showAdjustInventoryModal = false"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stock Adjustment Modal -->
  <Teleport to="body">
    <div v-if="showAddStockModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showAddStockModal = false"></div>
        <!-- This centers the modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <!-- Modal panel -->
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <QuickStockAdjustment 
            :product="product" 
            @cancel="showAddStockModal = false" 
            @success="onStockAdded"
          />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, defineEmits, defineProps } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { formatCurrency, getImageUrl, normalizeTrackInventory } from '../../utils/ProductUtils';
import axiosClient from '../../axios';
import QuickStockAdjustment from './QuickStockAdjustment.vue';
import { useStore } from 'vuex'; 

const router = useRouter();
const route = useRoute();
const store = useStore();

const emit = defineEmits(['edit-product', 'adjust-inventory', 'enable-tracking']);

const props = defineProps({
  id: {
    type: [String, Number],
    required: false
  }
});

const product = ref(null);
const loading = ref(true);
const inventoryMovements = ref([]);

// Inventory Adjustment Modal
const showAdjustInventoryModal = ref(false);
const adjustmentQuantity = ref(1);
const adjustmentDirection = ref('increase');
const adjustmentType = ref('purchase');
const adjustmentReference = ref('');
const adjustmentNotes = ref('');
const isAdjusting = ref(false);

// Quick Stock Addition Modal
const showAddStockModal = ref(false);

// Computed value for what the new quantity will be after adjustment
const newQuantity = computed(() => {
  if (!product.value) return 0;
  
  const change = parseInt(adjustmentQuantity.value) || 0;
  const currentQty = parseInt(product.value.quantity) || 0;
  
  return adjustmentDirection.value === 'increase' 
    ? currentQty + change
    : currentQty - change;
});

// Set up event listeners for keyboard shortcuts
onMounted(() => {
  fetchProduct();
  document.addEventListener('keydown', handleKeyDown);
});

// Remove event listeners on component unmount
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeyDown);
});

// Handle keyboard shortcuts
function handleKeyDown(event) {
  // Close modal on escape key
  if (event.key === 'Escape' && showAdjustInventoryModal.value) {
    showAdjustInventoryModal.value = false;
  }
  
  // Also close quick stock modal on escape
  if (event.key === 'Escape' && showAddStockModal.value) {
    showAddStockModal.value = false;
  }
}

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

function getStockStatusText(product) {
  if (isOutOfStock(product)) return 'Out of Stock';
  if (isLowStock(product)) return 'Low Stock';
  return 'In Stock';
}

// Stock bar helpers
function getStockBarColor(product) {
  if (isOutOfStock(product)) return 'bg-red-600';
  if (isLowStock(product)) return 'bg-yellow-500';
  return 'bg-green-600';
}

function getStockPercentage(product) {
  if (!product.track_inventory || product.quantity <= 0) {
    return '0%';
  }
  
  const maxStock = Math.max(product.reorder_level * 2 || 10, product.quantity);
  const percentage = Math.min(100, (product.quantity / maxStock) * 100);
  return `${percentage}%`;
}

// Movement type helpers
function getMovementTypeClass(type) {
  const typeClasses = {
    'purchase': 'bg-blue-100 text-blue-800',
    'sale': 'bg-purple-100 text-purple-800',
    'adjustment': 'bg-yellow-100 text-yellow-800',
    'return': 'bg-green-100 text-green-800',
    'loss': 'bg-red-100 text-red-800'
  };
  
  return typeClasses[type.toLowerCase()] || 'bg-gray-100 text-gray-800';
}

// Format date for display
function formatDate(dateString) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
}

// Fetch product data from API
async function fetchProduct() {
  loading.value = true;
  
  try {
    const productId = props.id || route.params.id;
    if (!productId) {
      loading.value = false;
      return;
    }
    
    const response = await axiosClient.get(`/products/${productId}`);
    product.value = response.data;
    
    // Normalize boolean values
    if (product.value) {
      product.value.track_inventory = normalizeTrackInventory(product.value.track_inventory);
    }
    
    // If inventory is tracked, fetch movement history
    if (product.value && product.value.track_inventory) {
      await fetchInventoryMovements(productId);
    }
    
  } catch (error) {
    console.error('Error fetching product:', error);
    // Handle error state
  } finally {
    loading.value = false;
  }
}

// Fetch inventory movements from API
async function fetchInventoryMovements(productId) {
  try {
    const response = await axiosClient.get(`/products/${productId}/inventory-movements`);
    inventoryMovements.value = response.data;
  } catch (error) {
    console.error('Error fetching inventory movements:', error);
    // Fail gracefully - this isn't critical functionality
  }
}

// Enable inventory tracking for this product
async function enableInventoryTracking() {
  try {
    const updatedProduct = { 
      ...product.value,
      track_inventory: true,
      quantity: 0,
      reorder_level: 5
    };
    
    const response = await axiosClient.put(`/products/${product.value.id}`, updatedProduct);
    
    // Update local product data
    product.value = response.data;
    
    // Emit event for parent component
    emit('enable-tracking', product.value);
    
    // Show success message
    alert('Inventory tracking has been enabled successfully!');
    
    // Refresh inventory movements
    await fetchInventoryMovements(product.value.id);
    
  } catch (error) {
    console.error('Error enabling inventory tracking:', error);
    alert('Failed to enable inventory tracking. Please try again.');
  }
}

// Submit inventory adjustment
async function submitInventoryAdjustment() {
  isAdjusting.value = true;
  
  try {
    // Calculate the actual quantity change (positive or negative)
    const quantityChange = adjustmentDirection.value === 'increase' 
      ? parseInt(adjustmentQuantity.value)
      : -parseInt(adjustmentQuantity.value);
    
    // Create the adjustment data
    const adjustmentData = {
      quantity: quantityChange,
      type: adjustmentType.value,
      reference: adjustmentReference.value,
      notes: adjustmentNotes.value
    };
    
    // Send the adjustment to the server
    const response = await axiosClient.post(`/products/${product.value.id}/adjust-inventory`, adjustmentData);
    
    // Update the product with the new data
    product.value = response.data.product;
    
    // Add the new movement to the list
    if (response.data.movement) {
      inventoryMovements.value.unshift(response.data.movement);
    }
    
    // Close the modal and reset form
    showAdjustInventoryModal.value = false;
    resetAdjustmentForm();
    
  } catch (error) {
    console.error('Error adjusting inventory:', error);
    // Show error notification
    alert('Failed to adjust inventory. Please try again.');
  } finally {
    isAdjusting.value = false;
  }
}

// Reset the adjustment form
function resetAdjustmentForm() {
  adjustmentQuantity.value = 1;
  adjustmentDirection.value = 'increase';
  adjustmentType.value = 'purchase';
  adjustmentReference.value = '';
  adjustmentNotes.value = '';
}

// Handle successful stock addition from QuickStockAdjustment component
function onStockAdded(result) {
  // Update the local product data
  product.value.quantity = result.newTotal;
  
  // Close the modal
  showAddStockModal.value = false;
  
  // Show success notification
  store.commit('showToast', {
    type: 'success',
    message: `Added ${result.addedQuantity} pieces to inventory. New total: ${result.newTotal} pieces.`,
    title: 'Stock Updated'
  });
  
  // Refresh product data from API to ensure it's up to date
  fetchProduct();
}
</script>