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
          
          <!-- Advanced filters toggle button -->
          <button 
            @click="showAdvancedFilters = !showAdvancedFilters"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-4 w-4 mr-1 text-gray-500" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            {{ showAdvancedFilters ? 'Hide Filters' : 'Show Filters' }}
          </button>
          
          <!-- Export Button -->
          <button 
            @click="exportCurrentOrders"
            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </button>
        </div>
      </div>
    </div>
    
    <!-- Advanced Filters Section (Grid Layout) -->
    <div v-if="showAdvancedFilters" class="p-4 bg-gray-50 border-b border-gray-200">
      <h3 class="text-sm font-medium text-gray-700 mb-3">Advanced Filters</h3>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Status Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Order Status</label>
          <select 
            v-model="statusFilter"
            @change="getOrders(null)"
            class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="paid">Paid</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="refunded">Refunded</option>
          </select>
        </div>
        
        <!-- Date Range Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Date Range</label>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs text-gray-500 mb-1">From</label>
              <input
                v-model="dateFrom"
                type="date"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">To</label>
              <input
                v-model="dateTo"
                type="date"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
          </div>
        </div>
        
        <!-- Payment Method Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Payment Method</label>
          <select 
            v-model="paymentMethodFilter"
            @change="getOrders(null)"
            class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Payment Methods</option>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cash_on_delivery">Cash on Delivery</option>
          </select>
        </div>
        
        <!-- Price Range Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Price Range</label>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs text-gray-500 mb-1">Min ($)</label>
              <input
                v-model="minPrice"
                type="number"
                min="0"
                step="0.01"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">Max ($)</label>
              <input
                v-model="maxPrice"
                type="number"
                min="0"
                step="0.01"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
          </div>
        </div>
        
        <!-- Customer Type Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Customer Type</label>
          <select 
            v-model="customerTypeFilter"
            @change="getOrders(null)"
            class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Customers</option>
            <option value="new">New Customers</option>
            <option value="returning">Returning Customers</option>
          </select>
        </div>
        
        <!-- Shipping Method Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Shipping Method</label>
          <select 
            v-model="shippingMethodFilter"
            @change="getOrders(null)"
            class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Shipping Methods</option>
            <option value="standard">Standard Shipping</option>
            <option value="express">Express Shipping</option>
            <option value="free">Free Shipping</option>
            <option value="pickup">Local Pickup</option>
          </select>
        </div>
        
        <!-- Has Notes Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
          <select 
            v-model="hasNotesFilter"
            @change="getOrders(null)"
            class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">All Orders</option>
            <option value="has_notes">Has Notes</option>
            <option value="no_notes">No Notes</option>
          </select>
        </div>
        
        <!-- Items Count Filter -->
        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
          <label class="block text-xs font-medium text-gray-500 mb-1">Number of Items</label>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs text-gray-500 mb-1">Min</label>
              <input
                v-model="minItems"
                type="number"
                min="1"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">Max</label>
              <input
                v-model="maxItems"
                type="number"
                min="1"
                @change="getOrders(null)"
                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              />
            </div>
          </div>
        </div>
      </div>
      
      <!-- Filter Actions -->
      <div class="flex justify-end mt-4 space-x-2">
        <button 
          @click="clearAllFilters"
          class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Clear All
        </button>
        <button 
          @click="applyAllFilters"
          class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Apply Filters
        </button>
      </div>
    </div>
    
    <!-- Active Filters Display -->
    <div v-if="hasActiveFilters" class="p-3 bg-indigo-50 border-b border-indigo-200">
      <div class="flex flex-wrap items-center gap-2">
        <span class="text-xs font-medium text-indigo-700">Active Filters:</span>
        
        <span v-if="statusFilter" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          Status: {{ formatStatusLabel(statusFilter) }}
          <button @click="removeFilter('status')" class="ml-1 text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </span>
        
        <span v-if="dateFrom || dateTo" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          Date: {{ dateFrom || 'Any' }} to {{ dateTo || 'Any' }}
          <button @click="removeFilter('date')" class="ml-1 text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </span>
        
        <!-- Display other active filters similarly -->
        <span v-if="paymentMethodFilter" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          Payment: {{ formatPaymentMethod(paymentMethodFilter) }}
          <button @click="removeFilter('paymentMethod')" class="ml-1 text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </span>
        
        <span v-if="minPrice || maxPrice" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          Price: ${{ minPrice || '0' }} - ${{ maxPrice || '∞' }}
          <button @click="removeFilter('price')" class="ml-1 text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </span>
        
        <span v-if="search" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
          Search: "{{ search }}"
          <button @click="removeFilter('search')" class="ml-1 text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </span>
      </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div v-if="selectedOrders.length > 0" class="bg-gray-50 py-3 px-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <div class="text-sm text-gray-700">
          {{ selectedOrders.length }} orders selected
        </div>
        <div class="flex space-x-2">
          <select 
            v-model="bulkAction" 
            class="appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">Bulk Actions</option>
            <option value="status">Update Status</option>
            <option value="export">Export</option>
            <option value="print">Print</option>
          </select>
          
          <select 
            v-if="bulkAction === 'status'"
            v-model="bulkStatus"
            class="appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
          >
            <option value="">Select Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="paid">Paid</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="refunded">Refunded</option>
          </select>
          
          <button 
            @click="applyBulkAction"
            class="bg-indigo-600 text-white py-1 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm"
          >
            Apply
          </button>
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="w-10 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <input 
                type="checkbox" 
                @change="toggleSelectAll" 
                :checked="isAllSelected"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
            </th>
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
            <td class="px-6 py-4 whitespace-nowrap">
              <input 
                type="checkbox" 
                :value="order.id" 
                v-model="selectedOrders"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ order.id ? '#' + order.id : 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
              <div>{{ getCustomerName(order) }}</div>
              <div class="text-gray-400 text-xs">{{ getCustomerEmail(order) }}</div>
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
                @click="handleViewClick(order)"
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
import debounce from 'lodash-es/debounce'
import { exportOrdersToCsv } from '../../utils/exportUtils'

