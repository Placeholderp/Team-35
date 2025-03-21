<!-- src/components/Inventory/ExportModal.vue -->
<template>
  <div v-if="modelValue" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="$emit('update:modelValue', false)"></div>
      
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
        <div>
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Export Inventory Data
            </h3>
            <button 
              @click="$emit('update:modelValue', false)"
              class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500"
            >
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="mt-2">
            <p class="text-sm text-gray-500">
              Choose the format and data you want to export.
            </p>
          </div>
        </div>
        
        <!-- Export options -->
        <div class="mt-5 space-y-4">
          <div>
            <h4 class="text-sm font-medium text-gray-700">Export options</h4>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="relative border rounded-md p-4 flex cursor-pointer focus:outline-none" 
                   :class="{ 'border-indigo-600': exportType === 'products', 'border-gray-200': exportType !== 'products' }"
                   @click="exportType = 'products'">
                <div class="flex items-center h-5">
                  <input id="export-products" type="radio" name="export-type" 
                         class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                         :checked="exportType === 'products'"
                         @change="exportType = 'products'" />
                </div>
                <div class="ml-3 flex flex-col">
                  <label for="export-products" class="text-sm font-medium text-gray-700">
                    Products
                  </label>
                  <span class="text-xs text-gray-500">Export all product data with current stock levels</span>
                </div>
              </div>
              <div class="relative border rounded-md p-4 flex cursor-pointer focus:outline-none"
                   :class="{ 'border-indigo-600': exportType === 'movements', 'border-gray-200': exportType !== 'movements' }"
                   @click="exportType = 'movements'">
                <div class="flex items-center h-5">
                  <input id="export-movements" type="radio" name="export-type" 
                         class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                         :checked="exportType === 'movements'"
                         @change="exportType = 'movements'" />
                </div>
                <div class="ml-3 flex flex-col">
                  <label for="export-movements" class="text-sm font-medium text-gray-700">
                    Movements
                  </label>
                  <span class="text-xs text-gray-500">Export inventory movement history</span>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <h4 class="text-sm font-medium text-gray-700">File format</h4>
            <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div class="relative border rounded-md p-4 flex cursor-pointer focus:outline-none"
                   :class="{ 'border-indigo-600': fileFormat === 'csv', 'border-gray-200': fileFormat !== 'csv' }"
                   @click="fileFormat = 'csv'">
                <div class="flex items-center h-5">
                  <input id="format-csv" type="radio" name="file-format" 
                         class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                         :checked="fileFormat === 'csv'"
                         @change="fileFormat = 'csv'" />
                </div>
                <div class="ml-3 flex flex-col">
                  <label for="format-csv" class="text-sm font-medium text-gray-700">
                    CSV
                  </label>
                </div>
              </div>
              <div class="relative border rounded-md p-4 flex cursor-pointer focus:outline-none"
                   :class="{ 'border-indigo-600': fileFormat === 'excel', 'border-gray-200': fileFormat !== 'excel' }"
                   @click="fileFormat = 'excel'">
                <div class="flex items-center h-5">
                  <input id="format-excel" type="radio" name="file-format" 
                         class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                         :checked="fileFormat === 'excel'"
                         @change="fileFormat = 'excel'" />
                </div>
                <div class="ml-3 flex flex-col">
                  <label for="format-excel" class="text-sm font-medium text-gray-700">
                    Excel
                  </label>
                </div>
              </div>
              <div class="relative border rounded-md p-4 flex cursor-pointer focus:outline-none"
                   :class="{ 'border-indigo-600': fileFormat === 'pdf', 'border-gray-200': fileFormat !== 'pdf' }"
                   @click="fileFormat = 'pdf'">
                <div class="flex items-center h-5">
                  <input id="format-pdf" type="radio" name="file-format" 
                         class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                         :checked="fileFormat === 'pdf'"
                         @change="fileFormat = 'pdf'" />
                </div>
                <div class="ml-3 flex flex-col">
                  <label for="format-pdf" class="text-sm font-medium text-gray-700">
                    PDF
                  </label>
                </div>
              </div>
            </div>
          </div>
          
          <div class="bg-gray-50 p-4 rounded-md">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-gray-700">
                  {{ exportType === 'products' ? productsInfo : movementsInfo }}
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Action buttons -->
        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
          <button
            type="button"
            @click="exportData"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
            :disabled="loading"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Exporting...' : 'Export' }}
          </button>
          <button
            type="button"
            @click="$emit('update:modelValue', false)"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { formatDate } from '../../utils/Formatters';

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  products: {
    type: Array,
    default: () => []
  },
  movements: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:modelValue', 'close']);

const exportType = ref('products');
const fileFormat = ref('csv');
const loading = ref(false);

// Information text based on selection
const productsInfo = computed(() => {
  return `You will export ${props.products.length} products with their current inventory status.`;
});

const movementsInfo = computed(() => {
  return `You will export inventory movement history.`;
});

// Generate filename with current date
const getFileName = () => {
  const date = new Date().toISOString().split('T')[0];
  const type = exportType.value;
  const format = fileFormat.value === 'excel' ? 'xlsx' : fileFormat.value;
  
  return `inventory_${type}_${date}.${format}`;
};

// Export data function
const exportData = async () => {
  loading.value = true;
  
  try {
    // Generate CSV content directly in the browser
    let content = '';
    if (exportType.value === 'products') {
      content = generateProductsCsv();
    } else {
      content = generateMovementsCsv();
    }
    
    // Create blob and download
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', getFileName());
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Success - close modal
    emit('update:modelValue', false);
    
  } catch (error) {
    console.error('Export failed:', error);
    // Could add error notification here
  } finally {
    loading.value = false;
  }
};

// Generate CSV for products
const generateProductsCsv = () => {
  const headers = ['ID', 'SKU', 'Product Name', 'Category', 'Current Stock', 'Reorder Level', 'Price', 'Status'];
  
  const rows = props.products.map(product => {
    const status = getProductStatus(product);
    const category = product.category ? product.category.name : '';
    
    return [
      product.id,
      product.sku || '',
      product.title,
      category,
      product.quantity || 0,
      product.reorder_level || 5,
      product.price || 0,
      status
    ];
  });
  
  return [
    headers.join(','),
    ...rows.map(row => row.join(','))
  ].join('\n');
};

// Generate CSV for movements
const generateMovementsCsv = () => {
  const headers = ['Date', 'Product', 'Type', 'Quantity', 'Previous Stock', 'New Stock', 'Reference'];
  
  const rows = props.movements.map(movement => {
    return [
      formatDate(movement.created_at),
      movement.product ? movement.product.title : 'Unknown Product',
      formatMovementType(movement.type),
      movement.quantity,
      movement.previous_quantity,
      movement.new_quantity,
      movement.reference || ''
    ];
  });
  
  return [
    headers.join(','),
    ...rows.map(row => row.join(','))
  ].join('\n');
};

// Helper functions
const getProductStatus = (product) => {
  if (!product.track_inventory) return 'Not Tracked';
  if (product.quantity === 0) return 'Out of Stock';
  if (product.quantity <= (product.reorder_level || 5)) return 'Low Stock';
  return 'In Stock';
};

const formatMovementType = (type) => {
  const typeMap = {
    'purchase': 'Purchase',
    'sale': 'Sale',
    'adjustment': 'Adjustment',
    'return': 'Return',
    'initial': 'Initial Stock'
  };
  
  return typeMap[type] || type;
};
</script>