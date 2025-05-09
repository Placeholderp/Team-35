<template>
  <!-- Main container with softer background and increased padding for breathing room -->
  <div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Enhanced Header with quick actions and better date filter -->
      <div
        class="mb-6 p-6 bg-white rounded-xl shadow-sm
               flex flex-col sm:flex-row sm:items-center sm:justify-between
               space-y-4 sm:space-y-0"
      >
        <div class="flex items-center">
          <div class="w-1 h-8 bg-indigo-600 rounded mr-3"></div>
          <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
              Gym Products Dashboard
            </h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, admin! Here's how your fitness store is performing today.</p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
          <div class="bg-gray-50 p-2 rounded-lg flex items-center space-x-2">
            <CalendarIcon class="h-5 w-5 text-gray-400" />
            <CustomInput
              type="select"
              v-model="chosenDate"
              @change="onDatePickerChange"
              :select-options="dateOptions"
              class="border-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <button @click="refreshData" class="flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
            <RefreshIcon class="h-4 w-4 mr-1" />
            <span class="text-sm font-medium">Refresh</span>
          </button>
        </div>
      </div>

      <!-- Added summary banner with key insight -->
      <div class="mb-6 p-4 bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-xl shadow-sm text-white flex items-center justify-between">
        <div class="flex items-center">
          <LightBulbIcon class="w-8 h-8 mr-3" />
          <div>
            <h3 class="font-semibold text-lg">Performance Insight</h3>
            <p class="text-indigo-100">{{ performanceInsight }}</p>
          </div>
        </div>
        <div>
          <button class="px-3 py-1.5 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-sm font-medium transition-colors">
            View Report
          </button>
        </div>
      </div>

      <!-- Enhanced Metrics Cards with better visual contrast and interaction -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <!-- Active Customers -->
        <div
          class="bg-white rounded-xl shadow-sm p-6 
                 transform transition duration-200 hover:-translate-y-1 hover:shadow-md
                 overflow-hidden animate-fade-in-down relative"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <div class="p-3 rounded-lg bg-indigo-50">
                <UserIcon class="w-6 h-6 text-indigo-600" />
              </div>
            </div>
            <div class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">
              Fitness Enthusiasts
            </div>
          </div>
          
          <template v-if="!loading.customersCount">
            <span class="text-3xl font-bold text-gray-800 block mb-1">
              {{ customersCount }}
            </span>
            <div class="flex items-center text-sm text-gray-500">
              <ArrowUpIcon class="w-4 h-4 text-green-500 mr-1" />
              <span>12% from last period</span>
            </div>
            
            <div class="absolute bottom-0 right-0 h-10 w-24 opacity-25">
              <MiniLineChart v-if="customerTrendData.length > 0" :data="customerTrendData" color="#6366F1" />
            </div>
          </template>
          <Spinner v-else text="" />
        </div>

        <!-- Active Products -->
        <div
          class="bg-white rounded-xl shadow-sm p-6
                 transform transition duration-200 hover:-translate-y-1 hover:shadow-md
                 overflow-hidden animate-fade-in-down animate-delay-100ms relative"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <div class="p-3 rounded-lg bg-green-50">
                <GiftIcon class="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
              Gym Equipment
            </div>
          </div>
          
          <template v-if="!loading.productsCount">
            <span class="text-3xl font-bold text-gray-800 block mb-1">
              {{ productsCount }}
            </span>
            <div class="flex items-center text-sm text-gray-500">
              <ArrowUpIcon class="w-4 h-4 text-green-500 mr-1" />
              <span>4% from last period</span>
            </div>
            
            <div class="absolute bottom-0 right-0 h-10 w-24 opacity-25">
              <MiniLineChart v-if="productTrendData.length > 0" :data="productTrendData" color="#10B981" />
            </div>
          </template>
          <Spinner v-else text="" />
        </div>

        <!-- Paid Orders -->
        <div
          class="bg-white rounded-xl shadow-sm p-6
                 transform transition duration-200 hover:-translate-y-1 hover:shadow-md
                 overflow-hidden animate-fade-in-down animate-delay-200ms relative"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <div class="p-3 rounded-lg bg-yellow-50">
                <ShoppingCartIcon class="w-6 h-6 text-yellow-600" />
              </div>
            </div>
            <div class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">
              Sales
            </div>
          </div>
          
          <template v-if="!loading.paidOrders">
            <span class="text-3xl font-bold text-gray-800 block mb-1">
              {{ paidOrders }}
            </span>
            <div class="flex items-center text-sm text-gray-500">
              <ArrowUpIcon class="w-4 h-4 text-green-500 mr-1" />
              <span>8% from last period</span>
            </div>
            
            <div class="absolute bottom-0 right-0 h-10 w-24 opacity-25">
              <MiniLineChart v-if="orderTrendData.length > 0" :data="orderTrendData" color="#FBBF24" />
            </div>
          </template>
          <Spinner v-else text="" />
        </div>

        <!-- Total Income -->
        <div
          class="bg-white rounded-xl shadow-sm p-6
                 transform transition duration-200 hover:-translate-y-1 hover:shadow-md
                 overflow-hidden animate-fade-in-down animate-delay-300ms relative"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex items-center">
              <div class="p-3 rounded-lg bg-red-50">
                <CurrencyDollarIcon class="w-6 h-6 text-red-600" />
              </div>
            </div>
            <div class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
              Revenue
            </div>
          </div>
          
          <template v-if="!loading.totalIncome">
            <span class="text-3xl font-bold text-gray-800 block mb-1">
              {{ totalIncome }}
            </span>
            <div class="flex items-center text-sm text-gray-500">
              <ArrowUpIcon class="w-4 h-4 text-green-500 mr-1" />
              <span>15% from last period</span>
            </div>
            
            <div class="absolute bottom-0 right-0 h-10 w-24 opacity-25">
              <MiniLineChart v-if="revenueTrendData.length > 0" :data="revenueTrendData" color="#F87171" />
            </div>
          </template>
          <Spinner v-else text="" />
        </div>
      </div>

      <!-- Lower Section: Latest Orders, Chart, Latest Customers with enhanced styling -->
      <div
        class="grid grid-rows-1 md:grid-rows-2 md:grid-flow-col
               grid-cols-1 md:grid-cols-3 gap-5"
      >
        <!-- Latest Orders with improved list styling -->
        <div
          class="col-span-1 md:col-span-2 row-span-1 md:row-span-2
                 bg-white rounded-xl shadow-sm p-6 latest-orders-section"
        >
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center">
              <div class="w-1 h-6 bg-indigo-600 rounded mr-3"></div>
              <h2 class="text-lg font-bold text-gray-800">
                Latest Fitness Orders
              </h2>
            </div>
            <div class="flex items-center space-x-2">
              <div class="relative">
                <input 
                  type="text" 
                  v-model="orderSearchQuery" 
                  placeholder="Search orders..."
                  class="pl-8 pr-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />
                <SearchIcon class="h-4 w-4 text-gray-400 absolute left-2.5 top-2" />
              </div>
              <router-link :to="{ name: 'app.orders' }" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View All
              </router-link>
            </div>
          </div>
          
          <template v-if="!loading.latestOrders && filteredOrders.length">
            <div class="space-y-3">
              <div
                v-for="o in filteredOrders"
                :key="o.id"
                class="p-4 hover:bg-gray-50 border border-gray-100 rounded-lg last:border-b transition-colors group"
              >
                <div class="flex justify-between items-center mb-2">
                  <div class="flex items-center">
                    <router-link
                      :to="{ name: 'app.orders.view', params: { id: o.id } }"
                      class="text-indigo-700 font-semibold flex items-center group-hover:text-indigo-800"
                    >
                      <span class="mr-2">Order #{{ o.id }}</span>
                      <span class="px-2 py-1 text-xs rounded-full bg-indigo-50 text-indigo-700 group-hover:bg-indigo-100">
                        {{ o.items }} items
                      </span>
                    </router-link>
                    <!-- Highlight if order is in selected category -->
                    <span 
                      v-if="selectedRevenueCategory && filteredOrdersByCategory.some(order => order.id === o.id)"
                      class="ml-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full"
                    >
                      {{ selectedRevenueCategory }}
                    </span>
                  </div>
                  <span class="text-sm font-medium text-gray-500">
                    {{ o.created_at }}
                  </span>
                </div>
                
                <div class="flex justify-between items-center">
                  <div class="flex items-center">
                    <div
                      class="w-8 h-8 bg-gray-200 flex items-center justify-center
                            rounded-full mr-2 group-hover:bg-indigo-50"
                    >
                      <UserIcon class="w-4 h-4 text-gray-600 group-hover:text-indigo-600" />
                    </div>
                    <span class="text-sm font-medium">
                      {{ o.first_name }} {{ o.last_name }}
                    </span>
                  </div>
                  <div class="flex items-center">
                    <OrderStatus :order="o" :status="o.status || 'paid'" class="mr-2" />
                    <span class="font-semibold text-gray-800">
                      {{ $filters.currencyUSD(o.total_price) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <div v-else-if="!loading.latestOrders && !filteredOrders.length" class="py-8 text-center text-gray-500">
            No recent orders found
          </div>
          <Spinner v-else text="Loading orders..." />
        </div>

        <!-- Revenue Breakdown -->
        <div
          class="bg-white rounded-xl shadow-sm p-6 flex flex-col"
        >
          <div class="flex items-center mb-5 justify-between">
            <div class="flex items-center">
              <div class="p-2 rounded-full bg-red-50 mr-3">
                <CurrencyDollarIcon class="h-5 w-5 text-red-600" />
              </div>
              <h2 class="text-lg font-bold text-gray-800">
                Revenue Breakdown
              </h2>
            </div>
            <div class="flex space-x-2">
              <button 
                @click="refreshRevenueData" 
                class="p-1.5 rounded text-red-600 hover:bg-red-50" 
                title="Refresh revenue data"
                :disabled="loading.revenueData"
              >
                <RefreshIcon class="h-4 w-4" :class="{'animate-spin': loading.revenueData}" />
              </button>
              <button 
                @click="toggleChartView"
                class="p-1.5 bg-gray-100 rounded text-gray-500 hover:bg-gray-200 hover:text-gray-700" 
                title="Toggle chart view"
              >
                <ChartBarIcon v-if="chartView === 'doughnut'" class="h-4 w-4" />
                <ChartPieIcon v-else class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <template v-if="!loading.revenueData && revenueChartData.labels && revenueChartData.labels.length">
            <div class="flex-1 flex items-center justify-center">
              <!-- Added click handler to each segment to filter orders -->
              <DoughnutChart
                v-if="chartView === 'doughnut'"
                :width="165"
                :height="200"
                :data="revenueChartData"
                @segment-click="onRevenueSegmentClick"
              />
              <BarChart
                v-else
                :data="revenueChartData"
                :height="200"
                @segment-click="onRevenueSegmentClick"
              />
            </div>
            
            <!-- Connection to orders - added category filter indicator -->
            <div v-if="selectedRevenueCategory" class="mt-4 py-2 px-3 bg-indigo-50 rounded-lg flex justify-between items-center text-sm">
              <div class="flex items-center">
                <span class="font-medium text-indigo-700">
                  Filtering: {{ selectedRevenueCategory }}
                </span>
                <span class="ml-2 text-indigo-600">
                  ({{ filteredOrdersByCategory.length }} orders)
                </span>
              </div>
              <button 
                @click="clearRevenueCategoryFilter" 
                class="text-indigo-600 hover:text-indigo-800"
                title="Clear filter"
              >
                <XIcon class="h-4 w-4" />
              </button>
            </div>
            
            <!-- Top performing products in selected category -->
            <div v-if="selectedRevenueCategory && topProductsInCategory.length" class="mt-3">
              <h4 class="text-sm font-medium text-gray-700 mb-2">Top Products in {{ selectedRevenueCategory }}</h4>
              <div class="space-y-2">
                <div 
                  v-for="product in topProductsInCategory" 
                  :key="product.id"
                  class="flex justify-between items-center text-sm py-1.5 px-2 hover:bg-gray-50 rounded"
                >
                  <span class="truncate">{{ product.name }}</span>
                  <span class="font-medium text-gray-700">{{ $filters.currencyUSD(product.revenue) }}</span>
                </div>
              </div>
            </div>
          </template>
          <div v-else-if="!loading.revenueData && (!revenueChartData.labels || !revenueChartData.labels.length)" class="py-8 text-center text-gray-500">
            No revenue data available
          </div>
          <Spinner v-else text="Loading revenue data..." />
        </div>

        <!-- Latest Customers with improved styling -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <div class="flex items-center">
              <div class="w-1 h-6 bg-yellow-600 rounded mr-3"></div>
              <h2 class="text-lg font-bold text-gray-800">
                New Customers Added
              </h2>
            </div>
            <div class="flex items-center space-x-2">
              <div class="relative">
                <input 
                  type="text" 
                  v-model="customerSearchQuery" 
                  placeholder="Search customers..."
                  class="pl-8 pr-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500"
                />
                <SearchIcon class="h-4 w-4 text-gray-400 absolute left-2.5 top-2" />
              </div>
              <router-link :to="{ name: 'app.customers' }" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View All
              </router-link>
            </div>
          </div>
          
          <template v-if="!loading.latestCustomers && filteredCustomers.length">
            <div class="space-y-3">
              <router-link
                v-for="c in filteredCustomers"
                :key="c.user_id || c.id"
                :to="{ name: 'app.customers.view', params: { id: c.user_id || c.id } }"
                class="flex items-center hover:bg-gray-50
                      p-3 rounded-lg transition-colors border border-gray-100 group"
              >
                <div
                  class="w-10 h-10 flex items-center justify-center
                        rounded-full mr-3 group-hover:bg-indigo-100 transition-colors"
                  :class="getCustomerAvatarColor(c.user_id || c.id)"
                >
                  <span class="text-md font-semibold text-white">{{ getCustomerInitials(c) }}</span>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-800">
                    {{ c.first_name }} {{ c.last_name }}
                  </h3>
                  <div class="flex items-center">
                    <p class="text-sm text-gray-500 mr-2">
                      {{ c.email }}
                    </p>
                    <span v-if="c.orders_count" class="text-xs bg-green-50 text-green-700 px-1.5 py-0.5 rounded">
                      {{ c.orders_count }} orders
                    </span>
                  </div>
                </div>
              </router-link>
            </div>
          </template>
          <div v-else-if="!loading.latestCustomers && !filteredCustomers.length" class="py-8 text-center text-gray-500">
            No recent customers found
          </div>
          <Spinner v-else text="Loading customers..." />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, onActivated, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import axiosClient from '../axios.js'

// Heroicons - added more icons for enhanced features
import {
  UserIcon,
  ShoppingCartIcon,
  CurrencyDollarIcon,
  GiftIcon,
  ArrowUpIcon,
  CalendarIcon,
  RefreshIcon,
  SearchIcon,
  ChartBarIcon,
  ChartPieIcon,
  LightBulbIcon,
  XIcon
} from '@heroicons/vue/outline'

// Local components
import Spinner from '../components/core/Spinner.vue'
import CustomInput from '../components/core/CustomInput.vue'
import DoughnutChart from '../components/core/Charts/Doughnut.vue'
import BarChart from '../components/core/Charts/Bar.vue'
import MiniLineChart from '../components/core/Charts/MiniLine.vue'
import OrderStatus from '../views/Orders/OrderStatus.vue'

// Access store and route
const store = useStore()
const route = useRoute()

// Date options
const dateOptions = computed(() => {
  const storeOptions = store.state.dateOptions;
  
  // If store options exist and are in the correct format, use them
  if (storeOptions && Array.isArray(storeOptions) && storeOptions.length > 0) {
    // Make sure the options have the correct format (key and text properties)
    if (storeOptions[0].key !== undefined && storeOptions[0].text !== undefined) {
      return storeOptions;
    }
  }
  
  // Otherwise, return default options in the format expected by CustomInput
  return [
    { key: 'all', text: 'All Time' },
    { key: '30d', text: 'Last 30 Days' },
    { key: '60d', text: 'Last 60 Days' },
    { key: '90d', text: 'Last Quarter' },
    { key: '12m', text: 'Last Year' }
  ];
})
const chosenDate = ref('all')

// Search and filtering
const orderSearchQuery = ref('')
const customerSearchQuery = ref('')

// Chart view toggle
const chartView = ref('doughnut') // doughnut or bar

// Revenue category filter
const selectedRevenueCategory = ref(null)
const rawRevenueData = ref([]) // Store the original data for filtering

// Loading states
const loading = ref({
  customersCount: true,
  productsCount: true,
  paidOrders: true,
  totalIncome: true,
  revenueData: true,
  latestCustomers: true,
  latestOrders: true
})

// Dashboard data
const customersCount = ref(0)
const productsCount = ref(0)
const paidOrders = ref(0)
const totalIncome = ref(0)
const latestCustomers = ref([])
const latestOrders = ref([])

// Revenue chart data
const revenueChartData = ref({
  labels: [],
  datasets: [
    {
      backgroundColor: [
        '#34D399', // Green (primary for revenue)
        '#818CF8', // Indigo
        '#FBBF24', // Yellow
        '#F87171', // Red
        '#60A5FA', // Blue
        '#FDBA74'  // Orange
      ],
      borderWidth: 0,
      data: []
    }
  ]
})

// Load trend data from API
const customerTrendData = ref([])
const productTrendData = ref([])
const orderTrendData = ref([])
const revenueTrendData = ref([])

// Performance insight from API
const performanceInsight = ref("")

// Filtered results for search functionality
const filteredOrders = computed(() => {
  // First filter by search query
  let result = latestOrders.value
  const query = orderSearchQuery.value.toLowerCase()
  
  if (query) {
    result = result.filter(order => {
      return (
        order.id.toString().includes(query) ||
        `${order.first_name} ${order.last_name}`.toLowerCase().includes(query) ||
        order.total_price.toString().includes(query)
      )
    })
  }
  
  // Then filter by selected revenue category if any
  if (selectedRevenueCategory.value) {
    const orderIds = new Set(filteredOrdersByCategory.value.map(order => order.id))
    result = result.filter(order => orderIds.has(order.id))
  }
  
  return result
})

const filteredCustomers = computed(() => {
  if (!customerSearchQuery.value) return latestCustomers.value
  
  const query = customerSearchQuery.value.toLowerCase()
  return latestCustomers.value.filter(customer => {
    return (
      `${customer.first_name} ${customer.last_name}`.toLowerCase().includes(query) ||
      (customer.email && customer.email.toLowerCase().includes(query))
    )
  })
})

// Orders filtered by selected revenue category
const filteredOrdersByCategory = computed(() => {
  if (!selectedRevenueCategory.value || !rawRevenueData.value.ordersByCategory) {
    return []
  }
  
  return rawRevenueData.value.ordersByCategory[selectedRevenueCategory.value] || []
})

// Top products in the selected category
const topProductsInCategory = computed(() => {
  if (!selectedRevenueCategory.value || !rawRevenueData.value.products) {
    return []
  }
  
  return rawRevenueData.value.products
    .filter(product => product.category === selectedRevenueCategory.value)
    .sort((a, b) => b.revenue - a.revenue)
    .slice(0, 3) // Show top 3 products
})

// Process revenue data for chart display with enhanced info for interaction
function processRevenueData(apiData) {
  const chartData = {
    labels: [],
    datasets: [
      {
        backgroundColor: [
          '#34D399', // Green (primary for revenue)
          '#818CF8', // Indigo
          '#FBBF24', // Yellow
          '#F87171', // Red
          '#60A5FA', // Blue
          '#FDBA74'  // Orange
        ],
        borderWidth: 0,
        data: [],
        categoryNames: [] // Store original category names for filtering
      }
    ]
  }
  
  // Check if data is directly an array or has a nested data property
  const categories = Array.isArray(apiData) ? apiData : (apiData?.data || [])
  
  if (categories && categories.length) {
    console.log('Processing revenue breakdown:', categories)
    
    // Sort categories by revenue amount for better display
    const sortedCategories = [...categories].sort((a, b) => {
      // Get revenue amount safely with fallbacks
      const amountA = a.revenue_amount || a.revenue || a.amount || a.total || 0
      const amountB = b.revenue_amount || b.revenue || b.amount || b.total || 0
      return amountB - amountA
    }).slice(0, 6) // Limit to top 6 for chart readability
    
    sortedCategories.forEach(category => {
      // Handle different possible property names
      const name = category.category_name || category.name || category.title || 'Other'
      const amount = category.revenue_amount || category.revenue || category.amount || category.total || 0
      
      // Format the label to include the amount
      const formattedAmount = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount)
      
      // Only add categories with an amount > 0
      if (amount > 0) {
        chartData.labels.push(`${name} (${formattedAmount})`)
        chartData.datasets[0].data.push(amount)
        chartData.datasets[0].categoryNames.push(name) // Store original name
      }
    })
  }
  
  return chartData
}

// Extract revenue breakdown from orders with enhanced detail
function extractRevenueFromOrders(orders) {
  const categoryRevenue = {}
  const productRevenue = {}
  const ordersByCategory = {}
  
  orders.forEach(order => {
    if (order.items && Array.isArray(order.items)) {
      // Track which categories are in each order
      const categoriesInOrder = new Set()
      
      order.items.forEach(item => {
        if (item.product) {
          const category = item.product.category?.name || 'Uncategorized'
          const productId = item.product.id
          const productName = item.product.name || `Product ${productId}`
          const revenue = (item.price || 0) * (item.quantity || 1)
          
          // Add to category revenue
          if (!categoryRevenue[category]) {
            categoryRevenue[category] = 0
          }
          categoryRevenue[category] += revenue
          
          // Add to product revenue
          const productKey = `${productId}-${category}`
          if (!productRevenue[productKey]) {
            productRevenue[productKey] = {
              id: productId,
              name: productName,
              category,
              revenue: 0
            }
          }
          productRevenue[productKey].revenue += revenue
          
          // Mark this category as included in this order
          categoriesInOrder.add(category)
        }
      })
      
      // Add this order to each category it contains products from
      categoriesInOrder.forEach(category => {
        if (!ordersByCategory[category]) {
          ordersByCategory[category] = []
        }
        ordersByCategory[category].push(order)
      })
    }
  })
  
  // Convert category revenue to array and sort by revenue
  const categorySummary = Object.entries(categoryRevenue)
    .map(([category, amount]) => ({
      category_name: category,
      revenue_amount: amount,
      orders: ordersByCategory[category] || []
    }))
    .sort((a, b) => b.revenue_amount - a.revenue_amount)
    .slice(0, 6) // Get top 6 categories
  
  // Convert product revenue to array and sort by revenue
  const productSummary = Object.values(productRevenue)
    .sort((a, b) => b.revenue - a.revenue)
  
  // Store raw data for filtering
  rawRevenueData.value = {
    categories: categorySummary,
    products: productSummary,
    ordersByCategory: ordersByCategory
  }
  
  return categorySummary
}

// Handle API errors
function handleApiError(endpoint, error) {
  console.error(`Error fetching ${endpoint}:`, error)
  
  // Set corresponding loading state to false to prevent infinite loading
  if (endpoint.includes('customers-count')) loading.value.customersCount = false
  else if (endpoint.includes('products-count')) loading.value.productsCount = false
  else if (endpoint.includes('orders-count')) loading.value.paidOrders = false
  else if (endpoint.includes('income-amount')) loading.value.totalIncome = false
  else if (endpoint.includes('revenue-breakdown')) loading.value.revenueData = false
  else if (endpoint.includes('latest-customers')) loading.value.latestCustomers = false
  else if (endpoint.includes('latest-orders')) loading.value.latestOrders = false
}

// Fetch revenue data
function fetchRevenueData() {
  loading.value.revenueData = true
  const d = chosenDate.value
  
  axiosClient
    .get('/dashboard/revenue-breakdown', { params: { d } })
    .then(({ data }) => {
      console.log('Revenue API response:', data)
      revenueChartData.value = processRevenueData(data)
      loading.value.revenueData = false
    })
    .catch(error => {
      console.error('Failed to load revenue breakdown, trying fallback:', error)
      
      // Try fallback to orders API to extract revenue breakdown
      axiosClient
        .get('/orders', { 
          params: { 
            per_page: 50,
            include: 'items.product',
            sort_field: 'created_at',
            sort_direction: 'desc'
          } 
        })
        .then(response => {
          const orders = response.data?.data || []
          console.log('Fallback: Processing orders to calculate revenue breakdown')
          
          if (orders.length > 0) {
            const revenueData = extractRevenueFromOrders(orders)
            revenueChartData.value = processRevenueData(revenueData)
          } else {
            revenueChartData.value = { labels: [], datasets: [{ backgroundColor: [], data: [] }] }
          }
          loading.value.revenueData = false
        })
        .catch(fallbackError => {
          console.error('Fallback to orders also failed:', fallbackError)
          revenueChartData.value = { labels: [], datasets: [{ backgroundColor: [], data: [] }] }
          loading.value.revenueData = false
        })
    })
}

// Fetch dashboard data
function updateDashboard() {
  console.log('Dashboard: Updating data...')
  const d = chosenDate.value

  // Reset loading flags
  for (const key in loading.value) {
    loading.value[key] = true
  }

  // Reset data to prevent stale data display
  latestOrders.value = []
  latestCustomers.value = []
  
  // Fetch customers count
  axiosClient
    .get('/dashboard/customers-count', { params: { d } })
    .then(({ data }) => {
      customersCount.value = data
      loading.value.customersCount = false
    })
    .catch(error => handleApiError('customers-count', error))

  // Fetch products count
  axiosClient
    .get('/dashboard/products-count', { params: { d } })
    .then(({ data }) => {
      productsCount.value = data
      loading.value.productsCount = false
    })
    .catch(error => handleApiError('products-count', error))

  // Fetch orders count
  axiosClient
    .get('/dashboard/orders-count', { params: { d } })
    .then(({ data }) => {
      paidOrders.value = data
      loading.value.paidOrders = false
    })
    .catch(error => handleApiError('orders-count', error))

  // Fetch total income
  axiosClient
    .get('/dashboard/income-amount', { params: { d } })
    .then(({ data }) => {
      totalIncome.value = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0
      }).format(data)
      loading.value.totalIncome = false
    })
    .catch(error => handleApiError('income-amount', error))

  // Fetch revenue breakdown data
  fetchRevenueData()
  
  // Fetch latest customers
  axiosClient
    .get('/dashboard/latest-customers', { params: { d } })
    .then(({ data }) => {
      latestCustomers.value = data || []
      loading.value.latestCustomers = false
    })
    .catch(error => {
      console.error('Failed to load latest customers:', error)
      // Try fallback to customers API if dashboard endpoint fails
      axiosClient
        .get('/customers', { params: { per_page: 4, sort_field: 'created_at', sort_direction: 'desc' } })
        .then(response => {
          if (response.data && response.data.data) {
            latestCustomers.value = response.data.data
          } else {
            latestCustomers.value = []
          }
          loading.value.latestCustomers = false
        })
        .catch(fallbackError => {
          console.error('Both customer endpoints failed:', fallbackError)
          latestCustomers.value = []
          loading.value.latestCustomers = false
        })
    })

  // Fetch latest orders
  axiosClient
    .get('/dashboard/latest-orders', { params: { d } })
    .then(({ data: orders }) => {
      latestOrders.value = orders?.data || []
      loading.value.latestOrders = false
    })
    .catch(error => handleApiError('latest-orders', error))
    
  // Load trend data and performance insight
  axiosClient
    .get('/dashboard/trend-data', { params: { d } })
    .then(({ data }) => {
      if (data.customers) customerTrendData.value = data.customers
      if (data.products) productTrendData.value = data.products
      if (data.orders) orderTrendData.value = data.orders
      if (data.revenue) revenueTrendData.value = data.revenue
    })
    .catch(error => console.error('Failed to load trend data:', error))
    
  axiosClient
    .get('/dashboard/performance-insight', { params: { d } })
    .then(({ data }) => {
      performanceInsight.value = data.message || ""
    })
    .catch(error => console.error('Failed to load performance insight:', error))
}

