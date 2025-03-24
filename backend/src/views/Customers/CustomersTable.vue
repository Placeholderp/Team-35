<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table Toolbar -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <!-- Left side controls -->
        <div class="flex items-center space-x-3">
          <div class="flex items-center">
            <span class="text-gray-600 mr-2">Show</span>
            <select
              @change="onPerPageChange"
              v-model="perPage"
              class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            >
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span class="ml-2 text-sm text-gray-600">entries</span>
          </div>
          <span class="text-sm text-gray-600">{{ customers.total || 0 }} total records</span>
        </div>
        
        <!-- Right side controls -->
        <div class="flex items-center space-x-3">
          <!-- Status filter dropdown -->
          <select
            v-model="statusFilter"
            @change="onStatusFilterChange"
            class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Statuses</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
          
          <!-- Search box -->
          <div class="relative flex-1 min-w-[240px]">
            <input
              v-model="search"
              @input="onSearchInputDelayed"
              class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full text-sm"
              placeholder="Search customers..."
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customers Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <TableHeaderCell
              field="id"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortCustomers('id')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              ID
            </TableHeaderCell>
            <TableHeaderCell
              field="name"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortCustomers('name')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Customer
            </TableHeaderCell>
            <TableHeaderCell
              field="email"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortCustomers('email')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Contact
            </TableHeaderCell>
            <TableHeaderCell
              field="status"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortCustomers('status')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
            </TableHeaderCell>
            <TableHeaderCell
              field="created_at"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortCustomers('created_at')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Registered
            </TableHeaderCell>
            <TableHeaderCell
              class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </TableHeaderCell>
          </tr>
        </thead>

        <tbody v-if="customers.loading || !customers.data?.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="6" class="px-6 py-10 text-center">
              <Spinner v-if="customers.loading" class="mx-auto" />
              <p v-else class="text-gray-500 text-sm">
                No customers found
              </p>
            </td>
          </tr>
        </tbody>
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr v-for="(customer, index) of customers.data" :key="index" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              #{{ getCustomerId(customer) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-lg">
                    {{ getInitials(customer) }}
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ customer.first_name }} {{ customer.last_name }}</div>
                  <div v-if="customer.company" class="text-sm text-gray-500">{{ customer.company }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ customer.email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="customer.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                {{ customer.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(customer.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
              <div class="flex justify-end space-x-2">
                <router-link
                  :to="{ name: 'app.customers.view', params: { id: getCustomerId(customer) } }"
                  class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors"
                  title="Edit Customer"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                </router-link>
                <button 
                  @click="deleteCustomer(customer)"
                  class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors"
                  title="Delete Customer"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!customers.loading" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <button 
          @click="getCustomers(customers.prev_page_url)"
          :disabled="!customers.prev_page_url"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="[!customers.prev_page_url ? 'opacity-50 cursor-not-allowed' : '']"
        >
          Previous
        </button>
        <button
          @click="getCustomers(customers.next_page_url)"
          :disabled="!customers.next_page_url"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="[!customers.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ customers.from || 0 }}</span> to <span class="font-medium">{{ customers.to || 0 }}</span> of <span class="font-medium">{{ customers.total }}</span> results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a
              v-for="(link, i) of customers.links"
              :key="i"
              href="#"
              @click.prevent="getForPage($event, link)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                !link.url ? 'bg-gray-100 text-gray-700 cursor-not-allowed' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : '',
                i === 0 ? 'rounded-l-md' : '',
                i === customers.links.length - 1 ? 'rounded-r-md' : '',
              ]"
              v-html="link.label"
            ></a>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, defineEmits, watch } from 'vue'
import { useRouter } from 'vue-router'
import store from '../../store'
import Spinner from '../../components/core/Spinner.vue'
import { CUSTOMERS_PER_PAGE } from '../../constants'
import TableHeaderCell from '../../components/core/Table/TableHeaderCell.vue'
import { debounce } from 'lodash'

