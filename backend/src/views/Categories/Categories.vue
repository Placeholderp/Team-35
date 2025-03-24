<template>
  <div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Page header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 leading-tight">Categories</h1>
          <p class="mt-2 text-sm text-gray-600">
            Manage product categories and their hierarchy
          </p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
          <button
            @click="showAddNewModal"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Category
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
      <!-- Total Categories Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0h10a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Total Categories
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ totalCategories }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>

      <!-- Active Categories Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Active Categories
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ activeCategories }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories with Products Card -->
      <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dt class="text-sm font-medium text-gray-500 truncate">
                Categories with Products
              </dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">
                  {{ categoriesWithProducts }}
                </div>
              </dd>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <CategoriesTable @clickEdit="editCategory" @statusChanged="refreshCategories" />
    </div>

    <!-- Category Modal -->
    <CategoryModal v-model="showCategoryModal" :category="categoryModel" @close="onModalClose" />
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex"; // Added proper store import
import CategoryModal from "./CategoryModal.vue";
import CategoriesTable from "./CategoriesTable.vue";
import axiosClient from "../../axios";

const store = useStore(); // Initialize store

const DEFAULT_CATEGORY = {
  id: '',
  name: '',
  description: '',
  image: '',
  is_active: true,
  parent_id: null,
  sort_order: 0
};

const categoryModel = ref({ ...DEFAULT_CATEGORY });
const showCategoryModal = ref(false);
const categories = ref([]);
const loading = ref(false);

// Computed stats
const totalCategories = computed(() => categories.value.length);
const activeCategories = computed(() => categories.value.filter(cat => cat.is_active).length);
const categoriesWithProducts = computed(() => categories.value.filter(cat => cat.product_count > 0).length);

// Helper methods for notifications
function showSuccess(message, title = 'Success') {
  if (window.$notification) {
    window.$notification.success(message, title);
  } else {
    console.log(`${title}: ${message}`);
  }
}

function showError(message, title = 'Error') {
  if (window.$notification) {
    window.$notification.error(message, title);
  } else {
    console.error(`${title}: ${message}`);
  }
}

// Load categories
function fetchCategories() {
  loading.value = true;
  
  axiosClient.get('/categories?include=products')
    .then(response => {
      categories.value = response.data.data;
    })
    .catch(error => {
      console.error('Error fetching categories:', error);
      showError('Failed to load categories. Please try again.');
    })
    .finally(() => {
      loading.value = false;
    });
}

function refreshCategories() {
  console.log('Refreshing categories from Vuex...');
  // Use the Vuex action instead of direct axios call
  store.dispatch('getCategories', { force: true });
}

function showAddNewModal() {
  categoryModel.value = { ...DEFAULT_CATEGORY }; 
  showCategoryModal.value = true;
}

function editCategory(category) {
  // Ensure we have an ID
  if (!category || !category.id) {
    showError('Invalid category - missing ID');
    return;
  }
  
  try {
    // Create a safe copy
    categoryModel.value = { ...category };
    showCategoryModal.value = true;
  } catch (error) {
    console.error('Error preparing category for edit:', error);
    showError('Failed to prepare category for editing');
  }
}

function onModalClose() {
  console.log('Modal closed, refreshing categories from store');
  
  // Reset the category model
  categoryModel.value = { ...DEFAULT_CATEGORY };
  showCategoryModal.value = false;
  
  // Refresh categories using Vuex
  refreshCategories();
}

onMounted(() => {
  fetchCategories();
});
</script>