<template>
  <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <!-- Table Header with Search and Filters -->
    <div class="p-5 border-b border-gray-200 bg-gray-50">
      <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
        <!-- Left side - Per page and results count -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center">
            <label for="perPage" class="text-sm font-medium text-gray-700 mr-2">Show</label>
            <select
              id="perPage"
              @change="getCategories(null)"
              v-model="perPage"
              class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
            >
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">entries</span>
          </div>
          <div class="text-sm text-gray-600">
            <span class="font-medium">{{ categories.total || 0 }}</span> categories found
          </div>
        </div>

        <!-- Right side - Search -->
        <div class="w-full md:w-64">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                  clip-rule="evenodd"
                />
              </svg>
            </div>
            <input
              v-model="search"
              @input="debounceSearch"
              type="text"
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm"
              placeholder="Search categories..."
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <!-- ID -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              ID
            </th>

            <!-- Category Details -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Category Details
            </th>

            <!-- Products Count -->
            <th
              class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Products
            </th>

            <!-- Parent Category -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Parent
            </th>

            <!-- Status (centered) -->
            <th
              class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
            </th>

            <!-- Last Updated -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Last Updated
            </th>

            <!-- Actions (left-aligned) -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </th>
          </tr>
        </thead>

        <!-- Loading / Empty State -->
        <tbody v-if="loading || !categories.data || !categories.data.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="7" class="px-6 py-10 text-center">
              <div v-if="loading" class="flex justify-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              <div v-else class="text-gray-500">
                <svg
                  class="mx-auto h-12 w-12 text-gray-400"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0h10a2 2 0 012 2v2M7 7h10"
                  />
                </svg>
                <p class="mt-2 text-sm font-medium">No categories found</p>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
              </div>
            </td>
          </tr>
        </tbody>

        <!-- Data Rows -->
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr
            v-for="category in categories.data"
            :key="category.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- ID -->
            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
              {{ category.id }}
            </td>

            <!-- Category Details -->
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900 truncate">
                {{ category.name }}
              </div>
              <div
                v-if="category.description"
                class="text-sm text-gray-500 truncate max-w-xs mt-1"
              >
                {{ category.description }}
              </div>
            </td>

            <!-- Products Count -->
            <td class="px-6 py-4 text-sm font-medium text-center whitespace-nowrap">
              {{ category.product_count || 0 }}
            </td>
            
            <!-- Parent Category -->
            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
              {{ getParentCategoryName(category.parent_id) || 'None' }}
            </td>

            <!-- Status (centered) -->
            <td class="px-6 py-4 text-center whitespace-nowrap">
              <button
                @click="toggleStatus(category)"
                :class="[
                  'inline-flex items-center justify-center px-2.5 py-1 text-xs font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 min-w-[88px] transition-colors',
                  category.is_active
                    ? 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500'
                    : 'bg-gray-100 text-gray-800 hover:bg-gray-200 focus:ring-gray-500'
                ]"
              >
                <span class="flex items-center">
                  <span class="relative flex-shrink-0 h-2 w-2 mr-1.5">
                    <span
                      :class="['absolute inset-0 rounded-full', category.is_active ? 'bg-green-600' : 'bg-gray-600']"
                    ></span>
                    <span
                      :class="['animate-ping absolute inset-0 rounded-full opacity-75', category.is_active ? 'bg-green-600' : 'bg-gray-600']"
                    ></span>
                  </span>
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </button>
            </td>

            <!-- Last Updated -->
            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
              {{ formatDate(category.updated_at) }}
            </td>

            <!-- Actions (left-aligned) -->
            <td class="px-6 py-4 text-left text-sm font-medium whitespace-nowrap">
              <div class="flex space-x-2">
                <button
                  @click="editCategory(category)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                >
                  <svg
                    class="mr-1.5 h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                  Edit
                </button>
                <button
                  @click="viewProducts(category)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors"
                >
                  <svg
                    class="mr-1.5 h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                    />
                  </svg>
                  Products
                </button>
                <button
                  @click="deleteCategory(category)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                  <svg
                    class="mr-1.5 h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div
      v-if="!loading && categories.data && categories.data.length > 0 && categories.links"
      class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
    >
      <div class="flex flex-col sm:flex-row justify-between items-center">
        <div class="text-sm text-gray-700 mb-2 sm:mb-0">
          Showing <span class="font-medium">{{ categories.from }}</span> to
          <span class="font-medium">{{ categories.to }}</span> of
          <span class="font-medium">{{ categories.total }}</span> results
        </div>
        <nav
          v-if="categories.total > categories.per_page"
          class="relative z-0 inline-flex shadow-sm rounded-md -space-x-px"
          aria-label="Pagination"
        >
          <template v-for="(link, i) in categories.links" :key="i">
            <button
              :disabled="!link.url"
              href="#"
              @click="getForPage($event, link)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '',
                i === categories.links.length - 1 ? 'rounded-r-md' : '',
                !link.url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : ''
              ]"
            >
              <!-- Previous page arrow icon -->
              <template v-if="i === 0">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Previous</span>
              </template>
              
              <!-- Next page arrow icon -->
              <template v-else-if="i === categories.links.length - 1">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Next</span>
              </template>
              
              <!-- Regular page numbers -->
              <template v-else>
                {{ link.label }}
              </template>
            </button>
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineEmits } from "vue";
import { useRouter } from 'vue-router';
import axiosClient from "../../axios";

