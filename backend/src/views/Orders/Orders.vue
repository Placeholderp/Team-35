<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Page Header with Stats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Orders</h1>
        <p class="text-gray-600 mt-1">Manage and track all customer orders</p>
      </div>
      
      <!-- Quick Stats Cards -->
      <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-blue-500">
          <div class="text-sm text-gray-500">Total Orders</div>
          <div class="text-2xl font-semibold">{{ ordersTotal }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-emerald-500">
          <div class="text-sm text-gray-500">Completed</div>
          <div class="text-2xl font-semibold">{{ completedOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-orange-400">
          <div class="text-sm text-gray-500">Pending</div>
          <div class="text-2xl font-semibold">{{ pendingOrders }}</div>
        </div>
      </div>
    </div>
    
    <!-- Orders Table Component -->
    <OrdersTable @click-view="viewOrder"/>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import OrdersTable from "./OrdersTable.vue";

// Use Composition API for store and router
const store = useStore();
const router = useRouter();

// Compute total orders with fallback
const ordersTotal = computed(() => {
  return store.state.orders?.total || 0;
});

// Calculate order statistics
const completedOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    ['paid', 'completed'].includes(order.status)
  ).length;
});

const pendingOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    ['unpaid', 'shipped'].includes(order.status)
  ).length;
});

// Method to view specific order
function viewOrder(order) {
  // Navigate to order view
  router.push({ name: 'app.orders.view', params: { id: order.id } });
}

// Load orders on component mount
onMounted(() => {
  // Dispatch action to load orders if not already loaded
  if (!store.state.orders?.data?.length) {
    store.dispatch('getOrders');
  }
});
</script>