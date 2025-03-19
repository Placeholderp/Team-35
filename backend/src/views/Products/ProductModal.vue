<template>
  <Teleport to="body">
    <TransitionRoot as="template" :show="modelValue">
      <Dialog as="div" class="fixed inset-0 z-50 overflow-y-auto" @close="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-200"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </TransitionChild>

          <!-- This element is to trick the browser into centering the modal contents. -->
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
              <div class="relative">
                <Spinner v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 z-10"/>
                
                <div class="bg-indigo-700 px-6 py-4">
                  <div class="flex items-center justify-between">
                    <DialogTitle class="text-xl font-semibold text-white">
                      {{ localProduct.id ? `Edit: ${localProduct.title}` : 'Add New Product' }}
                    </DialogTitle>
                    <button
                      type="button"
                      @click="closeModal"
                      class="text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700 rounded-full p-1"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
                
                <form @submit.prevent="onSubmit">
                  <div class="bg-white px-6 pt-6 pb-4 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div class="md:col-span-2">
                        <CustomInput 
                          label="Product Title" 
                          v-model="localProduct.title" 
                          :error="errors.title"
                          required
                          placeholder="Enter product title"
                          class="mb-1"
                        />
                      </div>
                      
                      <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-24 w-24 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                            <img
                              v-if="imagePreview"
                              :src="imagePreview"
                              alt="Product preview"
                              class="h-full w-full object-cover"
                            />
                            <div v-else class="h-full w-full flex items-center justify-center">
                              <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                            </div>
                          </div>
                          <div class="ml-5 space-y-2">
                            <label
                              for="file-upload"
                              class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                              <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                              </svg>
                              Upload
                            </label>
                            <input
                              id="file-upload"
                              type="file"
                              class="sr-only"
                              @change="handleFileChange"
                              accept="image/*"
                            />
                            <button
                              v-if="imagePreview"
                              type="button"
                              @click="clearImage"
                              class="inline-flex items-center text-sm text-red-600 hover:text-red-900"
                            >
                              <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                              Remove
                            </button>
                          </div>
                        </div>
                        <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
                      </div>
                      
                      <div class="md:col-span-2">
                        <CustomInput 
                          type="textarea" 
                          label="Description" 
                          v-model="localProduct.description"
                          :error="errors.description"
                          placeholder="Enter product description"
                          :rows="3"
                          class="mb-1"
                        />
                      </div>
                      
                      <div>
                        <CustomInput 
                          type="number" 
                          label="Price" 
                          v-model="localProduct.price" 
                          prepend="$"
                          :error="errors.price"
                          required
                          step="0.01"
                          min="0"
                          placeholder="0.00"
                          class="mb-1"
                        />
                      </div>
                      
                      <div>
                        <div class="flex items-start mt-5">
                          <div class="flex items-center h-5">
                            <input
                              id="published-checkbox"
                              name="published"
                              type="checkbox"
                              v-model="localProduct.published"
                              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                          </div>
                          <div class="ml-3 text-sm">
                            <label for="published-checkbox" class="font-medium text-gray-700">Published</label>
                            <p class="text-gray-500">Make this product visible to customers</p>
                          </div>
                        </div>
                        <p v-if="errors.published" class="mt-1 text-sm text-red-600">{{ errors.published }}</p>
                      </div>
                      
                      <!-- Category Selection - ADDED -->
                      <div>
                        <label for="category-id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select
                          id="category-id"
                          v-model="localProduct.category_id"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        >
                          <option :value="null">No Category</option>
                          <option 
                            v-for="category in categories" 
                            :key="category.id" 
                            :value="category.id"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                        <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
                      </div>
                    </div>
                    
                    <!-- Inventory tracking section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <div class="flex items-start mt-5">
                          <div class="flex items-center h-5">
                            <input
                              id="track-inventory-checkbox"
                              name="track_inventory"
                              type="checkbox"
                              v-model="localProduct.track_inventory"
                              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                          </div>
                          <div class="ml-3 text-sm">
                            <label for="track-inventory-checkbox" class="font-medium text-gray-700">Track Inventory</label>
                            <p class="text-gray-500">Enable inventory management for this product</p>
                          </div>
                        </div>
                        <p v-if="errors.track_inventory" class="mt-1 text-sm text-red-600">{{ errors.track_inventory }}</p>
                      </div>
                      <div v-if="localProduct.track_inventory">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Initial Stock Quantity</label>
                        <input
                          type="number"
                          v-model.number="localProduct.quantity"
                          min="0"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="0"
                        />
                        <p v-if="errors.quantity" class="mt-1 text-sm text-red-600">{{ errors.quantity }}</p>
                      </div>
                      <div v-if="localProduct.track_inventory">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                        <input
                          type="number"
                          v-model.number="localProduct.reorder_level"
                          min="0"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="5"
                        />
                        <p class="mt-1 text-xs text-gray-500">Alerts will trigger when stock falls below this level</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-200">
                    <button
                      type="button"
                      @click="closeModal"
                      class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      :disabled="loading"
                      class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition disabled:opacity-75 disabled:cursor-not-allowed"
                    >
                      {{ localProduct.id ? 'Update Product' : 'Create Product' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
  </Teleport>
</template>

<script setup>
import { ref, watch, reactive, computed, onBeforeUnmount, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue';
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import { cleanId, normalizePublished, formatCurrency, formatDate, prepareProductFormData, getImageUrl } from "../../utils/ProductUtils";
import axiosClient from '../../axios';

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  product: {
    type: Object,
    required: true,
    default: () => ({
      id: '',
      title: '',
      image: '',
      description: '',
      price: '',
      published: false,
      track_inventory: false,
      quantity: 0,
      reorder_level: 5,
      category_id: null  // Added default value for category_id
    })
  },
  // New prop for categories
  categories: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:modelValue', 'close']);
const loading = ref(false);
const imagePreview = ref(null);
const errors = reactive({
  title: '',
  image: '',
  description: '',
  price: '',
  published: '',
  track_inventory: '',
  quantity: '',
  reorder_level: '',
  category_id: ''  // Added error field for category
});

// Create a local copy of the product for editing
const localProduct = reactive({
  id: '',
  title: '',
  description: '',
  image: '',
  image_url: '', 
  price: '',
  published: false,
  track_inventory: false,
  quantity: 0,
  reorder_level: 5,
  category_id: null  // Added category_id field
});

// Clear all validation errors
function clearErrors() {
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
}

// Clean up any blob URLs on unmount
onBeforeUnmount(() => {
  if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
    URL.revokeObjectURL(imagePreview.value);
  }
});

// Watch for changes to the product prop
watch(() => props.product, (newVal) => {
  if (newVal) {
    try {
      // Clean the ID if it contains a colon
      const cleanedId = cleanId(newVal.id);
      
      // Log incoming product data for debugging
      console.log('Product being edited:', newVal);
      
      // Create a fresh object to avoid reactivity issues
      const productData = {
        id: cleanedId,
        title: newVal.title || '',
        description: newVal.description || '',
        price: newVal.price || '',
        published: normalizePublished(newVal.published),
        track_inventory: Boolean(newVal.track_inventory),
        quantity: parseInt(newVal.quantity || 0),
        reorder_level: parseInt(newVal.reorder_level || 5),
        image_url: newVal.image_url || '',
        category_id: newVal.category_id !== undefined ? newVal.category_id : null  // Handle category_id
      };
      
      // Update the local product with the new values
      Object.assign(localProduct, productData);
      
      // Set the image preview correctly
      if (newVal.image_url) {
        // Release the previous blob URL if there was one
        if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
          URL.revokeObjectURL(imagePreview.value);
        }
        imagePreview.value = newVal.image_url;
      } else {
        imagePreview.value = null;
      }
      
      // Clear errors when loading a new product
      clearErrors();
    } catch (error) {
      console.error('Error setting up product data:', error);
      notifyError('Error loading product data');
    }
  }
}, { immediate: true, deep: true });

// Notify success/error using the notification system
function notifySuccess(message, title = 'Success') {
  if (window.$notification) {
    window.$notification.success(message, title);
  } else {
    store.commit('showToast', {
      type: 'success',
      message,
      title
    });
  }
}

function notifyError(message, title = 'Error') {
  if (window.$notification) {
    window.$notification.error(message, title);
  } else {
    store.commit('showToast', {
      type: 'error',
      message,
      title
    });
  }
}

// Separate function for closing modal to avoid circular updates
function closeModal() {
  clearErrors();
  emit('update:modelValue', false);
  emit('close');
}

function clearImage() {
  // Revoke blob URL if it exists
  if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
    URL.revokeObjectURL(imagePreview.value);
  }
  
  localProduct.image = '';
  localProduct.image_url = '';
  imagePreview.value = null;
}

