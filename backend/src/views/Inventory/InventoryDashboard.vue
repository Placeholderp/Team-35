<!-- src/views/Inventory/EnhancedDashboard.vue -->
<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Inventory Management</h1>
          <p class="mt-2 text-sm text-gray-600">
            Track and manage product inventory levels
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
          <router-link
            :to="{name: 'app.products'}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Products
          </router-link>
          <button
            @click="refreshData"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
          </button>
          <button
            @click="showBulkAdjustmentModal = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
            Bulk Adjustment
          </button>
          <button
            @click="showExportModal = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Total Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.totalProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                In Stock Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.inStockProducts }}
                </div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                  {{ stats.totalProducts > 0 ? Math.round((stats.inStockProducts / stats.totalProducts) * 100) : 0 }}%
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Low Stock Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.lowStockProducts }}
                </div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-yellow-600">
                  {{ stats.totalProducts > 0 ? Math.round((stats.lowStockProducts / stats.totalProducts) * 100) : 0 }}%
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Out of Stock Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ stats.outOfStockProducts }}
                </div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                  {{ stats.totalProducts > 0 ? Math.round((stats.outOfStockProducts / stats.totalProducts) * 100) : 0 }}%
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
      <!-- Inventory Value Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Inventory Value</h3>
          <div class="mt-5">
            <div class="text-3xl font-semibold text-gray-900">{{ formatCurrency(inventoryValue) }}</div>
            <div class="mt-1 text-sm text-gray-500">Total value based on current stock and product prices</div>
          </div>
        </div>
      </div>
      
      <!-- Stock Health Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Stock Health</h3>
          <div class="mt-2">
            <div class="relative pt-1">
              <div class="flex mb-2 items-center justify-between">
                <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                  In Stock
                </div>
                <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-200">
                  Low Stock
                </div>
                <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">
                  Out of Stock
                </div>
              </div>
              <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div 
                  :style="{ width: `${inStockPercent}%` }" 
                  class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"
                ></div>
                <div 
                  :style="{ width: `${lowStockPercent}%` }" 
                  class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500"
                ></div>
                <div 
                  :style="{ width: `${outOfStockPercent}%` }" 
                  class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"
                ></div>
              </div>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-3 gap-3 text-center">
            <div>
              <div class="text-xl font-semibold text-green-600">{{ inStockPercent }}%</div>
              <div class="text-xs text-gray-500">Healthy</div>
            </div>
            <div>
              <div class="text-xl font-semibold text-yellow-600">{{ lowStockPercent }}%</div>
              <div class="text-xs text-gray-500">Low</div>
            </div>
            <div>
              <div class="text-xl font-semibold text-red-600">{{ outOfStockPercent }}%</div>
              <div class="text-xs text-gray-500">Out</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Action Required Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Actions Required</h3>
          <div class="mt-2">
            <ul class="divide-y divide-gray-200">
              <li v-if="stats.outOfStockProducts > 0" class="py-2">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <span class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                      <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </span>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">
                      {{ stats.outOfStockProducts }} products out of stock
                    </p>
                    <button 
                      @click="setActiveTab('out')"
                      class="mt-1 text-sm text-indigo-600 hover:text-indigo-500"
                    >
                      View and restock
                    </button>
                  </div>
                </div>
              </li>
              <li v-if="stats.lowStockProducts > 0" class="py-2">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <span class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                      <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                    </span>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">
                      {{ stats.lowStockProducts }} products running low
                    </p>
                    <button 
                      @click="setActiveTab('low')"
                      class="mt-1 text-sm text-indigo-600 hover:text-indigo-500"
                    >
                      View and reorder
                    </button>
                  </div>
                </div>
              </li>
              <li v-if="recentMovements.length > 0" class="py-2">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                      <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </span>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">
                      {{ recentMovements.length }} recent inventory changes
                    </p>
                    <button 
                      @click="setActiveTab('history')"
                      class="mt-1 text-sm text-indigo-600 hover:text-indigo-500"
                    >
                      View activity
                    </button>
                  </div>
                </div>
              </li>
              <li v-if="stats.outOfStockProducts === 0 && stats.lowStockProducts === 0 && recentMovements.length === 0" class="py-2">
                <div class="text-sm text-gray-500">
                  No immediate actions required
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <button
            @click="activeTab = 'all'"
            :class="[
              activeTab === 'all'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            All Products
            <span 
              :class="[
                activeTab === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900',
                'ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block'
              ]"
            >
              {{ stats.totalProducts }}
            </span>
          </button>
          
          <button
            @click="activeTab = 'low'"
            :class="[
              activeTab === 'low'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Low Stock
            <span 
              :class="[
                activeTab === 'low' ? 'bg-indigo-100 text-indigo-600' : 'bg-yellow-100 text-yellow-600',
                'ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block'
              ]"
            >
              {{ stats.lowStockProducts }}
            </span>
          </button>
          
          <button
            @click="activeTab = 'out'"
            :class="[
              activeTab === 'out'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            Out of Stock
            <span 
              :class="[
                activeTab === 'out' ? 'bg-indigo-100 text-indigo-600' : 'bg-red-100 text-red-600',
                'ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block'
              ]"
            >
              {{ stats.outOfStockProducts }}
            </span>
          </button>
          
          <button
            @click="activeTab = 'history'"
            :class="[
              activeTab === 'history'
                ? 'border-indigo-500 text-indigo-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            History
          </button>
        </nav>
      </div>
    </div>

    <!-- Tab Content -->
    <div v-if="activeTab === 'all'" class="bg-white shadow overflow-hidden sm:rounded-lg">
  <EnhancedProductsTable 
    :products="filteredProducts" 
    :categories="categories || []"
    :loading="loading"
    @adjust="openAdjustmentModal" 
    @refresh="refreshData"
    @view-history="viewProductHistory"
    @edit-product="editProduct"
    @generate-barcode="generateBarcode"
    @print-label="printLabel"
  />
