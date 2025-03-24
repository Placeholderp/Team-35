<!-- src/components/inventory/EnhancedInventoryAlert.vue -->
<template>
  <div v-if="product && product.track_inventory"
       class="rounded-md p-4 mb-4 transition-all duration-200 overflow-hidden"
       :class="alertClass">
    <div class="flex">
      <div class="flex-shrink-0">
        <component :is="alertIcon" class="h-5 w-5" :class="iconClass" aria-hidden="true" />
      </div>
      <div class="ml-3 w-full">
        <div class="flex justify-between items-start">
          <h3 class="text-sm font-medium" :class="textClass">
            {{ alertTitle }}
            <span v-if="trend" class="inline-block ml-1 text-xs font-normal">
              <span v-if="trend === 'up'" class="text-green-600">↑</span>
              <span v-else-if="trend === 'down'" class="text-red-600">↓</span>
              {{ trendAmount }}
            </span>
          </h3>
          <div v-if="showActions" class="relative ml-4">
            <button 
              @click="showMenu = !showMenu"
              class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2"
              :class="buttonBgClass"
              aria-haspopup="true"
              :aria-expanded="showMenu"
            >
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
              </svg>
            </button>
            
            <div v-if="showMenu" 
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                 role="menu" 
                 aria-orientation="vertical" 
                 tabindex="-1">
              <div class="py-1" role="none">
                <button
                  @click="executeAction('adjust')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                  tabindex="-1"
                >
                  {{ actionText }}
                </button>
                <button
                  @click="executeAction('history')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                  tabindex="-1"
                >
                  View Movement History
                </button>
                <button
                  v-if="isOutOfStock || isLowStock"
                  @click="executeAction('order')"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  role="menuitem"
                  tabindex="-1"
                >
                  Create Purchase Order
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-2 text-sm" :class="textClass">
          <p>{{ alertMessage }}</p>
        </div>
        
        <!-- Stock level visualization -->
        <div v-if="product.track_inventory && product.reorder_level > 0" class="mt-3">
          <div class="relative pt-1">
            <div class="flex mb-1 items-center justify-between">
              <div class="text-xs text-gray-500">
                0
              </div>
              <div class="text-xs text-gray-500">
                Reorder: {{ product.reorder_level }}
              </div>
              <div class="text-xs text-gray-500">
                Target: {{ targetLevel }}
              </div>
            </div>
            <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-gray-200">
              <div 
                :style="{ width: `${stockPercentage}%` }" 
                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-500"
                :class="barColor"
              ></div>
            </div>
            <div class="text-right text-xs text-gray-500">
              Current: {{ product.quantity }}
            </div>
          </div>
        </div>
        
        <div v-if="recommendation" class="mt-3 text-sm font-medium flex items-center" :class="recommendationClass">
          <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          {{ recommendation }}
        </div>
        
        <div v-if="showAction" class="mt-4">
          <div class="-mx-2 -my-1.5 flex">
            <button
              type="button"
              @click="executeAction('adjust')"
              class="rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2"
              :class="buttonClass"
            >
              {{ actionText }}
            </button>
            
            <button
              v-if="isOutOfStock || isLowStock"
              type="button"
              @click="executeAction('order')"
              class="ml-3 rounded-md px-2 py-1.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Create Purchase Order
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits, onMounted, onUnmounted, watch } from 'vue';
import { useInventoryStatus } from '../../composables/useInventoryStatus';

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  movementHistory: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['action-click', 'history-click', 'order-click']);

const showMenu = ref(false);

// Use the inventory status composable
const productRef = computed(() => props.product);
const {
  isOutOfStock,
  isLowStock,
  stockStatus,
  statusText,
  statusClasses
} = useInventoryStatus(productRef);

// Calculate target stock level (2x reorder level or at least 10)
const targetLevel = computed(() => {
  if (!props.product.reorder_level) return 10;
  return Math.max(props.product.reorder_level * 2, 10);
});

// Calculate stock percentage for visualization
const stockPercentage = computed(() => {
  if (!props.product.track_inventory) return 0;
  if (targetLevel.value <= 0) return 0;
  
  const percentage = (props.product.quantity / targetLevel.value) * 100;
  return Math.min(percentage, 100);
});

// Determine stock trend based on history
const trend = computed(() => {
  if (props.movementHistory.length < 2) return null;
  
  const sortedMovements = [...props.movementHistory]
    .filter(m => m.product_id === props.product.id)
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  
  if (sortedMovements.length < 2) return null;
  
  const latestQty = sortedMovements[0].new_quantity;
  const previousQty = sortedMovements[1].new_quantity;
  
  if (latestQty > previousQty) return 'up';
  if (latestQty < previousQty) return 'down';
  return null;
});

// Calculate trend amount
const trendAmount = computed(() => {
  if (!trend.value || props.movementHistory.length < 2) return '';
  
  const sortedMovements = [...props.movementHistory]
    .filter(m => m.product_id === props.product.id)
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  
  if (sortedMovements.length < 2) return '';
  
  const latestQty = sortedMovements[0].new_quantity;
  const previousQty = sortedMovements[1].new_quantity;
  const diff = Math.abs(latestQty - previousQty);
  
  return `${diff} ${diff === 1 ? 'unit' : 'units'}`;
});

