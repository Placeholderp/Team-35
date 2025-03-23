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
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-orange-400">
          <div class="text-sm text-gray-500">Pending</div>
          <div class="text-2xl font-semibold">{{ pendingOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-yellow-500">
          <div class="text-sm text-gray-500">Processing</div>
          <div class="text-2xl font-semibold">{{ processingOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-green-500">
          <div class="text-sm text-gray-500">Paid</div>
          <div class="text-2xl font-semibold">{{ paidOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-purple-500">
          <div class="text-sm text-gray-500">Shipped</div>
          <div class="text-2xl font-semibold">{{ shippedOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-indigo-500">
          <div class="text-sm text-gray-500">Delivered</div>
          <div class="text-2xl font-semibold">{{ deliveredOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-emerald-500">
          <div class="text-sm text-gray-500">Completed</div>
          <div class="text-2xl font-semibold">{{ completedOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-red-500">
          <div class="text-sm text-gray-500">Cancelled</div>
          <div class="text-2xl font-semibold">{{ cancelledOrders }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-cyan-500">
          <div class="text-sm text-gray-500">Refunded</div>
          <div class="text-2xl font-semibold">{{ refundedOrders }}</div>
        </div>
      </div>
    </div>
    
    <!-- Orders Table Component -->
    <OrdersTable @click-view="(order) => viewOrder(order)"/>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import OrdersTable from "./OrdersTable.vue";
import { normalizeStatus } from '../../utils/orderstatusUtils';

// Use Composition API for store and router
const store = useStore();
const router = useRouter();

// Compute total orders with fallback
const ordersTotal = computed(() => {
  return store.state.orders?.total || 0;
});

// Calculate order statistics based on exact status names from the dropdown
const pendingOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'pending'
  ).length;
});

const processingOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'processing'
  ).length;
});

const paidOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'paid'
  ).length;
});

const shippedOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'shipped'
  ).length;
});

const deliveredOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'delivered'
  ).length;
});

const completedOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'completed'
  ).length;
});

const cancelledOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'cancelled'
  ).length;
});

const refundedOrders = computed(() => {
  const orderData = store.state.orders?.data || [];
  return orderData.filter(order => 
    normalizeStatus(order.status) === 'refunded'
  ).length;
});

// Method to view specific order
function viewOrder(order) {
  console.log("View Order called with:", order); // Add this for debugging
  if (!order || !order.id) {
    console.error("Invalid order or missing order ID");
    return;
  }
  // Navigate to order view with the ID
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