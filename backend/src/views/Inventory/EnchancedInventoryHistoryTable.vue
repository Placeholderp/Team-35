<!-- src/components/inventory/EnhancedHistoryTable.vue -->
<template>
  <div>
    <!-- Filtering options -->
    <div class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div>
          <label for="movement-type" class="block text-sm font-medium text-gray-700 mb-1">Movement Type</label>
          <select
            id="movement-type"
            v-model="filters.type"
            @change="applyFilters"
            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
          >
            <option value="">All Types</option>
            <option value="purchase">Purchase</option>
            <option value="sale">Sale</option>
            <option value="adjustment">Adjustment</option>
            <option value="return">Return</option>
          </select>
        </div>
        
        <div>
          <label for="date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
          <div class="relative">
            <div class="flex">
              <input
                type="date"
                v-model="filters.startDate"
                @change="applyFilters"
                class="flex-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-l-md"
              />
              <input
                type="date"
                v-model="filters.endDate"
                @change="applyFilters"
                class="flex-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-r-md"
              />
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>
        
        <div>
          <label for="search-product" class="block text-sm font-medium text-gray-700 mb-1">Search Product</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              type="text"
              id="search-product"
              v-model="filters.search"
              @input="debounceSearch"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Search by product name or SKU"
            />
          </div>
        </div>
      </div>
      
      <!-- Advanced filters expansion panel -->
      <div class="mt-4">
        <button 
          @click="showAdvancedFilters = !showAdvancedFilters"
          type="button"
          class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          {{ showAdvancedFilters ? 'Hide Advanced Filters' : 'Show Advanced Filters' }}
          <svg 
            class="ml-1.5 h-5 w-5" 
            :class="{ 'transform rotate-180': showAdvancedFilters }"
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 20 20" 
            fill="currentColor"
          >
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
        
        <div v-if="showAdvancedFilters" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label for="reference-filter" class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
            <input
              type="text"
              id="reference-filter"
              v-model="filters.reference"
              @input="debounceSearch"
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              placeholder="Filter by reference..."
            />
          </div>
          
          <div>
            <label for="user-filter" class="block text-sm font-medium text-gray-700 mb-1">User</label>
            <input
              type="text"
              id="user-filter"
              v-model="filters.user"
              @input="debounceSearch"
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              placeholder="Filter by user..."
            />
          </div>
          
          <div>
            <label for="quantity-type" class="block text-sm font-medium text-gray-700 mb-1">Quantity Change</label>
            <select
              id="quantity-type"
              v-model="filters.quantityType"
              @change="applyFilters"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option value="">All Changes</option>
              <option value="positive">Additions Only</option>
              <option value="negative">Reductions Only</option>
            </select>
          </div>
        </div>
        
        <!-- Active filters -->
        <div v-if="hasActiveFilters" class="mt-4">
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-medium text-gray-500">Active filters:</span>
            
            <button 
              v-if="hasActiveFilters"
              @click="clearAllFilters"
              class="inline-flex items-center bg-indigo-100 px-2 py-0.5 rounded-full text-xs font-medium text-indigo-800 hover:bg-indigo-200"
            >
              Clear all filters
              <svg class="ml-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Inventory Movements Table -->
    <div class="overflow-x-auto">
      <VirtualizedTable
        :columns="columns"
        :items="filteredMovements"
        :loading="loading"
        :row-height="72"
        :body-height="600"
        empty-text="No inventory movements found matching your filters"
        @sort="handleSort"
        @scroll-end="$emit('loadMore')"
      >
        <!-- Date column slot -->
        <template #date="{ item }">
          <div class="text-sm text-gray-900">{{ formatDate(item.created_at) }}</div>
          <div class="text-xs text-gray-500">{{ formatTime(item.created_at) }}</div>
        </template>
        
        <!-- Product column slot -->
        <template #product="{ item }">
          <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
              <img v-if="item.product?.image_url" :src="item.product.image_url" :alt="item.product.title" class="h-10 w-10 rounded-full object-cover">
              <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <div class="text-sm font-medium text-gray-900">
                {{ item.product?.title || 'Unknown Product' }}
              </div>
              <div v-if="item.product?.sku" class="text-xs text-gray-500">
                SKU: {{ item.product.sku }}
              </div>
            </div>
          </div>
        </template>
        
        <!-- Movement type column slot -->
        <template #type="{ item }">
          <span :class="getMovementTypeBadgeClass(item.type)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
            {{ formatMovementType(item.type) }}
          </span>
        </template>
        
        <!-- Quantity column slot -->
        <template #quantity="{ item }">
          <div class="text-sm font-medium" :class="getQuantityClass(item.quantity)">
            {{ formatQuantity(item.quantity) }}
          </div>
        </template>
        
        <!-- Stock levels column slot -->
        <template #stockLevels="{ item }">
          <div class="text-xs text-gray-900">
            <div class="mb-1">Before: {{ item.previous_quantity }}</div>
            <div class="h-0.5 w-full bg-gray-200 relative">
              <div 
                class="absolute inset-y-0 bg-indigo-500"
                :class="{'bg-red-500': item.new_quantity < item.previous_quantity}"
                :style="{
                  left: `${Math.min(item.previous_quantity, item.new_quantity) * 100 / Math.max(item.previous_quantity, item.new_quantity, 1)}%`,
                  right: `${100 - (Math.max(item.previous_quantity, item.new_quantity) * 100 / Math.max(item.previous_quantity, item.new_quantity, 1))}%`
                }"
              ></div>
            </div>
            <div class="mt-1">After: {{ item.new_quantity }}</div>
          </div>
        </template>
        
        <!-- Reference column slot -->
        <template #reference="{ item }">
          <div class="text-sm text-gray-900">{{ item.reference || '-' }}</div>
          <div v-if="item.notes" class="text-xs text-gray-500 truncate max-w-xs" :title="item.notes">
            {{ item.notes }}
          </div>
        </template>
        
        <!-- User column slot -->
        <template #user="{ item }">
          <div class="text-sm text-gray-900">{{ item.created_by?.name || 'System' }}</div>
          <div class="text-xs text-gray-500">{{ formatRelativeTime(item.created_at) }}</div>
        </template>
        
        <!-- Actions column slot -->
        <template #actions="{ item }">
          <div class="flex space-x-2">
            <button 
              @click="viewDetails(item)"
              class="inline-flex items-center px-2 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Details
            </button>
            <button 
              v-if="canRevert(item)"
              @click="revertMovement(item)"
              class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
              Revert
            </button>
          </div>
        </template>
      </VirtualizedTable>
    </div>
    
    <!-- Movement detail modal -->
    <div v-if="showDetailModal" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showDetailModal = false"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mt-3 text-center sm:mt-0 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Movement Details
              </h3>
              
              <div class="mt-4 border-t border-gray-200 pt-4">
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm font-medium text-gray-500">Date & Time</p>
                    <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedMovement?.created_at) }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">Movement Type</p>
                    <p class="mt-1">
                      <span :class="selectedMovement ? getMovementTypeBadgeClass(selectedMovement.type) : ''" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ selectedMovement ? formatMovementType(selectedMovement.type) : '' }}
                      </span>
                    </p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">Product</p>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedMovement?.product?.title || 'Unknown Product' }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">SKU</p>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedMovement?.product?.sku || 'N/A' }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">Quantity Change</p>
                    <p class="mt-1 text-sm font-medium" :class="selectedMovement ? getQuantityClass(selectedMovement.quantity) : ''">
                      {{ selectedMovement ? formatQuantity(selectedMovement.quantity) : '' }}
                    </p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">Stock Levels</p>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ selectedMovement?.previous_quantity || 0 }} → {{ selectedMovement?.new_quantity || 0 }}
                    </p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">Reference</p>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedMovement?.reference || 'None' }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm font-medium text-gray-500">User</p>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedMovement?.created_by?.name || 'System' }}</p>
                  </div>
                  
                  <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-500">Notes</p>
                    <p class="mt-1 text-sm text-gray-900">{{ selectedMovement?.notes || 'No notes' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button
              v-if="selectedMovement && canRevert(selectedMovement)"
              type="button"
              @click="revertMovement(selectedMovement)"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
            >
              Revert This Movement
            </button>
            <button
              type="button"
              @click="showDetailModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Confirmation modal for revert -->
    <div v-if="showRevertConfirmation" class="fixed inset-0 z-20 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showRevertConfirmation = false"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Confirm Revert Operation
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Are you sure you want to revert this inventory movement? This will adjust the product's stock level back to what it was before this movement.
                </p>
                
                <div class="mt-4 p-4 bg-gray-50 rounded-md">
                  <div class="flex items-center justify-between text-sm">
                    <span class="font-medium">Product:</span>
                    <span>{{ selectedMovement?.product?.title || 'Unknown Product' }}</span>
                  </div>
                  <div class="flex items-center justify-between text-sm mt-2">
                    <span class="font-medium">Current Stock:</span>
                    <span>{{ selectedMovement?.product?.quantity || 0 }}</span>
                  </div>
                  <div class="flex items-center justify-between text-sm mt-2">
                    <span class="font-medium">After Revert:</span>
                    <span class="font-bold">{{ getRevertedQuantity() }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button
              type="button"
              @click="confirmRevert"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
            >
              Confirm Revert
            </button>
            <button
              type="button"
              @click="showRevertConfirmation = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useInventory } from '../../composables/useInventory';
import VirtualizedTable from '../Inventory/ui/VirtualizedTable.vue';
import { formatDate, formatTime, formatDateTime, formatRelativeTime } from '../../utils/Formatters';
import { debounce } from 'lodash';
import InventoryService from '../../services/InventoryService';

const props = defineProps({
  movements: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['loadMore', 'refresh']);

// Filter state
const filters = ref({
  type: '',
  startDate: '',
  endDate: '',
  search: '',
  reference: '',
  user: '',
  quantityType: ''
});

// UI state
const showAdvancedFilters = ref(false);
const showDetailModal = ref(false);
const showRevertConfirmation = ref(false);
const selectedMovement = ref(null);
const searchTimeout = ref(null);

// Table columns
const columns = [
  { key: 'date', label: 'Date & Time', sortable: true },
  { key: 'product', label: 'Product', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { key: 'quantity', label: 'Quantity', sortable: true, align: 'right' },
  { key: 'stockLevels', label: 'Stock Levels', sortable: false, align: 'right' },
  { key: 'reference', label: 'Reference', sortable: true },
  { key: 'user', label: 'User', sortable: true },
  { key: 'actions', label: 'Actions', sortable: false, align: 'center' }
];

// Filtered movements
const filteredMovements = computed(() => {
  if (!props.movements.length) return [];
  
  return props.movements.filter(movement => {
    // Type filter
    if (filters.value.type && movement.type !== filters.value.type) {
      return false;
    }
    
    // Date range filter - start date
    if (filters.value.startDate) {
      const startDate = new Date(filters.value.startDate);
      startDate.setHours(0, 0, 0, 0);
      const movementDate = new Date(movement.created_at);
      if (movementDate < startDate) {
        return false;
      }
    }
    
    // Date range filter - end date
    if (filters.value.endDate) {
      const endDate = new Date(filters.value.endDate);
      endDate.setHours(23, 59, 59, 999);
      const movementDate = new Date(movement.created_at);
      if (movementDate > endDate) {
        return false;
      }
    }
    
    // Product search
    if (filters.value.search) {
      const searchLower = filters.value.search.toLowerCase();
      const hasProduct = movement.product && (
        movement.product.title.toLowerCase().includes(searchLower) ||
        (movement.product.sku && movement.product.sku.toLowerCase().includes(searchLower))
      );
      if (!hasProduct) {
        return false;
      }
    }
    
    // Reference filter
    if (filters.value.reference && movement.reference) {
      const referenceLower = filters.value.reference.toLowerCase();
      if (!movement.reference.toLowerCase().includes(referenceLower)) {
        return false;
      }
    }
    
    // User filter
    if (filters.value.user && movement.created_by) {
      const userLower = filters.value.user.toLowerCase();
      if (!movement.created_by.name.toLowerCase().includes(userLower)) {
        return false;
      }
    }
    
    // Quantity type filter
    if (filters.value.quantityType === 'positive' && movement.quantity <= 0) {
      return false;
    }
    if (filters.value.quantityType === 'negative' && movement.quantity >= 0) {
      return false;
    }
    
    return true;
  });
});

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return Object.values(filters.value).some(value => value !== '');
});

// Debounced search
const debounceSearch = debounce(() => {
  console.log('Search term changed:', filters.value.search);
  applyFilters();
}, 300);
const applyFilters = () => {
  // Pass the current filter values to the parent component
  emit('refresh', { filters: filters.value });
};
// Clear all filters
const clearAllFilters = () => {
  filters.value = {
    type: '',
    startDate: '',
    endDate: '',
    search: '',
    reference: '',
    user: '',
    quantityType: ''
  };
  
  applyFilters();
};

// Handle sort
const handleSort = ({ key, order }) => {
  // Implement sorting logic here
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

// Open movement details modal
const viewDetails = (movement) => {
  selectedMovement.value = movement;
  showDetailModal.value = true;
};

// Check if movement can be reverted
const canRevert = (movement) => {
  // Define rules for when a movement can be reverted
  // For example, only recent movements by the current user
  
  // For demo purposes, we'll allow reverting any movement less than 24 hours old
  const now = new Date();
  const movementDate = new Date(movement.created_at);
  const hoursDifference = (now - movementDate) / (1000 * 60 * 60);
  
  return hoursDifference <= 24;
};

// Open revert confirmation modal
const revertMovement = (movement) => {
  selectedMovement.value = movement;
  showRevertConfirmation.value = true;
  
  // If detail modal is open, close it
  showDetailModal.value = false;
};

// Calculate quantity after revert
const getRevertedQuantity = () => {
  if (!selectedMovement.value) return 0;
  
  const currentQuantity = selectedMovement.value.product?.quantity || 0;
  // The revert would add the negative of the original movement's quantity
  const adjustmentQuantity = -selectedMovement.value.quantity;
  
  return currentQuantity + adjustmentQuantity;
};

// Confirm and execute revert operation
const confirmRevert = async () => {
  if (!selectedMovement.value) return;
  
  const movement = selectedMovement.value;
  const productId = movement.product_id;
  
  // The revert adjustment is the opposite of the original movement
  const revertAdjustment = {
    quantity: -movement.quantity,
    type: 'adjustment',
    reference: `Revert ${movement.type} (ID: ${movement.id})`,
    notes: `Reverting ${movement.type} movement from ${formatDateTime(movement.created_at)}`
  };
  
  try {
    await InventoryService.adjustInventory(productId, revertAdjustment);
    
    // Success - close modal and refresh
    showRevertConfirmation.value = false;
    emit('refresh');
  } catch (error) {
    console.error('Failed to revert movement:', error);
    // Handle error (show error message, etc.)
  }
};
</script>