// Date picker change handler
function onDatePickerChange() {
  updateDashboard()
}

// Refresh all dashboard data
function refreshData() {
  updateDashboard()
}

// Refresh only revenue data
function refreshRevenueData() {
  fetchRevenueData()
}

// Toggle chart view between doughnut and bar
function toggleChartView() {
  chartView.value = chartView.value === 'doughnut' ? 'bar' : 'doughnut'
}

// Handle click on revenue chart segment
function onRevenueSegmentClick(index) {
  if (index >= 0 && index < revenueChartData.value.datasets[0].categoryNames.length) {
    const categoryName = revenueChartData.value.datasets[0].categoryNames[index]
    selectedRevenueCategory.value = categoryName
    
    // Scroll to orders section to show filtered results
    setTimeout(() => {
      const ordersSection = document.querySelector('.latest-orders-section')
      if (ordersSection) {
        ordersSection.scrollIntoView({ behavior: 'smooth', block: 'start' })
      }
    }, 100)
  }
}

// Clear revenue category filter
function clearRevenueCategoryFilter() {
  selectedRevenueCategory.value = null
}

// Customer avatar/initials helper functions
function getCustomerInitials(customer) {
  if (!customer) return '';
  return (customer.first_name?.[0] || '') + (customer.last_name?.[0] || '');
}