const router = useRouter();
const emit = defineEmits(["clickEdit", "statusChanged"]);

const categories = ref({
  data: [],
  links: [],
  meta: {},
  from: 0,
  to: 0,
  total: 0,
  per_page: 10
});
const loading = ref(false);
const search = ref("");
const perPage = ref(10);
const searchTimeout = ref(null);
const parentCategories = ref([]);

// Load all categories initially for parent lookup
function loadParentCategories() {
  axiosClient.get('/categories/list')
    .then(response => {
      parentCategories.value = response.data;
    })
    .catch(error => {
      console.error('Error loading parent categories:', error);
    });
}

function getParentCategoryName(parentId) {
  if (!parentId) return null;
  
  const parent = parentCategories.value.find(c => c.id === parentId);
  return parent ? parent.name : `Category #${parentId}`;
}

function formatDate(dateStr) {
  if (!dateStr) return 'N/A';
  
  try {
    const date = new Date(dateStr);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return 'Invalid Date';
    }
    
    return new Intl.DateTimeFormat('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  } catch (error) {
    console.error('Error formatting date:', error);
    return 'Error';
  }
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) return;
  getCategories(link.url);
}

function getCategories(url = null) {
  loading.value = true;
  
  url = url || '/categories';
  
  // Prepare query parameters
  const params = {};
  
  // Add standard parameters
  params.search = search.value || '';
  params.per_page = perPage.value;
  params.include = 'products'; // Include product counts
  
  return axiosClient.get(url, { params })
    .then((response) => {
      categories.value = response.data;
    })
    .catch((error) => {
      console.error('Error fetching categories:', error);
      showError('Failed to load categories. Please try again.');
    })
    .finally(() => {
      loading.value = false;
    });
}

// Debounced search
function debounceSearch() {
  if (searchTimeout.value) clearTimeout(searchTimeout.value);
  searchTimeout.value = setTimeout(() => {
    getCategories();
  }, 300);
}

function showError(message, title = 'Error') {
  if (window.$notification) {
    window.$notification.error(message, title);
  } else {
    console.error(`${title}: ${message}`);
  }
}

function editCategory(category) {
  emit("clickEdit", category);
}

function toggleStatus(category) {
  const updatedCategory = { 
    ...category,
    is_active: !category.is_active 
  };
  
  loading.value = true;
  
  axiosClient.put(`/categories/${category.id}`, updatedCategory)
    .then(() => {
      // Success notification
      if (window.$notification) {
        window.$notification.success(
          `Category "${category.name}" is now ${updatedCategory.is_active ? 'active' : 'inactive'}.`,
          'Status Updated'
        );
      }
      
      // Refresh the categories
      getCategories();
      emit("statusChanged");
    })
    .catch(error => {
      console.error('Error updating category status:', error);
      showError('Failed to update category status. Please try again.');
    })
    .finally(() => {
      loading.value = false;
    });
}

function deleteCategory(category) {
  if (!confirm(`Are you sure you want to delete "${category.name}"? This action cannot be undone.`)) {
    return;
  }
  
  loading.value = true;
  
  axiosClient.delete(`/categories/${category.id}`)
    .then(() => {
      // Success notification
      if (window.$notification) {
        window.$notification.success(
          `Category "${category.name}" has been deleted.`,
          'Category Deleted'
        );
      }
      
      // Refresh the categories
      getCategories();
    })
    .catch(error => {
      console.error('Error deleting category:', error);
      
      // Show specific error message from server if available
      let errorMessage = 'Failed to delete category. Please try again.';
      if (error.response && error.response.data && error.response.data.message) {
        errorMessage = error.response.data.message;
      } else if (error.response && error.response.data && error.response.data.errors && error.response.data.errors.general) {
        errorMessage = error.response.data.errors.general[0];
      }
      
      showError(errorMessage);
    })
    .finally(() => {
      loading.value = false;
    });
}

function viewProducts(category) {
  // Navigate to products page with category filter
  router.push({
    name: 'app.products',
    query: { category_id: category.id }
  });
}

onMounted(() => {
  getCategories();
  loadParentCategories();
});
</script>

<style scoped>
@keyframes ping {
  75%, 100% {
    transform: scale(2);
    opacity: 0;
  }
}
.animate-ping {
  animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>