</div>

<div v-if="activeTab === 'low'" class="bg-white shadow overflow-hidden sm:rounded-lg">
  <EnhancedProductsTable 
    :products="filteredProducts" 
    :categories="categories || []"
    :loading="loading"
    @adjust="openAdjustmentModal" 
    @refresh="refreshData"
    @view-history="viewProductHistory"
    @edit-product="editProduct"
    @generate-barcode="generateBarcode"
    @print-label="printLabel"
  />
</div>

<div v-if="activeTab === 'out'" class="bg-white shadow overflow-hidden sm:rounded-lg">
  <EnhancedProductsTable 
    :products="filteredProducts" 
    :categories="categories || []"
    :loading="loading"
    @adjust="openAdjustmentModal" 
    @refresh="refreshData"
    @view-history="viewProductHistory"
    @edit-product="editProduct"
    @generate-barcode="generateBarcode"
    @print-label="printLabel"
  />
</div>

    <div v-if="activeTab === 'history'" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <EnhancedHistoryTable 
        :movements="inventoryMovements" 
        :loading="loading"
        @refresh="refreshData"
        @loadMore="loadMoreHistory"
      />
    </div>

    <!-- Modals -->
    <EnhancedAdjustmentModal 
      v-if="showAdjustmentModal"
      v-model="showAdjustmentModal"
      :product="selectedProduct"
      @close="closeAdjustmentModal"
      @saved="onAdjustmentSaved"
    />
    
    <EnhancedBulkAdjustmentModal
      v-if="showBulkAdjustmentModal"
      v-model="showBulkAdjustmentModal"
      :products="products"
      :categories="categories"
      @close="showBulkAdjustmentModal = false"
      @success="onBulkAdjustmentSuccess"
    />
    
    <ExportModal
      v-if="showExportModal"
      v-model="showExportModal"
      :products="filteredProducts"
      @close="showExportModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useInventory } from '../../composables/useInventory';
import { formatCurrency } from '../../utils/Formatters';
import axios from 'axios'; 

import EnhancedProductsTable from '../Products/ProductsTable.vue';
import EnhancedHistoryTable from '../Inventory/EnchancedInventoryHistoryTable.vue';
import EnhancedAdjustmentModal from '../Inventory/InventoryAdjustmentModal.vue';
import EnhancedBulkAdjustmentModal from '../Inventory/EnhancedBulkSAdjustmentModal.vue'; // Fixed typo in filename
import ExportModal from '../../components/Inventory/ExportModal.vue'; // Fixed import path

