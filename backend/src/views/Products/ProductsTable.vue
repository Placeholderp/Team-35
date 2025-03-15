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
              @change="getProducts(null)"
              v-model="perPage"
              class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
            >
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">entries</span>
          </div>
          <div class="text-sm text-gray-600">
            <span class="font-medium">{{ products.total }}</span> products found
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
              placeholder="Search products..."
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

            <!-- Image -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Image
            </th>

            <!-- Product Details -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Product Details
            </th>

            <!-- Price (left-aligned) -->
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Price
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
        <tbody v-if="products.loading || !products.data.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="7" class="px-6 py-10 text-center">
              <Spinner v-if="products.loading" />
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
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                  />
                </svg>
                <p class="mt-2 text-sm font-medium">No products found</p>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
              </div>
            </td>
          </tr>
        </tbody>

        <!-- Data Rows -->
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr
            v-for="product in products.data"
            :key="product.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- ID -->
            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
              {{ product.id }}
            </td>

            <!-- Image -->
            <td class="px-6 py-4 whitespace-nowrap">
              <div
                v-if="product.image_url"
                class="h-16 w-16 rounded-md overflow-hidden bg-gray-100 border border-gray-200"
              >
                <img
                  class="h-full w-full object-cover"
                  :src="product.image_url"
                  :alt="product.title"
                />
              </div>
              <div
                v-else
                class="h-16 w-16 rounded-md bg-gray-100 flex items-center justify-center border border-gray-200"
              >
                <svg class="h-8 w-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
            </td>

            <!-- Product Details -->
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900 truncate">
                {{ product.title }}
              </div>
              <div
                v-if="product.description"
                class="text-sm text-gray-500 truncate max-w-xs mt-1"
              >
                {{ product.description }}
              </div>
            </td>

            <!-- Price (left-aligned) -->
            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
              {{ formatCurrency(product.price) }}
            </td>

            <!-- Status (centered) -->
            <td class="px-6 py-4 text-center whitespace-nowrap">
              <button
                @click="togglePublishStatus(product)"
                :class="[
                  'inline-flex items-center justify-center px-2.5 py-1 text-xs font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 min-w-[88px] transition-colors',
                  isPublished(product)
                    ? 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500'
                    : 'bg-gray-100 text-gray-800 hover:bg-gray-200 focus:ring-gray-500'
                ]"
              >
                <span class="flex items-center">
                  <span class="relative flex-shrink-0 h-2 w-2 mr-1.5">
                    <span
                      :class="['absolute inset-0 rounded-full', isPublished(product) ? 'bg-green-600' : 'bg-gray-600']"
                    ></span>
                    <span
                      :class="['animate-ping absolute inset-0 rounded-full opacity-75', isPublished(product) ? 'bg-green-600' : 'bg-gray-600']"
                    ></span>
                  </span>
                  {{ isPublished(product) ? 'Published' : 'Draft' }}
                </span>
              </button>
            </td>

            <!-- Last Updated -->
            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
              {{ formatDate(product.updated_at) }}
            </td>

            <!-- Actions (left-aligned) -->
            <td class="px-6 py-4 text-left text-sm font-medium whitespace-nowrap">
              <div class="flex space-x-2">
                <button
                  @click="editProduct(product)"
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
                  @click="deleteProduct(product)"
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
      v-if="!products.loading && products.data.length > 0"
      class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
    >
      <div class="flex flex-col sm:flex-row justify-between items-center">
        <div class="text-sm text-gray-700 mb-2 sm:mb-0">
          Showing <span class="font-medium">{{ products.from }}</span> to
          <span class="font-medium">{{ products.to }}</span> of
          <span class="font-medium">{{ products.total }}</span> results
        </div>
        <nav
          v-if="products.total > products.limit"
          class="relative z-0 inline-flex shadow-sm rounded-md -space-x-px"
          aria-label="Pagination"
        >
          <template v-for="(link, i) in products.links" :key="i">
            <a
              :disabled="!link.url"
              href="#"
              @click="getForPage($event, link)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '',
                i === products.links.length - 1 ? 'rounded-r-md' : '',
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
              <template v-else-if="i === products.links.length - 1">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Next</span>
              </template>
              
              <!-- Regular page numbers -->
              <template v-else>
                {{ link.label }}
              </template>
            </a>
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, defineEmits } from "vue";
import store from "../../store";
import axiosClient from "../../axios";
import Spinner from "../../components/core/Spinner.vue";
import { PRODUCTS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import {
  cleanId,
  normalizePublished,
  formatCurrency,
  formatDate,
  prepareProductFormData
} from "../../utils/ProductUtils";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const searchTimeout = ref(null);

const products = computed(() => store.state.products);
const sortField = ref("updated_at");
const sortDirection = ref("desc");

const emit = defineEmits(["clickEdit", "statusChanged"]);

onMounted(() => {
  getProducts();
});

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) return;
  getProducts(link.url);
}