function handleFileChange(event) {
  const file = event.target.files[0];
  if (!file) return;
  
  // Check file type
  if (!file.type.match('image.*')) {
    errors.image = 'Please select an image file';
    return;
  }
  
  // Check file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    errors.image = 'Image size should be less than 5MB';
    return;
  }
  
  try {
    // Clean up previous preview URL if it exists
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
      URL.revokeObjectURL(imagePreview.value);
    }
    
    // Store the file reference
    localProduct.image = file;
    
    // Create and display image preview using URL.createObjectURL for better performance
    imagePreview.value = URL.createObjectURL(file);
    
    // Clear any previous error
    errors.image = '';
  } catch (error) {
    console.error('Error handling file:', error);
    errors.image = 'Error processing image';
  }
}

// Validate the product form
function validateForm() {
  let isValid = true;
  clearErrors();
  
  // Title validation
  if (!localProduct.title || !localProduct.title.trim()) {
    errors.title = 'Product title is required';
    isValid = false;
  } else if (localProduct.title.length > 255) {
    errors.title = 'Product title must be less than 255 characters';
    isValid = false;
  }
  
  // Price validation
  if (!localProduct.price) {
    errors.price = 'Price is required';
    isValid = false;
  } else {
    const price = parseFloat(localProduct.price);
    if (isNaN(price) || price < 0) {
      errors.price = 'Price must be a positive number';
      isValid = false;
    }
  }
  
  // Description length check (optional field)
  if (localProduct.description && localProduct.description.length > 1000) {
    errors.description = 'Description must be less than 1000 characters';
    isValid = false;
  }
  
  // Validate inventory fields when tracking is enabled
  if (localProduct.track_inventory) {
    if (typeof localProduct.quantity !== 'number') {
      // Try to convert to number if it's not already
      const quantity = parseInt(localProduct.quantity || '0');
      
      if (isNaN(quantity)) {
        errors.quantity = 'Quantity must be a valid number';
        isValid = false;
      } else {
        localProduct.quantity = quantity;
      }
    }
    
    if (localProduct.quantity < 0) {
      errors.quantity = 'Quantity cannot be negative';
      isValid = false;
    }
    
    if (typeof localProduct.reorder_level !== 'number') {
      // Try to convert to number if it's not already
      const reorderLevel = parseInt(localProduct.reorder_level || '5');
      
      if (isNaN(reorderLevel)) {
        errors.reorder_level = 'Reorder level must be a valid number';
        isValid = false;
      } else {
        localProduct.reorder_level = reorderLevel;
      }
    }
    
    if (localProduct.reorder_level < 0) {
      errors.reorder_level = 'Reorder level cannot be negative';
      isValid = false;
    }
  }
  
  return isValid;
}

