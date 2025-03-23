// src/store/state.js
export default {
  user: {
    token: sessionStorage.getItem('TOKEN'),
    data: {}
  },
  products: {
    loading: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: 10,
    total: null
  },
  // Add categories state
  categories: {
    loading: false,
    data: []
  },
  users: {
    loading: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: 10,
    total: null
  },
  customers: {
    loading: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: 10,
    total: null
  },
  countries: [],
  orders: {
    loading: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: 10,
    total: null,
    selected: [], // Array of selected order IDs
    bulkLoading: false // Loading state for bulk operations
  },
  // Add dashboard state
  dashboard: {
    loading: false,
    period: 'month',
    metrics: {
      totalRevenue: 0,
      revenueChange: 0,
      revenueData: [],
      totalOrders: 0,
      ordersChange: 0,
      ordersData: [],
      averageOrderValue: 0,
      aovChange: 0,
      aovData: [],
      conversionRate: 0,
      conversionChange: 0,
      conversionData: []
    },
    charts: {
      revenue: {
        labels: [],
        datasets: []
      },
      orderStatus: {
        labels: [],
        datasets: []
      },
      topProducts: {
        labels: [],
        datasets: []
      }
    },
    recentOrders: []
  },
  // Add order notes state
  orderNotes: {
    loading: false,
    data: {}, // Object with order ID as key and array of notes as value
    submitting: false
  },
  toast: {
    show: false,
    message: '',
    type: 'success',
    delay: 5000
  },
  
  dateOptions: [
    {key: '1d', text: 'Last Day'},
    {key: '1k', text: 'Last Week'},
    {key: '2k', text: 'Last 2 Weeks'},
    {key: '1m', text: 'Last Month'},
    {key: '3m', text: 'Last 3 Months'},
    {key: '6m', text: 'Last 6 Months'},
    {key: 'all', text: 'All Time'},
  ]
}