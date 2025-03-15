<template>
  <div v-if="order" class="container mx-auto px-4 py-6">
    <!-- Back button and order header -->
    <div class="flex items-center mb-6">
      <router-link :to="{ name: 'app.orders' }" class="flex items-center text-indigo-600 hover:text-indigo-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to Orders
      </router-link>
    </div>

    <!-- Order Summary Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
      <div class="flex justify-between items-center p-6 bg-gray-50 border-b border-gray-200">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Order #{{ order.id }}</h1>
          <p class="text-gray-600 mt-1">{{ formatOrderDate(order.created_at) }}</p>
        </div>
        <OrderStatus :order="order" />
      </div>

      <!-- Order Actions -->
      <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex flex-wrap gap-4">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
            <div class="flex items-center">
              <select 
                v-model="currentStatus" 
                @change="onStatusChange"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option v-for="status of orderStatuses" :key="status" :value="status">
                  {{ capitalizeFirstLetter(status) }}
                </option>
              </select>
              <button 
                type="button"
                v-if="isStatusChanged"
                @click="onStatusChange"
                class="ml-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Update
              </button>
            </div>
          </div>
          
          <div class="flex items-end gap-2">
            <button 
              type="button" 
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              @click="printOrder"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
              </svg>
              Print
            </button>
            <button 
              type="button" 
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              @click="emailInvoice"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Email Invoice
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Order Summary -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden md:col-span-2">
        <div class="p-6 bg-gray-50 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Order Items</h2>
        </div>
        <div class="p-6">
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Product
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Quantity
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) of order.items" :key="item.id || index">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img 
                          class="h-10 w-10 rounded-md object-cover" 
                          :src="item.product.image" 
                          :alt="item.product.title" 
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ item.product.title }}
                        </div>
                        <div class="text-sm text-gray-500" v-if="item.product.sku">
                          SKU: {{ item.product.sku }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                    {{ item.quantity }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                    {{ formatCurrency(item.unit_price) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ formatCurrency(item.unit_price * item.quantity) }}
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-gray-50">
                <tr>
                  <th scope="row" colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                    Subtotal
                  </th>
                  <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ formatCurrency(order.total_price) }}
                  </td>
                </tr>
                <tr>
                  <th scope="row" colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                    Tax
                  </th>
                  <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ formatCurrency(0) }}
                  </td>
                </tr>
                <tr>
                  <th scope="row" colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                    Shipping
                  </th>
                  <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ formatCurrency(0) }}
                  </td>
                </tr>
                <tr class="bg-gray-200">
                  <th scope="row" colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                    Total
                  </th>
                  <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                    {{ formatCurrency(order.total_price) }}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="space-y-6">
        <!-- Customer Details -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Customer</h2>
          </div>
          <div class="p-6">
            <div class="flex items-center mb-4">
              <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900">
                  {{ order.customer.first_name }} {{ order.customer.last_name }}
                </h3>
                <p class="text-sm text-gray-500">Customer ID: #{{ order.customer.id }}</p>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-4">
              <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ order.customer.email }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="mt-1 text-sm text-gray-900">
                    {{ formatPhoneNumber(order.customer.phone) }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <!-- Shipping and Billing -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Addresses</h2>
          </div>
          <div class="p-6">
            <div class="mb-6">
              <h3 class="text-sm font-medium text-gray-900 mb-2">Shipping Address</h3>
              <address class="not-italic text-sm text-gray-700">
                {{ order.customer.shippingAddress.address1 }}<br>
                <span v-if="order.customer.shippingAddress.address2">
                  {{ order.customer.shippingAddress.address2 }}<br>
                </span>
                {{ order.customer.shippingAddress.city }}, 
                {{ order.customer.shippingAddress.state }} 
                {{ order.customer.shippingAddress.zipcode }}<br>
                {{ order.customer.shippingAddress.country }}
              </address>
            </div>
            <div class="pt-4 border-t border-gray-200">
              <h3 class="text-sm font-medium text-gray-900 mb-2">Billing Address</h3>
              <address class="not-italic text-sm text-gray-700">
                {{ order.customer.billingAddress.address1 }}<br>
                <span v-if="order.customer.billingAddress.address2">
                  {{ order.customer.billingAddress.address2 }}<br>
                </span>
                {{ order.customer.billingAddress.city }}, 
                {{ order.customer.billingAddress.state }} 
                {{ order.customer.billingAddress.zipcode }}<br>
                {{ order.customer.billingAddress.country }}
              </address>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="flex justify-center items-center h-64">
    <Spinner />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import axios from '../../axios';

// Components
import OrderStatus from './OrderStatus.vue';
import Spinner from '../../components/core/Spinner.vue';

// Composables
import { useOrderActions } from '../../composables/useOrderActions';
import { useDateFormatter } from '../../composables/useDateFormatter';
import { useCurrencyFormatter } from '../../composables/useCurrencyFormatter';

// Route and store
const route = useRoute();
const store = useStore();

// State
const order = ref(null);
const orderStatuses = ref([]);
const currentStatus = ref('');
const originalStatus = ref('');

// Composable methods
const { formatOrderDate } = useDateFormatter();
const { formatCurrency } = useCurrencyFormatter();
const { 
  updateOrderStatus, 
  printOrder, 
  emailInvoice, 
  formatPhoneNumber 
} = useOrderActions();

// Computed properties
const isStatusChanged = computed(() => 
  originalStatus.value && originalStatus.value !== currentStatus.value
);

// Lifecycle hooks
onMounted(async () => {
  try {
    // Fetch order details
    const { data } = await store.dispatch('getOrder', route.params.id);
    order.value = data;
    currentStatus.value = data.status;
    originalStatus.value = data.status;

    // Fetch available statuses
    const statusResponse = await axios.get('/orders/statuses');
    orderStatuses.value = statusResponse.data;
  } catch (error) {
    console.error('Error fetching order details:', error);
    store.commit('showToast', {
      message: 'Error loading order details.',
      type: 'error'
    });
  }
});

// Status change handler
async function onStatusChange() {
  if (!isStatusChanged.value) return;

  try {
    await updateOrderStatus(order.value.id, currentStatus.value);
    originalStatus.value = currentStatus.value;
    store.commit('showToast', {
      message: `Order status was successfully changed to "${currentStatus.value}"`,
      type: 'success'
    });
  } catch (error) {
    console.error('Error updating order status:', error);
    // Revert status on error
    currentStatus.value = originalStatus.value;
    store.commit('showToast', {
      message: 'Error updating order status.',
      type: 'error'
    });
  }
}

// Utility functions
function capitalizeFirstLetter(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>

<style scoped>
@media print {
  nav, 
  .order-actions,
  button {
    display: none !important;
  }
  
  body * {
    visibility: hidden;
  }
  
  .order-print-section, 
  .order-print-section * {
    visibility: visible;
  }
  
  .order-print-section {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>