function onSubmit() {
  if (!validateForm()) {
    return;
  }
  
  loading.value = true;
  
  try {
    // Create FormData object
    const formData = new FormData();
    
    // Add basic fields - ensure correct data types
    formData.append('title', localProduct.title.trim());
    formData.append('description', localProduct.description || '');
    
    // Ensure price is formatted as a valid number
    const priceValue = parseFloat(localProduct.price);
    formData.append('price', isNaN(priceValue) ? "0" : priceValue.toString());
    
    // Boolean values need to be 0/1 for Laravel
    formData.append('published', localProduct.published ? '1' : '0');
    formData.append('track_inventory', localProduct.track_inventory ? '1' : '0');
    
    // Add category_id - handle null value appropriately
    if (localProduct.category_id !== null) {
      formData.append('category_id', localProduct.category_id);
    } else {
      // Send empty string or null depending on backend expectations
      formData.append('category_id', '');
    }
    
    // Only append inventory fields if tracking is enabled
    if (localProduct.track_inventory) {
      // Ensure these are numbers
      const qtyValue = parseInt(localProduct.quantity) || 0;
      const reorderValue = parseInt(localProduct.reorder_level) || 5;
      
      formData.append('quantity', qtyValue.toString());
      formData.append('reorder_level', reorderValue.toString());
    }
    
    // Only append image if it's a File object (new upload)
    if (localProduct.image instanceof File) {
      formData.append('image', localProduct.image);
    } else if (localProduct.id && !imagePreview.value) {
      // If editing a product and image was cleared, explicitly tell the server to remove it
      formData.append('remove_image', '1');
    }
    
    // For updating existing products
    if (localProduct.id) {
      const cleanedId = cleanId(localProduct.id);
      formData.append('id', cleanedId);
      formData.append('_method', 'PUT');
    }
    
    // Debug: Log all form data being sent
    console.log('---- FORM DATA BEING SENT ----');
    for (let [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }
    console.log('---- END FORM DATA ----');
    
    // Use the store actions
    const action = localProduct.id 
      ? store.dispatch('updateProduct', formData)
      : store.dispatch('createProduct', formData);
      
    action
      .then((response) => {
        console.log('Product saved response:', response);
        store.dispatch('getProducts', { force: true });
        closeModal();
        notifySuccess(`Product "${localProduct.title}" ${localProduct.id ? 'updated' : 'created'} successfully!`);
      })
      .catch(error => {
        console.error('API Error:', error.response?.data || error);
        handleApiError(error);
      })
      .finally(() => {
        loading.value = false;
      });
  } catch (error) {
    console.error('Unexpected error:', error);
    notifyError('An unexpected error occurred. Please try again.');
    loading.value = false;
  }
}

// Handle API errors
function handleApiError(error) {
  if (error.response && error.response.data) {
    // Handle Laravel validation errors
    if (error.response.status === 422 && error.response.data.errors) {
      // Map backend validation errors to our local error object
      const backendErrors = error.response.data.errors;
      Object.keys(backendErrors).forEach(key => {
        // Handle Laravel's array of error messages
        if (Array.isArray(backendErrors[key]) && backendErrors[key].length > 0) {
          errors[key] = backendErrors[key][0];
        } else {
          errors[key] = backendErrors[key];
        }
      });
      
      // Scroll to first error field
      setTimeout(() => {
        const firstErrorField = document.querySelector('.text-red-600');
        if (firstErrorField) {
          firstErrorField.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }, 100);
    } else if (error.response.data.message) {
      // Show general error message from server
      notifyError(error.response.data.message);
    } else {
      // Fallback error message
      notifyError('An error occurred while saving the product.');
    }
  } else {
    // Network or other error
    notifyError('Network error. Please check your connection and try again.');
  }
}
</script>