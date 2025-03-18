<!-- PrintInventoryReport.vue -->
<template>
    <div class="print:block hidden">
      <div class="max-w-4xl mx-auto p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 border-b pb-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventory Report</h1>
            <p class="text-gray-600">Generated: {{ formatDate(new Date()) }}</p>
          </div>
          <div>
            <img src="/logo.png" alt="Company Logo" class="h-12" />
          </div>
        </div>
        
        <!-- Product Information -->
        <div class="mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-2">{{ product.title }}</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p><span class="font-semibold">ID:</span> {{ product.id }}</p>
              <p><span class="font-semibold">SKU:</span> {{ product.sku || 'N/A' }}</p>
              <p><span class="font-semibold">Price:</span> {{ formatCurrency(product.price) }}</p>
            </div>
            <div>
              <p><span class="font-semibold">Current Stock:</span> {{ product.quantity }}</p>
              <p><span class="font-semibold">Reorder Level:</span> {{ product.reorder_level }}</p>
              <p>
                <span class="font-semibold">Status:</span>
                <span :class="getStatusColorClass()">{{ getStatusText() }}</span>
              </p>
            </div>
          </div>
        </div>
        
        <!-- Inventory Movements -->
        <div class="mb-8">
          <h3 class="text-lg font-bold text-gray-900 mb-2">Inventory Movement History</h3>
          <table class="w-full">
            <thead>
              <tr class="border-b-2 border-gray-200">
                <th class="py-2 text-left">Date</th>
                <th class="py-2 text-left">Type</th>
                <th class="py-2 text-right">Change</th>
                <th class="py-2 text-right">Before</th>
                <th class="py-2 text-right">After</th>
                <th class="py-2 text-left">Reference</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="movement in inventoryMovements" :key="movement.id" class="border-b border-gray-200">
                <td class="py-2">{{ formatDate(movement.created_at) }}</td>
                <td class="py-2">{{ movement.type }}</td>
                <td class="py-2 text-right" :class="movement.quantity > 0 ? 'text-green-600' : 'text-red-600'">
                  {{ movement.quantity > 0 ? '+' : '' }}{{ movement.quantity }}
                </td>
                <td class="py-2 text-right">{{ movement.previous_quantity }}</td>
                <td class="py-2 text-right">{{ movement.new_quantity }}</td>
                <td class="py-2">{{ movement.reference || '—' }}</td>
              </tr>
              <tr v-if="inventoryMovements.length === 0">
                <td colspan="6" class="py-4 text-center text-gray-500">No inventory movements recorded</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Summary -->
        <div class="mb-8">
          <h3 class="text-lg font-bold text-gray-900 mb-2">Inventory Summary</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-3 gap-4">
              <div class="text-center">
                <p class="text-sm text-gray-500">Total Purchases</p>
                <p class="text-xl font-bold">{{ getTotalPurchases() }}</p>
              </div>
              <div class="text-center">
                <p class="text-sm text-gray-500">Total Sales</p>
                <p class="text-xl font-bold">{{ getTotalSales() }}</p>
              </div>
              <div class="text-center">
                <p class="text-sm text-gray-500">Current Inventory Value</p>
                <p class="text-xl font-bold">{{ formatCurrency(getInventoryValue()) }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 pt-4 border-t">
          <p>This report is for internal use only. Confidential information.</p>
          <p>Page 1 of 1</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  import { formatCurrency } from '../../utils/ProductUtils';
  
  const props = defineProps({
    product: {
      type: Object,
      required: true
    },
    inventoryMovements: {
      type: Array,
      default: () => []
    }
  });
  
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
  
  // Get status text based on inventory level
  function getStatusText() {
    if (!props.product.track_inventory) return 'Not Tracked';
    if (props.product.quantity <= 0) return 'Out of Stock';
    if (props.product.quantity <= props.product.reorder_level) return 'Low Stock';
    return 'In Stock';
  }
  
  // Get status color class based on inventory level
  function getStatusColorClass() {
    if (!props.product.track_inventory) return 'text-gray-600';
    if (props.product.quantity <= 0) return 'text-red-600';
    if (props.product.quantity <= props.product.reorder_level) return 'text-yellow-600';
    return 'text-green-600';
  }
  
  // Calculate total purchases
  function getTotalPurchases() {
    return props.inventoryMovements
      .filter(m => m.type.toLowerCase() === 'purchase' || m.quantity > 0)
      .reduce((total, movement) => total + Math.max(0, movement.quantity), 0);
  }
  
  // Calculate total sales
  function getTotalSales() {
    return props.inventoryMovements
      .filter(m => m.type.toLowerCase() === 'sale' || m.quantity < 0)
      .reduce((total, movement) => total + Math.abs(Math.min(0, movement.quantity)), 0);
  }
  
  // Calculate inventory value
  function getInventoryValue() {
    return props.product.price * props.product.quantity;
  }
  </script>
  
  <style>
  @media print {
    body * {
      visibility: hidden;
    }
    .print-section, .print-section * {
      visibility: visible;
    }
    .print-section {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
  </style>