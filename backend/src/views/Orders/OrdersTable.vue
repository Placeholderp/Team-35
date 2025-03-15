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
              @change="getOrders(null)"
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
          <span class="text-sm text-gray-600">{{ ordersTotal }} total records</span>
        </div>
        
        <!-- Right side controls -->
        <div class="flex items-center space-x-3">
          <!-- Status filter dropdown -->
          <select
            v-model="statusFilter"
            @change="getOrders(null)"
            class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Statuses</option>
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          
          <!-- Search box -->
          <div class="relative flex-1 min-w-[240px]">
            <input
              v-model="search"
              @input="onSearchInputDelayed"
              class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full text-sm"
              placeholder="Search orders..."
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

    <!-- Orders Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <TableHeaderCell
              field="id"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortOrders('id')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Order ID
            </TableHeaderCell>
            <TableHeaderCell
              field="customer_name"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortOrders('customer_name')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Customer
            </TableHeaderCell>
            <TableHeaderCell
              field="status"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortOrders('status')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
            </TableHeaderCell>
            <TableHeaderCell
              field="total_price"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortOrders('total_price')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Total
            </TableHeaderCell>
            <TableHeaderCell
              field="created_at"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortOrders('created_at')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Date
            </TableHeaderCell>
            <TableHeaderCell
              class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </TableHeaderCell>
          </tr>
        </thead>

        <tbody v-if="loading || !ordersData.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="6" class="px-6 py-10 text-center">
              <Spinner v-if="loading" class="mx-auto" />
              <p v-else class="text-gray-500 text-sm">
                No orders found
              </p>
            </td>
          </tr>
        </tbody>
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr v-for="(order, index) of ordersData" :key="order.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              #{{ order.id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
              <div>{{ order.customer.first_name }} {{ order.customer.last_name }}</div>
              <div class="text-gray-400 text-xs">{{ order.customer.email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <OrderStatus :order="order" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ formatCurrency(order.total_price) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
              <button
                @click="$emit('clickView', order)"
                class="inline-flex items-center justify-center px-3 py-1.5 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-600 hover:text-white transition-colors text-xs font-medium"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!loading" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
      <div class="flex-1 flex justify-between sm:hidden">
        <button 
          @click="getOrders(prevPageUrl)"
          :disabled="!prevPageUrl"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="[!prevPageUrl ? 'opacity-50 cursor-not-allowed' : '']"
        >
          Previous
        </button>
        <button
          @click="getOrders(nextPageUrl)"
          :disabled="!nextPageUrl"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="[!nextPageUrl ? 'opacity-50 cursor-not-allowed' : '']"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ fromRecord }}</span> to <span class="font-medium">{{ toRecord }}</span> of <span class="font-medium">{{ ordersTotal }}</span> results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a
              v-for="(link, i) of pageLinks"
              :key="i"
              href="#"
              @click.prevent="getForPage($event, link)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                !link.url ? 'bg-gray-100 text-gray-700 cursor-not-allowed' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : '',
                i === 0 ? 'rounded-l-md' : '',
                i === pageLinks.length - 1 ? 'rounded-r-md' : '',
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
import { useStore } from 'vuex'
import  debounce  from 'lodash-es/debounce'

// Component imports
import Spinner from '../../components/core/Spinner.vue'
import TableHeaderCell from '../../components/core/Table/TableHeaderCell.vue'
import OrderStatus from './OrderStatus.vue'

// Constants
const PRODUCTS_PER_PAGE = 10 // Default value, adjust as needed

// Composition setup
const store = useStore()
const emit = defineEmits(['clickView'])

// Reactive state
const perPage = ref(PRODUCTS_PER_PAGE)
const search = ref('')
const statusFilter = ref('')
const sortField = ref('created_at')
const sortDirection = ref('desc')

// Computed properties for better data handling
const loading = computed(() => store.state.orders?.loading || false)
const ordersData = computed(() => store.state.orders?.data || [])
const ordersTotal = computed(() => store.state.orders?.total || 0)
const fromRecord = computed(() => store.state.orders?.from || 0)
const toRecord = computed(() => store.state.orders?.to || 0)
const prevPageUrl = computed(() => store.state.orders?.prev_page_url || null)
const nextPageUrl = computed(() => store.state.orders?.next_page_url || null)
const pageLinks = computed(() => store.state.orders?.links || [])

// Use debounce to prevent too many API calls while typing
const onSearchInputDelayed = debounce(() => {
  getOrders(null)
}, 500)

onMounted(() => {
  getOrders()
})

// Watch for changes in dropdown filters
watch(perPage, () => {
  getOrders(null)
})

watch(statusFilter, () => {
  getOrders(null)
})

function getForPage(ev, link) {
  if (!link.url || link.active) return
  getOrders(link.url)
}

function getOrders(url = null) {
  store.dispatch('getOrders', {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    status: statusFilter.value,
  })
}

function sortOrders(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  getOrders()
}

function formatDate(dateString) {
  if (!dateString) return ''
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}
</script>