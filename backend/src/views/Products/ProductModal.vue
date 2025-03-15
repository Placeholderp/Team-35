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
import axiosClient from "../../axios";
import { ref, watch, reactive, computed } from 'vue';
import { Dialog, DialogPanel, DialogTitle, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue';
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import { cleanId, normalizePublished, prepareProductFormData } from "../../utils/ProductUtils";

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
      published: false
    })
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
  published: ''
});

// Create a local copy of the product for editing
const localProduct = reactive({
  id: '',
  title: '',
  description: '',
  image: '',
  price: '',
  published: false
});

watch(() => props.product, (newVal) => {
  if (newVal) {
    // Clean the ID if it contains a colon
    const cleanedId = cleanId(newVal.id);
    
    Object.assign(localProduct, {
      id: cleanedId,
      title: newVal.title || '',
      image: newVal.image || '',
      description: newVal.description || '',
      price: newVal.price || '',
      published: normalizePublished(newVal.published)
    });
    
    // Reset image preview if editing an existing product with an image
    if (cleanedId && newVal.image_url) {
      imagePreview.value = newVal.image_url;
    } else {
      imagePreview.value = null;
    }
    
    // Clear errors when loading a new product
    clearErrors();
  }
}, { immediate: true, deep: true });

// Clear all validation errors
function clearErrors() {
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
}

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
  localProduct.image = '';
  imagePreview.value = null;
}

function handleFileChange(event) {
  if (event.target && event.target.files && event.target.files.length > 0) {
    const file = event.target.files[0];
    if (file) {
      // Validate file is an image
      if (!file.type.match('image.*')) {
        errors.image = 'Please select an image file';
        return;
      }
      
      // Store the file reference
      localProduct.image = file;
      
      // Create and display image preview
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.value = e.target.result;
      };
      reader.readAsDataURL(file);
      
      // Clear any previous error
      errors.image = '';
    }
  }
}

function validateForm() {
  let isValid = true;
  clearErrors();
  
  if (!localProduct.title.trim()) {
    errors.title = 'Product title is required';
    isValid = false;
  }
  
  if (!localProduct.price || parseFloat(localProduct.price) < 0) {
    errors.price = 'Please enter a valid price';
    isValid = false;
  }
  
  return isValid;
}

function onSubmit() {
  if (!validateForm()) {
    return;
  }
  
  loading.value = true;
  
  try {
    // Use the utility function to prepare form data
    const formData = prepareProductFormData(localProduct, !!localProduct.id);
    
    const action = localProduct.id 
      ? store.dispatch('updateProduct', formData)
      : store.dispatch('createProduct', formData);
      
    action
      .then(() => {
        store.dispatch('getProducts', { force: true });
        closeModal();
        notifySuccess(`Product "${localProduct.title}" ${localProduct.id ? 'updated' : 'created'} successfully!`);
      })
      .catch(error => {
        handleApiError(error);
      })
      .finally(() => {
        loading.value = false;
      });
  } catch (error) {
    notifyError('An unexpected error occurred. Please try again.');
    loading.value = false;
  }
}

function handleApiError(error) {
  if (error.response && error.response.data && error.response.data.errors) {
    // Map backend validation errors to our local error object
    const backendErrors = error.response.data.errors;
    Object.keys(backendErrors).forEach(key => {
      if (errors.hasOwnProperty(key)) {
        errors[key] = backendErrors[key][0]; // Usually the first error message is enough
      }
    });
  } else {
    // General error handling
    notifyError('An error occurred. Please try again.');
  }
}
</script>