function getCustomerAvatarColor(id) {
  const colors = [
    'bg-indigo-500',
    'bg-blue-500',
    'bg-green-500',
    'bg-yellow-500',
    'bg-red-500',
    'bg-purple-500'
  ];
  // Ensure consistent color based on customer ID and handle non-numeric IDs
  const idNumber = typeof id === 'number' ? id : (parseInt(id) || 0);
  return colors[idNumber % colors.length];
}

// Refresh customers and revenue data
function refreshSpecificSections() {
  // Reset loading flags for specific sections
  loading.value.revenueData = true
  loading.value.latestCustomers = true
  
  // Fetch revenue data
  fetchRevenueData()
  
  // Fetch latest customers
  const d = chosenDate.value
  axiosClient
    .get('/dashboard/latest-customers', { params: { d } })
    .then(({ data }) => {
      latestCustomers.value = data || []
      loading.value.latestCustomers = false
    })
    .catch(error => {
      console.error('Failed to refresh latest customers:', error)
      // Try fallback to customers API
      axiosClient
        .get('/customers', { params: { per_page: 4, sort_field: 'created_at', sort_direction: 'desc' } })
        .then(response => {
          if (response.data && response.data.data) {
            latestCustomers.value = response.data.data
          } else {
            latestCustomers.value = []
          }
          loading.value.latestCustomers = false
        })
        .catch(fallbackError => {
          loading.value.latestCustomers = false
        })
    })
}

// Add both onMounted and onActivated hooks
onMounted(() => {
  console.log('Dashboard: Component mounted')
  updateDashboard()
})

// This hook will be called when the component is re-used from cache
onActivated(() => {
  console.log('Dashboard: Component activated')
  updateDashboard()
})

// Watch for route changes to reload data when returning to dashboard
watch(
  () => route.name,
  (newRouteName) => {
    if (newRouteName === 'app.dashboard') {
      console.log('Dashboard: Route changed back to dashboard')
      updateDashboard()
    }
  }
)
</script>

<style scoped>
/* Improved animations for a more polished feel */
.animate-fade-in-down {
  animation: fadeInDown 0.5s cubic-bezier(0.23, 1, 0.32, 1) forwards;
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    transform: translateY(-12px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Custom animation delays */
.animate-delay-100ms {
  animation-delay: 0.1s;
}
.animate-delay-200ms {
  animation-delay: 0.2s;
}
.animate-delay-300ms {
  animation-delay: 0.3s;
}
</style>