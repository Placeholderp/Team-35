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
          <h1 class="text-2xl font-bold text-gray-800">Order #{{ order.id || order.user_id }}</h1>
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
              @click="exportOrder"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Export
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
                <tr v-if="!order.items || order.items.length === 0">
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No items found for this order</td>
                </tr>
                <tr v-for="(item, index) of order.items" :key="item.id || item.user_id || index">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img 
                          class="h-10 w-10 rounded-md object-cover" 
                          :src="getProductImageUrl(item)" 
                          :alt="item.product ? item.product.title : 'Product'" 
                          @error="handleImageError"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ item.product ? item.product.title : 'Product not available' }}
                        </div>
                        <div v-if="item.product && item.product.sku" class="text-sm text-gray-500">
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
                    {{ formatCurrency(shippingCost) }}
                  </td>
                </tr>
                <tr class="bg-gray-200">
                  <th scope="row" colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                    Total
                  </th>
                  <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                    {{ formatCurrency(orderTotal) }}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="space-y-6">
        <!-- Enhanced Customer Details -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Customer</h2>
          </div>
          <div class="p-6" v-if="order.customer">
            <div class="flex items-center mb-4">
              <!-- Customer Avatar/Picture with dynamic color based on ID -->
              <div 
                :class="[getAvatarColor, 'h-12 w-12 rounded-full flex items-center justify-center text-white font-bold text-lg']"
              >
                {{ getCustomerInitials }}
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">
                  {{ getCustomerFullName() }}
                </h3>
                <p class="text-sm text-gray-500">
                  Customer ID: #{{ order.customer.id }}
                </p>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-4">
              <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900 break-all">
                    {{ getCustomerEmail() }}
                  </dd>
                </div>
                
                <!-- Additional customer information (if available) -->
                <div v-if="order.customer.phone" class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="mt-1 text-sm text-gray-900">
                    {{ order.customer.phone }}
                  </dd>
                </div>
                
                <!-- Order count or customer status (if available) -->
                <div v-if="order.customer.order_count" class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                  <dd class="mt-1 text-sm text-gray-900">
                    {{ order.customer.order_count }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>
          <div class="p-6" v-else>
            <p class="text-sm text-gray-500">Customer information not available</p>
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
              <address class="not-italic text-sm text-gray-700" v-if="hasShippingAddress">
                {{ order.customer.shippingAddress.address1 }}<br>
                <span v-if="order.customer.shippingAddress.address2">
                  {{ order.customer.shippingAddress.address2 }}<br>
                </span>
                {{ order.customer.shippingAddress.city }}, 
                {{ order.customer.shippingAddress.state }} 
                {{ order.customer.shippingAddress.zipcode }}<br>
                {{ order.customer.shippingAddress.country }}
              </address>
              <p v-else class="text-sm text-gray-500">No shipping address available</p>
            </div>
            <div class="pt-4 border-t border-gray-200">
              <h3 class="text-sm font-medium text-gray-900 mb-2">Billing Address</h3>
              <address class="not-italic text-sm text-gray-700" v-if="hasBillingAddress">
                {{ order.customer.billingAddress.address1 }}<br>
                <span v-if="order.customer.billingAddress.address2">
                  {{ order.customer.billingAddress.address2 }}<br>
                </span>
                {{ order.customer.billingAddress.city }}, 
                {{ order.customer.billingAddress.state }} 
                {{ order.customer.billingAddress.zipcode }}<br>
                {{ order.customer.billingAddress.country }}
              </address>
              <p v-else class="text-sm text-gray-500">No billing address available</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Notes Section -->
    <div class="mt-6">
      <OrderNotes 
        v-if="order && (order.id || order.user_id)" 
        :order-id="order.id || order.user_id" 
      />
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
import { exportOrdersToCsv } from '../../utils/exportUtils';

// Components
import OrderStatus from './OrderStatus.vue';
import OrderNotes from './OrderNotes.vue';
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
// Fixed shipping cost of $40 that will be added to every order total
const shippingCost = ref(40);
const baseUrl = ref(window.location.origin); // Gets the base URL (e.g., http://localhost:5173)

// Composable methods
const { formatOrderDate } = useDateFormatter();
const { formatCurrency } = useCurrencyFormatter();
const { 
  loading,
  updateOrderStatus, 
  printOrder, 
  emailInvoice, 
} = useOrderActions();

// Computed properties
const isStatusChanged = computed(() => 
  originalStatus.value && originalStatus.value !== currentStatus.value
);

const hasShippingAddress = computed(() => 
  order.value && 
  order.value.customer && 
  order.value.customer.shippingAddress && 
  order.value.customer.shippingAddress.address1
);

const hasBillingAddress = computed(() => 
  order.value && 
  order.value.customer && 
  order.value.customer.billingAddress && 
  order.value.customer.billingAddress.address1
);

// Computed property for order total including shipping
// This adds the $40 shipping cost to every order total
const orderTotal = computed(() => 
  order.value ? order.value.total_price + shippingCost.value : 0
);

// Enhanced customer info display
const getCustomerInitials = computed(() => {
  if (!order.value || !order.value.customer) return '?';
  
  const firstName = order.value.customer.first_name || '';
  const lastName = order.value.customer.last_name || '';
  
  if (firstName && lastName) {
    return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
  } else if (firstName) {
    return firstName.charAt(0).toUpperCase();
  } else if (lastName) {
    return lastName.charAt(0).toUpperCase();
  }
  
  return '#';
});

// Get avatar background color based on customer ID
const getAvatarColor = computed(() => {
  if (!order.value || !order.value.customer || !order.value.customer.id) return 'bg-gray-400';
  
  // Generate color based on customer ID
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 
    'bg-pink-500', 'bg-purple-500', 'bg-indigo-500',
    'bg-red-500', 'bg-teal-500', 'bg-orange-500'
  ];
  
  const colorIndex = (parseInt(order.value.customer.id) % colors.length);
  return colors[colorIndex];
});

