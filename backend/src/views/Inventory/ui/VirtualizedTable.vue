<template>
    <div class="virtualized-table">
      <!-- Table header -->
      <div class="overflow-hidden bg-gray-50 border-b border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th 
                v-for="column in columns" 
                :key="column.key"
                :class="[
                  'px-6 py-3 text-xs font-medium tracking-wider',
                  column.align === 'right' ? 'text-right' : 'text-left',
                  getColumnClass(column)
                ]"
                @click="handleColumnSort(column)"
              >
                <div class="flex items-center">
                  <span>{{ column.label }}</span>
                  <span v-if="column.sortable" class="ml-1 flex-none rounded">
                    <svg
                      v-if="sortKey === column.key && sortOrder === 'asc'"
                      class="h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.707a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 0 1-1.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <svg
                      v-else-if="sortKey === column.key && sortOrder === 'desc'"
                      class="h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M14.707 12.293a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L10 15.586l3.293-3.293a1 1 0 0 1 1.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <svg
                      v-else-if="column.sortable"
                      class="h-4 w-4 text-gray-400"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 0 1 1 1v10.586l3.293-3.293a1 1 0 0 1 1.414 1.414l-5 5a1 1 0 0 1-1.414 0l-5-5a1 1 0 1 1 1.414-1.414L9 14.586V4a1 1 0 0 1 1-1z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </span>
                </div>
              </th>
            </tr>
          </thead>
        </table>
      </div>
  
      <!-- Table body with virtualization -->
      <div 
        ref="tableBodyContainer"
        class="overflow-auto"
        :style="{ height: `${bodyHeight}px` }"
        @scroll="handleScroll"
      >
        <div :style="{ height: `${totalHeight}px`, position: 'relative' }">
          <table 
            class="min-w-full divide-y divide-gray-200"
            :style="{ position: 'absolute', top: `${scrollTop}px` }"
          >
            <tbody class="bg-white divide-y divide-gray-200">
              <tr 
                v-for="(item, index) in visibleItems" 
                :key="getItemKey(item, index)"
                class="hover:bg-gray-50"
              >
                <td 
                  v-for="column in columns" 
                  :key="column.key"
                  :class="[
                    'px-6 py-4 whitespace-nowrap text-sm',
                    column.align === 'right' ? 'text-right' : 'text-left',
                    getColumnClass(column)
                  ]"
                >
                  <slot 
                    :name="column.key" 
                    :item="item" 
                    :value="getCellValue(item, column.key)"
                  >
                    {{ formatCellValue(getCellValue(item, column.key), column.format) }}
                  </slot>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Empty state -->
      <div v-if="items.length === 0" class="py-6 text-center">
        <p class="text-gray-500">{{ emptyText }}</p>
      </div>
      
      <!-- Loading indicator -->
      <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
        <div class="spinner"></div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
  
  const props = defineProps({
    columns: {
      type: Array,
      required: true
    },
    items: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    rowHeight: {
      type: Number,
      default: 48 // Pixels
    },
    bodyHeight: {
      type: Number,
      default: 400 // Pixels
    },
    emptyText: {
      type: String,
      default: 'No data available'
    },
    initialSortKey: {
      type: String,
      default: ''
    },
    initialSortOrder: {
      type: String,
      default: 'asc' // 'asc' or 'desc'
    }
  });
  
  const emit = defineEmits(['sort', 'scroll-end']);
  
  const tableBodyContainer = ref(null);
  const sortKey = ref(props.initialSortKey);
  const sortOrder = ref(props.initialSortOrder);
  const scrollTop = ref(0);
  const visibleItems = ref([]);
  const buffer = 5; // Number of items to render above/below visible area
  
  // Total height of all rows
  const totalHeight = computed(() => {
    return props.items.length * props.rowHeight;
  });
  
  // Handle column sorting
  function handleColumnSort(column) {
    if (!column.sortable) return;
    
    if (sortKey.value === column.key) {
      // Toggle sort order
      sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
      // New sort column
      sortKey.value = column.key;
      sortOrder.value = 'asc';
    }
    
    emit('sort', { key: sortKey.value, order: sortOrder.value });
  }
  
  // Get column class
  function getColumnClass(column) {
    return {
      'cursor-pointer': column.sortable,
      'text-gray-500 uppercase': true,
      ...(column.headerClass || {})
    };
  }
  
  // Get cell value from item
  function getCellValue(item, key) {
    const keys = key.split('.');
    let value = item;
    
    for (const k of keys) {
      if (value === null || value === undefined) return '';
      value = value[k];
    }
    
    return value;
  }
  
  // Format cell value based on column format
  function formatCellValue(value, format) {
    if (value === null || value === undefined) return '';
    
    if (!format) return value;
    
    switch (format.type) {
      case 'date':
        return formatDate(value, format.pattern || 'MM/DD/YYYY');
      case 'currency':
        return formatCurrency(value, format.locale, format.options);
      case 'number':
        return formatNumber(value, format.locale, format.options);
      case 'boolean':
        return value ? format.trueText || 'Yes' : format.falseText || 'No';
      default:
        return value;
    }
  }
  
  // Get unique key for item
  function getItemKey(item, index) {
    return item.id || index;
  }
  
  // Handle scroll event
  function handleScroll() {
    if (!tableBodyContainer.value) return;
    
    const { scrollTop: newScrollTop, scrollHeight, clientHeight } = tableBodyContainer.value;
    scrollTop.value = newScrollTop;
    
    // Update visible items
    updateVisibleItems();
    
    // Detect if scrolled to the bottom
    if (scrollHeight - newScrollTop - clientHeight < 50) {
      emit('scroll-end');
    }
  }
  
  // Update visible items based on current scroll position
  function updateVisibleItems() {
    if (!tableBodyContainer.value) return;
    
    const { scrollTop: currentScrollTop, clientHeight } = tableBodyContainer.value;
    
    // Calculate visible range with buffer
    const startIndex = Math.max(0, Math.floor(currentScrollTop / props.rowHeight) - buffer);
    const endIndex = Math.min(
      props.items.length - 1,
      Math.ceil((currentScrollTop + clientHeight) / props.rowHeight) + buffer
    );
    
    // Update visible items
    visibleItems.value = props.items.slice(startIndex, endIndex + 1);
    
    // Calculate new scroll position relative to the window
    scrollTop.value = startIndex * props.rowHeight;
  }
  
  // Watch for changes in items to update visible items
  watch(() => props.items, () => {
    updateVisibleItems();
  }, { deep: true });
  
  // Watch for changes in container height
  watch(() => props.bodyHeight, () => {
    updateVisibleItems();
  });
  
  // Setup resize observer to handle container resizing
  let resizeObserver = null;
  
  onMounted(() => {
    updateVisibleItems();
    
    // Create resize observer
    resizeObserver = new ResizeObserver(() => {
      updateVisibleItems();
    });
    
    if (tableBodyContainer.value) {
      resizeObserver.observe(tableBodyContainer.value);
    }
  });
  
  onUnmounted(() => {
    if (resizeObserver) {
      resizeObserver.disconnect();
    }
  });
  </script>
  
  <style scoped>
  .virtualized-table {
    position: relative;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
  }
  
  .spinner {
    border: 3px solid rgba(156, 163, 175, 0.2);
    border-radius: 50%;
    border-top-color: #6366f1;
    width: 2rem;
    height: 2rem;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  </style>