const route = useRoute();
const activeTab = ref('all');
const showAdjustmentModal = ref(false);
const showBulkAdjustmentModal = ref(false);
const showExportModal = ref(false);
const selectedProduct = ref(null);
const categories = ref([]);
const inventoryMovements = ref([]); // Added default empty array

// Use the inventory composable
const {
  loading,
  products,
  filteredProducts: composableFilteredProducts,
  lowStockProducts: composableLowStockProducts,
  outOfStockProducts: composableOutOfStockProducts,
  inventoryValue,
  stats: storeStats,
  fetchProducts,
  fetchInventoryMovements,
  setFilter,
  loadMoreProducts,
  loadMoreMovements
} = useInventory();

// Initialize stats with safe default values
const stats = ref({
  totalProducts: 0,
  inStockProducts: 0,
  lowStockProducts: 0,
  outOfStockProducts: 0
});

// Updated computed properties
const filteredProducts = computed({
  get: () => {
    if (activeTab.value === 'low') {
      return lowStockProducts.value;
    } else if (activeTab.value === 'out') {
      return outOfStockProducts.value;
    } else {
      return composableFilteredProducts.value || [];
    }
  },
  set: (value) => {
    // This is used by our fallback filtering in setActiveTab
    if (activeTab.value !== 'all') {
      // We're only allowing setting if we're not on the 'all' tab
      composableFilteredProducts.value = value;
    }
  }
});

const lowStockProducts = computed(() => {
  if (!products.value || !Array.isArray(products.value)) return [];
  return products.value.filter(product => 
    product && product.track_inventory && 
    product.quantity > 0 && 
    product.quantity <= (product.reorder_level || 5)
  );
});

const outOfStockProducts = computed(() => {
  if (!products.value || !Array.isArray(products.value)) return [];
  return products.value.filter(product => 
    product && product.track_inventory && 
    product.quantity === 0
  );
});

// Get inventory movements and compute recent ones
const recentMovements = computed(() => {
  if (!inventoryMovements.value || !Array.isArray(inventoryMovements.value)) {
    return [];
  }
  
  const now = new Date();
  const oneDayAgo = new Date(now.getTime() - 24 * 60 * 60 * 1000);
  
  return inventoryMovements.value
    .filter(movement => {
      if (!movement || !movement.created_at) return false;
      const movementDate = new Date(movement.created_at);
      return movementDate >= oneDayAgo;
    })
    .slice(0, 5);
});

// Compute stock distribution percentages
const totalTrackedProducts = computed(() => {
  if (!stats.value) return 0;
  return stats.value.totalProducts || 0;
});

const inStockPercent = computed(() => {
  if (!totalTrackedProducts.value || totalTrackedProducts.value === 0) return 0;
  // Calculate % of products that are in stock but not low stock
  return Math.round(((stats.value.inStockProducts - stats.value.lowStockProducts) / totalTrackedProducts.value) * 100);
});

const lowStockPercent = computed(() => {
  if (!totalTrackedProducts.value || totalTrackedProducts.value === 0) return 0;
  return Math.round((stats.value.lowStockProducts / totalTrackedProducts.value) * 100);
});

const outOfStockPercent = computed(() => {
  if (!totalTrackedProducts.value || totalTrackedProducts.value === 0) return 0;
  return Math.round((stats.value.outOfStockProducts / totalTrackedProducts.value) * 100);
});

// Fetch data
const fetchData = async () => {
  try {
    await fetchProducts();
    
    // Update stats
    if (products.value && Array.isArray(products.value)) {
      const inStockCount = products.value.filter(p => p && p.quantity > 0).length;
      const lowStockCount = lowStockProducts.value.length;
      const outOfStockCount = outOfStockProducts.value.length;
      
      stats.value = {
        totalProducts: products.value.length,
        inStockProducts: inStockCount,
        lowStockProducts: lowStockCount,
        outOfStockProducts: outOfStockCount
      };
    }
    
    // Get inventory movements
    try {
      const response = await fetchInventoryMovements();
      inventoryMovements.value = Array.isArray(response?.data) 
        ? response.data 
        : [];
    } catch (error) {
      console.error('Error fetching inventory movements:', error);
      inventoryMovements.value = []; 
    }
    
    await fetchCategories();
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

// Fetch categories
const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/categories');
    categories.value = response.data?.data || [];
  } catch (error) {
    console.error('Error fetching categories:', error);
    categories.value = [];
  }
};