// Set alert styling based on stock status
const alertClass = computed(() => statusClasses.value.alert);
const textClass = computed(() => statusClasses.value.text);
const iconClass = computed(() => statusClasses.value.icon);
const buttonClass = computed(() => {
  switch (stockStatus.value) {
    case 'out_of_stock': return 'bg-red-100 text-red-800 hover:bg-red-200 focus:ring-red-500 focus:ring-offset-red-50';
    case 'low_stock': return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 focus:ring-yellow-500 focus:ring-offset-yellow-50';
    case 'in_stock': return 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500 focus:ring-offset-green-50';
    default: return 'bg-gray-100 text-gray-800 hover:bg-gray-200 focus:ring-gray-500 focus:ring-offset-gray-50';
  }
});

const buttonBgClass = computed(() => {
  switch (stockStatus.value) {
    case 'out_of_stock': return 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
    case 'low_stock': return 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500';
    case 'in_stock': return 'bg-green-600 hover:bg-green-700 focus:ring-green-500';
    default: return 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500';
  }
});

const barColor = computed(() => {
  if (isOutOfStock.value) return 'bg-red-500';
  if (isLowStock.value) return 'bg-yellow-500';
  return 'bg-green-500';
});

// Alert content based on stock status
const alertTitle = computed(() => statusText.value);

const alertMessage = computed(() => {
  if (isOutOfStock.value) {
    return 'This product is currently out of stock. Consider restocking soon to continue sales.';
  }
  if (isLowStock.value) {
    return `Only ${props.product.quantity} units remaining. This is below the reorder level of ${props.product.reorder_level || 5}.`;
  }
  return `Currently ${props.product.quantity} units in stock, which is above the reorder level.`;
});

// Intelligent recommendation based on stock status and history
const recommendation = computed(() => {
  if (isOutOfStock.value) {
    return `Order at least ${getRecommendedQuantity()} units as soon as possible.`;
  }
  
  if (isLowStock.value) {
    const daysUntilStockout = estimateDaysUntilStockout();
    if (daysUntilStockout) {
      return `Estimated stockout in ~${daysUntilStockout} days. Consider ordering ${getRecommendedQuantity()} units.`;
    } else {
      return `Reorder recommended. Consider ordering ${getRecommendedQuantity()} units.`;
    }
  }
  
  return null;
});

const recommendationClass = computed(() => {
  if (isOutOfStock.value) return 'text-red-800';
  if (isLowStock.value) return 'text-yellow-800';
  return 'text-gray-600';
});

// Show action button based on stock status
const showAction = computed(() => true);
const showActions = computed(() => true);

const actionText = computed(() => {
  if (isOutOfStock.value) return 'Restock Now';
  if (isLowStock.value) return 'Order More';
  return 'Adjust Inventory';
});

// Alert icons using render functions
const alertIcon = computed(() => {
  if (isOutOfStock.value) {
    return {
      render() {
        return h('svg', { 
          xmlns: 'http://www.w3.org/2000/svg', 
          viewBox: '0 0 20 20', 
          fill: 'currentColor' 
        }, [
          h('path', { 
            'fill-rule': 'evenodd',
            'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
            'clip-rule': 'evenodd'
          })
        ]);
      }
    };
  }
  
  if (isLowStock.value) {
    return {
      render() {
        return h('svg', { 
          xmlns: 'http://www.w3.org/2000/svg', 
          viewBox: '0 0 20 20', 
          fill: 'currentColor' 
        }, [
          h('path', { 
            'fill-rule': 'evenodd',
            'd': 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
            'clip-rule': 'evenodd'
          })
        ]);
      }
    };
  }
  
  return {
    render() {
      return h('svg', { 
        xmlns: 'http://www.w3.org/2000/svg', 
        viewBox: '0 0 20 20', 
        fill: 'currentColor' 
      }, [
        h('path', { 
          'fill-rule': 'evenodd',
          'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
          'clip-rule': 'evenodd'
        })
      ]);
    }
  };
});

// Utility functions
function getRecommendedQuantity() {
  // Basic implementation - in a real app, this would use sales velocity data
  if (!props.product.reorder_level) return 10;
  
  const reorderLevel = props.product.reorder_level;
  const currentStock = props.product.quantity;
  const deficit = Math.max(0, reorderLevel - currentStock);
  
  // Order enough to reach 2x reorder level or at least 10 more than current
  return Math.max(deficit + reorderLevel, 10);
}

function estimateDaysUntilStockout() {
  // If we have enough history, calculate average daily usage
  if (props.movementHistory.length < 3) return null;
  
  const salesMovements = props.movementHistory
    .filter(m => m.product_id === props.product.id && m.quantity < 0);
  
  if (salesMovements.length < 2) return null;
  
  // Sort by date, newest first
  salesMovements.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  
  // Calculate date range
  const latestDate = new Date(salesMovements[0].created_at);
  const oldestDate = new Date(salesMovements[salesMovements.length - 1].created_at);
  
  const daysDifference = Math.max(1, (latestDate - oldestDate) / (1000 * 60 * 60 * 24));
  
  // Calculate total units sold
  const totalSold = salesMovements.reduce((sum, movement) => sum + Math.abs(movement.quantity), 0);
  
  // Calculate daily average
  const dailyAverage = totalSold / daysDifference;
  
  if (dailyAverage <= 0) return null;
  
  // Estimate days until stockout
  const daysUntilStockout = Math.floor(props.product.quantity / dailyAverage);
  
  return daysUntilStockout;
}

function executeAction(action) {
  showMenu.value = false;
  
  switch (action) {
    case 'adjust':
      emit('action-click', props.product);
      break;
    case 'history':
      emit('history-click', props.product);
      break;
    case 'order':
      emit('order-click', props.product);
      break;
  }
}

// Close menu when clicking outside
function handleClickOutside(event) {
  if (showMenu.value) {
    showMenu.value = false;
  }
}

// Setup click outside listener
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

// Clean up
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>