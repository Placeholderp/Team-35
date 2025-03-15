<!-- views/Products/ProductDetails.vue -->
<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Product Details</h1>
          <p class="mt-2 text-sm text-gray-600">
            Add and manage products in your catalog
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
          <button
            @click="router.push({ name: 'app.products' })"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Products
          </button>
          <button
            @click="saveProduct"
            :disabled="isSaving"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ isEditing ? 'Update Product' : 'Save Product' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Product Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          {{ isEditing ? 'Edit Product' : 'Add New Product' }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          {{ isEditing ? 'Update product information' : 'Enter product details to add to your catalog' }}
        </p>
      </div>
      
      <div class="px-4 py-5 sm:p-6">
        <form @submit.prevent="saveProduct" class="space-y-6">
          <!-- Main product info section -->
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
              <label for="image-upload" class="block text-sm font-medium text-gray-700">
                Product Image
              </label>
              <div class="mt-1 flex items-center">
                <div v-if="imagePreview" class="flex-shrink-0 h-24 w-24 rounded-md overflow-hidden bg-gray-100">
                  <img :src="imagePreview" alt="Product preview" class="h-24 w-24 object-cover">
                </div>
                <div v-else class="flex-shrink-0 h-24 w-24 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                  <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="ml-5">
                  <div class="relative">
                    <input
                      id="image-upload"
                      ref="imageInput"
                      type="file"
                      class="sr-only"
                      accept="image/*"
                      @change="handleImageUpload"
                    >
                    <label
                      for="image-upload"
                      class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                      </svg>
                      Upload
                    </label>
                  </div>
                  <p v-if="imagePreview" class="mt-2 text-xs text-gray-500">
                    <button 
                      type="button" 
                      class="text-indigo-600 hover:text-indigo-900"
                      @click="removeImage"
                    >
                      Remove image
                    </button>
                  </p>
                </div>
              </div>
            </div>

            <div class="sm:col-span-4">
              <label for="title" class="block text-sm font-medium text-gray-700">
                Product Name
              </label>
              <div class="mt-1">
                <input
                  type="text"
                  id="title"
                  v-model="product.title"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-300': validationErrors.title }"
                  placeholder="Enter product name"
                >
                <p v-if="validationErrors.title" class="mt-1 text-sm text-red-600">
                  {{ validationErrors.title }}
                </p>
              </div>
            </div>

            <div class="sm:col-span-2">
              <label for="price" class="block text-sm font-medium text-gray-700">
                Price
              </label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  id="price"
                  v-model="product.price"
                  class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-300': validationErrors.price }"
                  placeholder="0.00"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">USD</span>
                </div>
              </div>
              <p v-if="validationErrors.price" class="mt-1 text-sm text-red-600">
                {{ validationErrors.price }}
              </p>
            </div>

            <div class="sm:col-span-6">
              <label for="description" class="block text-sm font-medium text-gray-700">
                Description
              </label>
              <div class="mt-1">
                <textarea
                  id="description"
                  v-model="product.description"
                  rows="5"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-300': validationErrors.description }"
                  placeholder="Enter product description"
                ></textarea>
                <p v-if="validationErrors.description" class="mt-1 text-sm text-red-600">
                  {{ validationErrors.description }}
                </p>
              </div>
            </div>

            <div class="sm:col-span-3">
              <label for="category" class="block text-sm font-medium text-gray-700">
                Category
              </label>
              <div class="mt-1">
                <select
                  id="category"
                  v-model="product.category_id"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                >
                  <option value="">Select a category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="sm:col-span-3">
              <label for="inventory" class="block text-sm font-medium text-gray-700">
                Inventory
              </label>
              <div class="mt-1">
                <input
                  type="number"
                  min="0"
                  id="inventory"
                  v-model="product.quantity"
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  :class="{ 'border-red-300': validationErrors.quantity }"
                  placeholder="Available stock"
                >
                <p v-if="validationErrors.quantity" class="mt-1 text-sm text-red-600">
                  {{ validationErrors.quantity }}
                </p>
              </div>
            </div>
          </div>

          <!-- Product status and additional settings -->
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-6">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="published"
                    type="checkbox"
                    v-model="product.published"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                  >
                </div>
                <div class="ml-3 text-sm">
                  <label for="published" class="font-medium text-gray-700">Publish product</label>
                  <p class="text-gray-500">Make this product visible in your store</p>
                </div>
              </div>
            </div>

            <div class="sm:col-span-6">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="featured"
                    type="checkbox"
                    v-model="product.featured"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                  >
                </div>
                <div class="ml-3 text-sm">
                  <label for="featured" class="font-medium text-gray-700">Featured product</label>
                  <p class="text-gray-500">Highlight this product on your homepage</p>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Product metadata section -->
    <div v-if="isEditing" class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Product Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Additional details and metadata
        </p>
      </div>
      <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Product ID</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ product.id || 'Not yet assigned' }}</dd>
          </div>
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Created at</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(product.created_at) }}</dd>
          </div>
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Last updated</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(product.updated_at) }}</dd>
          </div>
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Status</dt>
            <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
              <span 
                :class="[
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full', 
                  product.published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                ]"
              >
                {{ product.published ? 'Published' : 'Draft' }}
              </span>
            </dd>
          </div>
        </dl>
      </div>
    </div>
    
    <!-- Order history for this product (only shown if editing existing product) -->
    <div v-if="isEditing && orderHistory.length > 0" class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Order History
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Recent orders containing this product
        </p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order ID
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Customer
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Quantity
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">View</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="order in orderHistory" :key="order.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ order.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(order.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ order.customer_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ order.quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                ${{ formatNumber(order.total) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="viewOrder(order.id)"
                  class="text-indigo-600 hover:text-indigo-900"
                >
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Toast notification -->
    <Toast position="bottom" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRouter, useRoute } from 'vue-router';
import store from "../../store";
import Toast from "../../components/core/Toast.vue";
import { normalizePublished, cleanId, formatDate } from "../../utils/ProductUtils";
import axiosClient from "../../axios.js";

const router = useRouter();
const route = useRoute();
const isSaving = ref(false);
const imageInput = ref(null);
const imagePreview = ref(null);
const validationErrors = ref({});
const orderHistory = ref([]);
const categories = ref([
  { id: 1, name: 'Electronics' },
  { id: 2, name: 'Clothing' },
  { id: 3, name: 'Home & Kitchen' },
  { id: 4, name: 'Books' },
  { id: 5, name: 'Beauty & Personal Care' }
]);

// Initialize the product object
const product = ref({
  id: null,
  title: '',
  description: '',
  price: '',
  image: null,
  image_url: null,
  category_id: '',
  quantity: 0,
  published: false,
  featured: false,
  created_at: null,
  updated_at: null
});

// Determine if we're editing an existing product or creating a new one
const isEditing = computed(() => {
  return !!product.value.id;
});

// Helper methods for formatting
function formatNumber(value) {
  return parseFloat(value || 0).toFixed(2);
}

// Check if a product ID was provided in the route
onMounted(async () => {
  // Check if we're editing an existing product
  const productId = route.params.id;
  if (productId) {
    await loadProduct(productId);
    await loadOrderHistory(productId);
  }
});

// Load product data
async function loadProduct(id) {
  try {
    const response = await store.dispatch('getProduct', cleanId(id));
    if (response && response.data) {
      // Update our local product object with the received data
      const receivedProduct = response.data;
      
      // Normalize the published value and update the product ref
      product.value = {
        ...receivedProduct,
        published: normalizePublished(receivedProduct.published)
      };
      
      // Set the image preview if there's an image URL
      if (receivedProduct.image_url) {
        imagePreview.value = receivedProduct.image_url;
      }
    }
  } catch (error) {
    console.error('Error loading product:', error);
    store.commit('showToast', 'Failed to load product data', 'error');
  }
}

// Load order history for the product
async function loadOrderHistory(id) {
  try {
    // This would be a real API call in a production app
    // For demo purposes, we'll just simulate some order history
    orderHistory.value = [
      {
        id: '1001',
        created_at: new Date(2025, 1, 15),
        customer_name: 'John Doe',
        quantity: 1,
        total: product.value.price
      },
      {
        id: '982',
        created_at: new Date(2025, 1, 10),
        customer_name: 'Jane Smith',
        quantity: 2,
        total: product.value.price * 2
      },
      {
        id: '864',
        created_at: new Date(2025, 0, 25),
        customer_name: 'Michael Johnson',
        quantity: 1,
        total: product.value.price
      }
    ];
  } catch (error) {
    console.error('Error loading order history:', error);
  }
}

// Handle image uploads
function handleImageUpload(event) {
  const file = event.target.files[0];
  if (!file) return;
  
  // Check file type
  if (!file.type.match('image.*')) {
    store.commit('showToast', 'Please select an image file', 'error');
    return;
  }
  
  // Check file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    store.commit('showToast', 'Image size should be less than 5MB', 'error');
    return;
  }
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
  
  // Store file for form submission
  product.value.image = file;
}

// Remove the selected image
function removeImage() {
  imagePreview.value = null;
  product.value.image = null;
  if (imageInput.value) {
    imageInput.value.value = '';
  }
}

// Validate the form
function validateForm() {
  const errors = {};
  
  // Title validation
  if (!product.value.title.trim()) {
    errors.title = 'Product name is required';
  } else if (product.value.title.length > 255) {
    errors.title = 'Product name must be less than 255 characters';
  }
  
  // Price validation
  if (!product.value.price) {
    errors.price = 'Price is required';
  } else if (isNaN(parseFloat(product.value.price)) || parseFloat(product.value.price) < 0) {
    errors.price = 'Price must be a positive number';
  }
  
  // Description validation (optional)
  if (product.value.description && product.value.description.length > 1000) {
    errors.description = 'Description must be less than 1000 characters';
  }
  
  // Quantity validation
  if (product.value.quantity === '' || isNaN(parseInt(product.value.quantity))) {
    errors.quantity = 'Inventory quantity must be a number';
  } else if (parseInt(product.value.quantity) < 0) {
    errors.quantity = 'Inventory quantity cannot be negative';
  }
  
  validationErrors.value = errors;
  return Object.keys(errors).length === 0;
}

// Save the product
async function saveProduct() {
  if (!validateForm()) {
    store.commit('showToast', 'Please fix the errors before saving', 'error');
    return;
  }
  
  isSaving.value = true;
  
  try {
    // Create FormData for file upload
    const formData = new FormData();
    
    // Add fields to FormData
    for (const [key, value] of Object.entries(product.value)) {
      // Skip image URL since we don't need to send it back
      if (key === 'image_url') continue;
      
      // Skip null/undefined values and the image (handled separately)
      if (value !== null && value !== undefined && key !== 'image') {
        formData.append(key, value);
      }
    }
    
    // Only append image if a new one was selected
    if (product.value.image) {
      formData.append('image', product.value.image);
    }
    
    // Add method for PUT requests
    if (isEditing.value) {
      formData.append('_method', 'PUT');
    }
    
    let response;
    if (isEditing.value) {
      response = await store.dispatch('updateProduct', formData);
    } else {
      response = await store.dispatch('createProduct', formData);
    }
    
    // Show success message
    store.commit('showToast', `Product ${isEditing.value ? 'updated' : 'created'} successfully`);
    
    // Navigate back to products page
    router.push({ name: 'app.products' });
  } catch (error) {
    console.error('Error saving product:', error);
    
    // Handle validation errors from server
    if (error.response && error.response.status === 422) {
      validationErrors.value = error.response.data.errors;
      store.commit('showToast', 'Please fix the validation errors', 'error');
    } else {
      store.commit('showToast', `Failed to ${isEditing.value ? 'update' : 'create'} product`, 'error');
    }
  } finally {
    isSaving.value = false;
  }
}

// Navigate to view an order
function viewOrder(orderId) {
  router.push({ name: 'app.orders.view', params: { id: orderId } });
}
</script>