// Fixed setActiveTab function for InventoryDashboard.vue
const setActiveTab = async (tab) => {
  activeTab.value = tab;
  loading.value = true;
  
  try {
    let filterParams = {};
    
    switch (tab) {
      case 'low':
        filterParams = {
          stock_status: 'low_stock',
          track_inventory: true
        };
        break;
      case 'out':
        filterParams = {
          stock_status: 'out_of_stock',
          track_inventory: true
        };
        break;
      case 'all':
        filterParams = {}; // No filters
        break;
      case 'history':
        // Handle history tab separately
        await fetchInventoryMovements();
        return;
    }
    
    // Fetch products with the specific filter
    await fetchProducts(filterParams);
  } catch (error) {
    console.error(`Error loading ${tab} products:`, error);
  } finally {
    loading.value = false;
  }
};

// Refresh data
// Refresh data
const refreshData = async (params = {}) => {
  loading.value = true;
  try {
    await fetchProducts();
    
    // Update stats
    if (products.value && Array.isArray(products.value)) {
      const inStockCount = products.value.filter(p => p && p.quantity > 0).length;
      const lowStockCount = lowStockProducts.value.length;
      const outOfStockCount = outOfStockProducts.value.length;
      
      stats.value = {
        totalProducts: products.value.length,
        inStockProducts: inStockCount,
        lowStockProducts: lowStockCount,
        outOfStockProducts: outOfStockCount
      };
    }
    
    // Get inventory movements with filters
    try {
      // Use filters from params if provided
      const response = await fetchInventoryMovements(params?.filters);
      inventoryMovements.value = Array.isArray(response?.data) 
        ? response.data 
        : [];
    } catch (error) {
      console.error('Error fetching inventory movements:', error);
      inventoryMovements.value = []; 
    }
    
    await fetchCategories();
  } catch (error) {
    console.error('Error refreshing data:', error);
  } finally {
    loading.value = false;
  }
};

// Load more history
const loadMoreHistory = () => {
  loadMoreMovements();
};

// Open adjustment modal
const openAdjustmentModal = (product) => {
  selectedProduct.value = product;
  showAdjustmentModal.value = true;
};

// Close adjustment modal
const closeAdjustmentModal = () => {
  selectedProduct.value = null;
  showAdjustmentModal.value = false;
};

// Handle adjustment saved
const onAdjustmentSaved = () => {
  refreshData();
  closeAdjustmentModal();
};

// Handle bulk adjustment success
const onBulkAdjustmentSuccess = () => {
  showBulkAdjustmentModal.value = false;
  refreshData();
};

// New functions for additional event handlers
const viewProductHistory = (product) => {
  // Handle viewing product history
  if (product && product.id) {
    activeTab.value = 'history';
    setFilter({ key: 'productId', value: product.id });
  }
};

const editProduct = (product) => {
  // Redirect to product edit page
  if (product && product.id) {
    // Implement navigation to edit product page
    console.log('Edit product:', product.id);
  }
};

const generateBarcode = (product) => {
  // Handle barcode generation
  if (product && product.id) {
    // Implement barcode generation logic
    console.log('Generate barcode for product:', product.id);
  }
};

const printLabel = (product) => {
  // Handle label printing
  if (product && product.id) {
    // Implement label printing logic
    console.log('Print label for product:', product.id);
  }
};

// Check for product ID in query params
watch(() => route.query.productId, (newProductId) => {
  if (newProductId && products.value && Array.isArray(products.value)) {
    const product = products.value.find(p => String(p.id) === String(newProductId));
    if (product) {
      openAdjustmentModal(product);
    }
  }
}, { immediate: true });

// Initialize
onMounted(async () => {
  await fetchData();
  
  // Check for product ID in query params
  const productId = route.query.productId;
  if (productId && products.value && Array.isArray(products.value)) {
    const product = products.value.find(p => String(p.id) === String(productId));
    if (product) {
      openAdjustmentModal(product);
    }
  }
});

</script>