function getProducts(url = null) {
  store.dispatch("getProducts", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

// Debounced search
function debounceSearch() {
  if (searchTimeout.value) clearTimeout(searchTimeout.value);
  searchTimeout.value = setTimeout(() => {
    getProducts();
  }, 300);
}

function sortProducts(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }
  getProducts();
}

function isPublished(product) {
  return normalizePublished(product?.published);
}

function togglePublishStatus(product) {
  const currentStatus = isPublished(product);
  const newStatus = !currentStatus;
  const id = cleanId(product.id);
  const updatedProductData = {
    ...product,
    id,
    published: newStatus
  };
  const formData = prepareProductFormData(updatedProductData, true);

  // Optimistic UI update
  store.commit('setProducts', [true]);
  const updatedProduct = JSON.parse(JSON.stringify(updatedProductData));
  store.commit('updateProductInList', updatedProduct);

  axiosClient.post(`/products/${id}`, formData)
    .then(() => {
      return store.dispatch('getProducts', {
        force: true,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
      });
    })
    .then(() => {
      emit('statusChanged');
      if (window.$notification) {
        window.$notification.success(
          `Product "${product.title}" is now ${newStatus ? 'published' : 'unpublished'}.`,
          'Status Updated'
        );
      } else {
        store.commit('showToast', {
          type: 'success',
          message: `Product "${product.title}" is now ${newStatus ? 'published' : 'unpublished'}.`,
          title: 'Status Updated'
        });
      }
    })
    .catch(() => {
      // Revert if error
      store.commit('updateProductInList', {
        ...updatedProduct,
        published: currentStatus
      });
      if (window.$notification) {
        window.$notification.error('Failed to update product status. Please try again.');
      } else {
        store.commit('showToast', {
          type: 'error',
          message: 'Failed to update product status. Please try again.',
          title: 'Error'
        });
      }
    })
    .finally(() => {
      store.commit('setProducts', [false]);
    });
}

function editProduct(product) {
  const cleanedProduct = { ...product };
  cleanedProduct.id = cleanId(product.id);
  emit("clickEdit", cleanedProduct);
}

function deleteProduct(product) {
  if (!confirm(`Are you sure you want to delete "${product.title}"?`)) return;
  const id = cleanId(product.id);

  store.commit('setProducts', [true]);
  axiosClient.delete(`/products/${id}`)
    .then(() => {
      store.dispatch("getProducts", {
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value,
        force: true
      });
      if (window.$notification) {
        window.$notification.success(`Product "${product.title}" was deleted successfully.`);
      } else {
        store.commit('showToast', {
          type: 'success',
          message: `Product "${product.title}" was deleted successfully.`,
          title: 'Success'
        });
      }
    })
    .catch(() => {
      if (window.$notification) {
        window.$notification.error('Failed to delete the product. Please try again.');
      } else {
        store.commit('showToast', {
          type: 'error',
          message: 'Failed to delete the product. Please try again.',
          title: 'Error'
        });
      }
    })
    .finally(() => {
      store.commit('setProducts', [false]);
    });
}
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