// Component imports
import Spinner from '../../components/core/Spinner.vue'
import TableHeaderCell from '../../components/core/Table/TableHeaderCell.vue'
import OrderStatus from './OrderStatus.vue'

// Constants
const PRODUCTS_PER_PAGE = 10 // Default value, adjust as needed

// Composition setup
const store = useStore()
const emit = defineEmits(['click-view'])  

// Reactive state
const perPage = ref(PRODUCTS_PER_PAGE)
const search = ref('')
const statusFilter = ref('')
const dateFrom = ref('') // Added for date range filter
const dateTo = ref('') // Added for date range filter
const sortField = ref('created_at')
const sortDirection = ref('desc')
const selectedOrders = ref([])
const bulkAction = ref('')
const bulkStatus = ref('')

// New advanced filter states
const showAdvancedFilters = ref(false)
const paymentMethodFilter = ref('')
const minPrice = ref('')
const maxPrice = ref('')
const customerTypeFilter = ref('')
const shippingMethodFilter = ref('')
const hasNotesFilter = ref('')
const minItems = ref('')
const maxItems = ref('')

// Computed properties for better data handling
const loading = computed(() => store.state.orders?.loading || false)
const ordersData = computed(() => store.state.orders?.data || [])
const ordersTotal = computed(() => store.state.orders?.total || 0)
const fromRecord = computed(() => store.state.orders?.from || 0)
const toRecord = computed(() => store.state.orders?.to || 0)
const prevPageUrl = computed(() => store.state.orders?.prev_page_url || null)
const nextPageUrl = computed(() => store.state.orders?.next_page_url || null)
const pageLinks = computed(() => store.state.orders?.links || [])

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return Boolean(
    statusFilter.value || 
    dateFrom.value || 
    dateTo.value || 
    search.value || 
    paymentMethodFilter.value || 
    minPrice.value || 
    maxPrice.value || 
    customerTypeFilter.value || 
    shippingMethodFilter.value ||
    hasNotesFilter.value ||
    minItems.value ||
    maxItems.value
  );
})

// Bulk actions methods
const isAllSelected = computed(() => {
  return selectedOrders.value.length === ordersData.value.length && ordersData.value.length > 0
})

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

// Format payment method for display
function formatPaymentMethod(method) {
  const labels = {
    'credit_card': 'Credit Card',
    'paypal': 'PayPal',
    'bank_transfer': 'Bank Transfer',
    'cash_on_delivery': 'Cash on Delivery'
  };
  
  return labels[method] || method;
}

// Format status label
function formatStatusLabel(status) {
  return status.charAt(0).toUpperCase() + status.slice(1);
}

// Remove specific filter
function removeFilter(filterType) {
  switch(filterType) {
    case 'status':
      statusFilter.value = '';
      break;
    case 'date':
      dateFrom.value = '';
      dateTo.value = '';
      break;
    case 'paymentMethod':
      paymentMethodFilter.value = '';
      break;
    case 'price':
      minPrice.value = '';
      maxPrice.value = '';
      break;
    case 'search':
      search.value = '';
      break;
    // Add other filter types as needed
  }
  
  // Apply filters after removal
  getOrders(null);
}

// Clear all filters
function clearAllFilters() {
  statusFilter.value = '';
  dateFrom.value = '';
  dateTo.value = '';
  search.value = '';
  paymentMethodFilter.value = '';
  minPrice.value = '';
  maxPrice.value = '';
  customerTypeFilter.value = '';
  shippingMethodFilter.value = '';
  hasNotesFilter.value = '';
  minItems.value = '';
  maxItems.value = '';
  
  // Apply empty filters
  getOrders(null);
}

// Apply all filters
function applyAllFilters() {
  getOrders(null);
}

// Added watchers for date range filters
watch(dateFrom, () => {
  getOrders(null)
})

watch(dateTo, () => {
  getOrders(null)
})