onMounted(async () => {
  try {
    // Fetch order details
    const response = await store.dispatch('getOrder', route.params.id);
    
    // Verify we have a valid response with data
    if (!response || !response.data) {
      throw new Error('Invalid response format from API');
    }
    
    // Set the order data - use the raw data without modification
    order.value = response.data;
    console.log('Order data loaded:', order.value);
    
    // Set status values if available
    currentStatus.value = response.data.status || '';
    originalStatus.value = response.data.status || '';
    
    // Fetch available statuses
    try {
      const statusResponse = await axios.get('/orders/statuses');
      orderStatuses.value = statusResponse.data || [];
    } catch (statusError) {
      console.error('Error fetching order statuses:', statusError);
    }
  } catch (error) {
    console.error('Error fetching order details:', error);
    store.commit('showToast', {
      message: 'Error loading order details. Please try again later.',
      type: 'error'
    });
  }
});

async function onStatusChange() {
  if (!isStatusChanged.value) return;

  try {
    // Call updateOrderStatus directly with the order ID and new status
    // Make sure we're passing string values
    const orderId = String(order.value.id);
    const newStatus = String(currentStatus.value);
    
    console.log(`Updating order ${orderId} status to ${newStatus}`);
    
    await updateOrderStatus(orderId, newStatus);
    
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

// Improved customer email retrieval
function getCustomerEmail() {
  if (!order.value || !order.value.customer) return 'Email not available';
  
  // First check if email is directly available
  if (order.value.customer.email) {
    return order.value.customer.email;
  }
  
  // Check for nested properties with fallbacks
  if (order.value.customer.user_email) {
    return order.value.customer.user_email;
  }
  
  // Check if user object exists with email
  if (order.value.user && order.value.user.email) {
    return order.value.user.email;
  }
  
  // Check if email might be in order object directly
  if (order.value.email) {
    return order.value.email;
  }
  
  // Check if email might be in addresses
  if (order.value.customer.billingAddress && order.value.customer.billingAddress.email) {
    return order.value.customer.billingAddress.email;
  }
  
  if (order.value.customer.shippingAddress && order.value.customer.shippingAddress.email) {
    return order.value.customer.shippingAddress.email;
  }
  
  return 'Email not available';
}

// Customer name formatter with fallbacks
function getCustomerFullName() {
  if (!order.value || !order.value.customer) return 'Unknown Customer';
  
  const firstName = order.value.customer.first_name || '';
  const lastName = order.value.customer.last_name || '';
  
  if (firstName || lastName) {
    return `${firstName} ${lastName}`.trim();
  }
  
  if (order.value.user && order.value.user.name) {
    return order.value.user.name;
  }
  
  return `Customer #${order.value.customer.id || 'Unknown'}`;
}

// Modified getProductImageUrl function for OrderView.vue
function getProductImageUrl(item) {
  // Base fallback image - could be a placeholder image
  const fallbackImage = '';  // Empty to trigger error handler
  
  // Check if we have valid item and product data
  if (!item) return fallbackImage;
  if (!item.product) return fallbackImage;
  
  // Check for product image in various possible locations
  let imagePath = '';
  
  // Direct image property
  if (item.product.image) {
    imagePath = item.product.image;
  } 
  // Image might be in image_url
  else if (item.product.image_url) {
    imagePath = item.product.image_url;
  }
  // Check if image is in images array
  else if (item.product.images && Array.isArray(item.product.images) && item.product.images.length > 0) {
    // Get the first image from the array
    imagePath = typeof item.product.images[0] === 'string' 
      ? item.product.images[0] 
      : (item.product.images[0]?.url || item.product.images[0]?.path || '');
  }
  // Check thumbnail as a last resort
  else if (item.product.thumbnail) {
    imagePath = item.product.thumbnail;
  }
  
  // If no image was found, return fallback
  if (!imagePath) return fallbackImage;
  
  // If the image path is already a full URL, return it as is
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  // For Laravel projects with Vite/Vue development server, you may need to use the backend URL
  // Modify this to match your Laravel backend URL
  const backendUrl = 'http://localhost/team-35/public'; // Change this to match your XAMPP setup
  
  // Clean up the path to ensure proper formatting
  let cleanPath = imagePath;
  
  // Remove any leading slash for concatenation
  if (cleanPath.startsWith('/')) {
    cleanPath = cleanPath.substring(1);
  }
  
  // Ensure the path includes 'storage/products' prefix
  if (!cleanPath.includes('public/storage/products') && !cleanPath.includes('public/storage/products')) {
    cleanPath = `public/storage/products${cleanPath}`;
  } else if (!cleanPath.includes('public/storage/products')) {
    cleanPath = `public/storage/products${cleanPath}`;
  }
  
  // Return the full URL to the image on the backend
  return `${backendUrl}/${cleanPath}`;
}

// Replace the getProductImageUrl function in OrderView.vue (around line 483)


// Export order to CSV
function exportOrder() {
  const orderId = order.value.id || order.value.user_id;
  exportOrdersToCsv([order.value], `order_${orderId}_export.csv`);
  
  store.commit('showToast', {
    message: 'Order exported successfully',
    type: 'success'
  });
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