const router = useRouter()
// Convert to number to ensure proper typing
const perPage = ref(Number(CUSTOMERS_PER_PAGE))
const search = ref('')
const statusFilter = ref('')
const customers = computed(() => store.state.customers)

const sortField = ref('created_at')
const sortDirection = ref('desc')

const emit = defineEmits(['clickEdit'])

// Explicitly define handlers for filters
function onPerPageChange() {
  console.log('Per page changed to:', perPage.value)
  loadCustomers()
}

function onStatusFilterChange() {
  console.log('Status filter changed to:', statusFilter.value)
  loadCustomers()
}

// Use debounce to prevent too many API calls while typing
const onSearchInputDelayed = debounce(() => {
  console.log('Search changed to:', search.value)
  loadCustomers()
}, 500)

onMounted(() => {
  // Initial load
  loadCustomers()
})

// Function to handle pagination links
function getForPage(ev, link) {
  if (!link.url || link.active) return
  getCustomers(link.url)
}

// Main function to load customers - all filter changes use this
function loadCustomers() {
  getCustomers(null)
}

// Core function that makes the API call
// Core function that makes the API call
function getCustomers(url = null) {
  // Clean and sanitize parameters
  const cleanParams = {
    url,
    per_page: Number(perPage.value) || 10,
    sort_field: sortField.value || 'created_at',
    sort_direction: sortDirection.value || 'desc',
  }
  
  // Only add search if it's not empty
  if (search.value && search.value.trim() !== '') {
    cleanParams.search = search.value.trim()
  }
  
  // Only add status if it has a value and ensure it's properly formatted
  if (statusFilter.value !== '') {
    // Remove any non-numeric characters to ensure it's clean
    cleanParams.status = String(statusFilter.value).replace(/[^0-9]/g, '')
  }
  
  console.log('Requesting customers with params:', cleanParams)
  
  // Track if user is using filters (for fallback purposes)
  const isFiltered = Boolean(
    cleanParams.search || 
    cleanParams.status !== undefined || 
    cleanParams.sort_field !== 'created_at' || 
    cleanParams.sort_direction !== 'desc'
  )
  
  store.dispatch('getCustomers', cleanParams)
    .then(() => {
      console.log('Customers loaded successfully')
    })
    .catch(error => {
      console.error('Error loading customers:', error)
      
      if (isFiltered) {
        // If filtering failed, attempt to load without filters
        store.commit('showToast', 'Error with filters. Showing unfiltered results.', 'warning')
        
        // Reset UI filters
        search.value = ''
        statusFilter.value = ''
        sortField.value = 'created_at'
        sortDirection.value = 'desc'
        
        // Try again with minimal parameters
        store.dispatch('getCustomers', {
          url: null,
          per_page: Number(perPage.value) || 10
        }).catch(fallbackError => {
          console.error('Even fallback failed:', fallbackError)
          store.commit('showToast', 'Unable to load customers. Please try again.', 'error')
        })
      } else {
        // Just show error toast
        store.commit('showToast', 'Error loading customers. Please try again.', 'error')
      }
    })
}
function sortCustomers(field) {
  console.log('Sorting by field:', field)
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  loadCustomers()
}

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function getCustomerId(customer) {
  return customer.user_id || customer.id;
}

function deleteCustomer(customer) {
  const customerId = getCustomerId(customer);
  
  if (!confirm(`Are you sure you want to delete customer "${customer.first_name} ${customer.last_name}"?`)) return;
  
  store.dispatch('deleteCustomer', customerId)
    .then(() => {
      store.commit('showToast', 'Customer deleted successfully');
      loadCustomers();
    })
    .catch((error) => {
      console.error('Error deleting customer:', error);
      store.commit('showToast', 'Error deleting customer', 'error');
    });
}

function getInitials(customer) {
  if (!customer) return '';
  
  const firstInitial = customer.first_name ? customer.first_name.charAt(0).toUpperCase() : '';
  const lastInitial = customer.last_name ? customer.last_name.charAt(0).toUpperCase() : '';
  
  return firstInitial + lastInitial;
}
</script>