// Reset selected orders when the page changes
watch([ordersData], () => {
  selectedOrders.value = []
  bulkAction.value = ''
  bulkStatus.value = ''
})

/**
 * Safely get customer name with fallbacks
 * @param {Object} order The order object
 * @returns {String} Formatted customer name
 */
 function getCustomerName(order) {
  // First check if customer exists
  if (!order || !order.customer) {
    return 'Unknown Customer';
  }
  
  // Check if first_name exists
  const firstName = order.customer.first_name || '';
  const lastName = order.customer.last_name || '';
  
  if (firstName || lastName) {
    return `${firstName} ${lastName}`.trim();
  }
  
  // Last resort fallback
  return `Customer #${order.customer.id || 'Unknown'}`;
}

/**
 * Safely get customer email with fallback
 * @param {Object} order The order object
 * @returns {String} Customer email or empty string
 */
function getCustomerEmail(order) {
  if (!order || !order.customer) {
    return '';
  }
  
  return order.customer.email || '';
}

function getForPage(ev, link) {
  if (!link.url || link.active) return
  getOrders(link.url)
}

function getOrders() {
  // Make sure status is a valid string value or empty
  const validStatus = statusFilter.value && typeof statusFilter.value === 'string' 
    && !statusFilter.value.includes(':') ? statusFilter.value : '';
  
  // Sanitize sort parameters to prevent 500 errors
  const validSortField = sortField.value;
  const validSortDirection = sortDirection.value === 'asc' ? 'asc' : 'desc';
  
  // Add error handling in case of API failures
  store.dispatch('getOrders', {
    search: search.value,
    per_page: perPage.value,
    sort_field: validSortField,
    sort_direction: validSortDirection,
    status: validStatus,
    date_from: dateFrom.value,
    date_to: dateTo.value,
    // Add new filter parameters
    payment_method: paymentMethodFilter.value,
    min_price: minPrice.value,
    max_price: maxPrice.value,
    customer_type: customerTypeFilter.value,
    shipping_method: shippingMethodFilter.value,
    has_notes: hasNotesFilter.value,
    min_items: minItems.value,
    max_items: maxItems.value,
  })
  .catch(error => {
    console.error("Error fetching orders:", error);
    store.commit('showToast', {
      message: 'Failed to load orders. Please try again.',
      type: 'error'
    });
  });
}

function sortOrders(field) {
  // If trying to sort by 'id', actually sort by 'user_id'
  const actualField = field === 'id' ? 'user_id' : field;
  
  if (actualField === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
  } else {
    sortField.value = actualField;
    sortDirection.value = 'asc';
  }
  getOrders();
}
/**
 * Export current orders to CSV
 */
 function exportCurrentOrders() {
  if (!ordersData.value.length) {
    store.commit('showToast', {
      message: 'No orders to export',
      type: 'warning'
    });
    return;
  }
  
  // Use the existing utility to export orders
  exportOrdersToCsv(ordersData.value, 'orders_export.csv');
  
  store.commit('showToast', {
    message: 'Orders exported successfully',
    type: 'success'
  });
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

function toggleSelectAll(event) {
  if (event.target.checked) {
    // Select all orders
    selectedOrders.value = ordersData.value.map(order => order.id)
  } else {
    // Deselect all orders
    selectedOrders.value = []
  }
}

// Improved handler for view button clicks
function handleViewClick(order) {
  console.log("Full order object:", order);
  
  // If order lacks an ID, generate a temporary one
  if (!order.id) {
    console.log("Order missing ID, creating temporary ID");
    order = {
      ...order,
      id: Date.now() // Use timestamp as temporary ID
    };
  }
  
  emit('click-view', order);
}

async function applyBulkAction() {
  if (!selectedOrders.value.length) {
    store.commit('showToast', {
      message: 'No orders selected',
      type: 'error'
    })
    return
  }
  try {
    if (bulkAction.value === 'status' && bulkStatus.value) {
      // Update status for all selected orders
      await store.dispatch('updateOrdersStatus', {
        orderIds: selectedOrders.value,
        status: bulkStatus.value
      })
      
      store.commit('showToast', {
        message: `Status updated for ${selectedOrders.value.length} orders`,
        type: 'success'
      })
      
      // Refresh orders list
      getOrders()
      
    } else if (bulkAction.value === 'export') {
      // Export selected orders
      await store.dispatch('exportOrders', selectedOrders.value)
      
      store.commit('showToast', {
        message: 'Orders exported successfully',
        type: 'success'
      })
      
    } else if (bulkAction.value === 'print') {
      // Prepare and print selected orders
      await store.dispatch('printOrders', selectedOrders.value)
    }
    
    // Reset selections after action
    selectedOrders.value = []
    bulkAction.value = ''
    bulkStatus.value = ''
    
  } catch (error) {
    console.error('Error applying bulk action:', error)
    store.commit('showToast', {
      message: 'Failed to apply bulk action',
      type: 'error'
    })
  }
}
</script>