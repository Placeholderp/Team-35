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
                <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 z-10">
                  <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </div>
                
                <div class="bg-indigo-700 px-6 py-4">
                  <div class="flex items-center justify-between">
                    <DialogTitle class="text-xl font-semibold text-white">
                      {{ localCategory.id ? `Edit: ${localCategory.name}` : 'Add New Category' }}
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
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                          Category Name <span class="text-red-600">*</span>
                        </label>
                        <input
                          type="text"
                          id="name"
                          v-model="localCategory.name"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          required
                          placeholder="Enter category name"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                      </div>
                      
                      <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                          id="description"
                          v-model="localCategory.description"
                          rows="3"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="Enter category description"
                        ></textarea>
                        <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                      </div>
                      
                      <div>
                        <label for="parent-id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select
                          id="parent-id"
                          v-model="localCategory.parent_id"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        >
                          <option :value="null">None (Top Level Category)</option>
                          <option 
                            v-for="category in availableParentCategories" 
                            :key="category.id" 
                            :value="category.id"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                        <p v-if="errors.parent_id" class="mt-1 text-sm text-red-600">{{ errors.parent_id }}</p>
                      </div>
                      
                      <div>
                        <label for="sort-order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input
                          type="number"
                          id="sort-order"
                          v-model.number="localCategory.sort_order"
                          min="0"
                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="0"
                        />
                        <p class="mt-1 text-xs text-gray-500">Categories are sorted in ascending order</p>
                        <p v-if="errors.sort_order" class="mt-1 text-sm text-red-600">{{ errors.sort_order }}</p>
                      </div>
                    </div>
                    
                    <div>
                      <div class="flex items-start">
                        <div class="flex items-center h-5">
                          <input
                            id="is-active"
                            name="is_active"
                            type="checkbox"
                            v-model="localCategory.is_active"
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                          />
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="is-active" class="font-medium text-gray-700">Active</label>
                          <p class="text-gray-500">Only active categories are visible to customers</p>
                        </div>
                      </div>
                      <p v-if="errors.is_active" class="mt-1 text-sm text-red-600">{{ errors.is_active }}</p>
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
                      {{ localCategory.id ? 'Update Category' : 'Create Category' }}
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
import { ref, watch, reactive, computed, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue';
import axiosClient from "../../axios";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  category: {
    type: Object,
    required: true,
    default: () => ({
      id: '',
      name: '',
      description: '',
      is_active: true,
      parent_id: null,
      sort_order: 0
    })
  }
});

const emit = defineEmits(['update:modelValue', 'close']);
const loading = ref(false);
const errors = reactive({
  name: '',
  description: '',
  parent_id: '',
  sort_order: '',
  is_active: ''
});

// Local copy of the category for editing
const localCategory = reactive({
  id: '',
  name: '',
  description: '',
  is_active: true,
  parent_id: null,
  sort_order: 0
});

// All parent categories
const parentCategories = ref([]);

// Computed list of available parent categories (excluding self and children)
const availableParentCategories = computed(() => {
  if (!localCategory.id) return parentCategories.value;
  
  // Filter out self and potentially children if we implement hierarchical categories
  return parentCategories.value.filter(category => category.id !== localCategory.id);
});

// Clear all validation errors
function clearErrors() {
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
}

// Load all categories for parent dropdown
function loadParentCategories() {
  axiosClient.get('/categories/list')
    .then(response => {
      parentCategories.value = response.data;
    })
    .catch(error => {
      console.error('Error loading parent categories:', error);
    });
}

// Watch for changes to the category prop
watch(() => props.category, (newVal) => {
  if (newVal) {
    try {
      // Create a fresh object to avoid reactivity issues
      const categoryData = {
        id: newVal.id || '',
        name: newVal.name || '',
        description: newVal.description || '',
        is_active: newVal.is_active !== undefined ? newVal.is_active : true,
        parent_id: newVal.parent_id || null,
        sort_order: newVal.sort_order !== undefined ? newVal.sort_order : 0
      };
      
      // Update the local category with the new values
      Object.assign(localCategory, categoryData);
      
      // Clear errors when loading a new category
      clearErrors();
    } catch (error) {
      console.error('Error setting up category data:', error);
      showNotification('Error loading category data', 'error');
    }
  }
}, { immediate: true, deep: true });

// Notify success/error
function showNotification(message, type = 'success') {
  if (window.$notification) {
    if (type === 'success') {
      window.$notification.success(message);
    } else {
      window.$notification.error(message);
    }
  } else {
    if (type === 'success') {
      console.log(`Success: ${message}`);
    } else {
      console.error(`Error: ${message}`);
    }
  }
}

// Close modal function
function closeModal() {
  clearErrors();
  emit('update:modelValue', false);
  emit('close');
}

// Validate the form
function validateForm() {
  let isValid = true;
  clearErrors();
  
  // Name validation
  if (!localCategory.name || !localCategory.name.trim()) {
    errors.name = 'Category name is required';
    isValid = false;
  } else if (localCategory.name.length > 255) {
    errors.name = 'Category name must be less than 255 characters';
    isValid = false;
  }
  
  // Parent validation
  if (localCategory.parent_id && localCategory.parent_id === localCategory.id) {
    errors.parent_id = 'A category cannot be its own parent';
    isValid = false;
  }
  
  // Sort order validation
  if (localCategory.sort_order < 0) {
    errors.sort_order = 'Sort order cannot be negative';
    isValid = false;
  }
  
  return isValid;
}

function onSubmit() {
  if (!validateForm()) {
    return;
  }
  
  loading.value = true;
  
  const categoryData = {
    name: localCategory.name.trim(),
    description: localCategory.description || '',
    is_active: localCategory.is_active ? 1 : 0,
    sort_order: localCategory.sort_order,
    parent_id: localCategory.parent_id
  };
  
  // For updating existing categories
  if (localCategory.id) {
    axiosClient.put(`/categories/${localCategory.id}`, categoryData)
      .then(() => {
        showNotification(`Category "${localCategory.name}" updated successfully`);
        closeModal();
      })
      .catch(handleApiError)
      .finally(() => {
        loading.value = false;
      });
  } else {
    // For creating new categories
    axiosClient.post('/categories', categoryData)
      .then(() => {
        showNotification(`Category "${localCategory.name}" created successfully`);
        closeModal();
      })
      .catch(handleApiError)
      .finally(() => {
        loading.value = false;
      });
  }
}

// Handle API errors
function handleApiError(error) {
  console.error('API Error:', error);
  
  if (error.response && error.response.data) {
    // Handle validation errors
    if (error.response.status === 422 && error.response.data.errors) {
      const backendErrors = error.response.data.errors;
      
      Object.keys(backendErrors).forEach(key => {
        if (Array.isArray(backendErrors[key]) && backendErrors[key].length > 0) {
          errors[key] = backendErrors[key][0];
        } else {
          errors[key] = backendErrors[key];
        }
      });
    } else if (error.response.data.message) {
      // Show general error message
      showNotification(error.response.data.message, 'error');
    } else {
      // Fallback error
      showNotification('An error occurred while saving the category', 'error');
    }
  } else {
    // Network error
    showNotification('Network error. Please check your connection and try again', 'error');
  }
}

onMounted(() => {
  loadParentCategories();
});
</script>