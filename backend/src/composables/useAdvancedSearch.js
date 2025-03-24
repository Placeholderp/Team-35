import { ref, computed, watch } from 'vue';
import { debounce } from 'lodash';

export function useAdvancedSearch(fetchFunction, options = {}) {
  const {
    initialFilters = {},
    initialSort = { field: 'created_at', direction: 'desc' },
    debounceMs = 300
  } = options;

  const filters = ref({
    search: '',
    category: '',
    status: '',
    minPrice: null,
    maxPrice: null,
    minStock: null,
    maxStock: null,
    dateRange: { start: null, end: null },
    ...initialFilters
  });
  
  const sort = ref({
    field: initialSort.field,
    direction: initialSort.direction
  });
  
  const pagination = ref({
    page: 1,
    perPage: 20,
    total: 0
  });
  
  const loading = ref(false);
  const items = ref([]);
  const error = ref(null);
  const hasMorePages = computed(() => {
    return pagination.value.page < Math.ceil(pagination.value.total / pagination.value.perPage);
  });
  
  // Create debounced search function
  const debouncedSearch = debounce(() => {
    pagination.value.page = 1;
    fetchData();
  }, debounceMs);
  
  // Watch for filter changes
  watch(() => filters.value.search, () => {
    debouncedSearch();
  });
  
  watch([
    () => filters.value.category,
    () => filters.value.status,
    () => filters.value.minPrice,
    () => filters.value.maxPrice,
    () => filters.value.minStock,
    () => filters.value.maxStock,
    () => filters.value.dateRange
  ], () => {
    pagination.value.page = 1;
    fetchData();
  });
  
  // Watch for sort changes
  watch(() => sort.value, () => {
    pagination.value.page = 1;
    fetchData();
  });
  
  // Fetch data
  async function fetchData() {
    loading.value = true;
    error.value = null;
    
    try {
      // Build parameters from filters, sort, and pagination
      const params = {
        page: pagination.value.page,
        per_page: pagination.value.perPage,
        sort_field: sort.value.field,
        sort_direction: sort.value.direction
      };
      
      // Add filters if present
      if (filters.value.search) params.search = filters.value.search;
      if (filters.value.category) params.category_id = filters.value.category;
      if (filters.value.status) params.status = filters.value.status;
      if (filters.value.minPrice) params.min_price = filters.value.minPrice;
      if (filters.value.maxPrice) params.max_price = filters.value.maxPrice;
      if (filters.value.minStock) params.min_stock = filters.value.minStock;
      if (filters.value.maxStock) params.max_stock = filters.value.maxStock;
      if (filters.value.dateRange.start) params.start_date = filters.value.dateRange.start;
      if (filters.value.dateRange.end) params.end_date = filters.value.dateRange.end;
      
      const response = await fetchFunction(params);
      
      // Update items and pagination
      items.value = response.data;
      pagination.value.total = response.meta.total;
      pagination.value.page = response.meta.current_page;
      pagination.value.perPage = response.meta.per_page;
    } catch (err) {
      console.error('Error fetching data:', err);
      error.value = err.message || 'Failed to fetch data';
    } finally {
      loading.value = false;
    }
  }
  
  // Load more items
  async function loadMore() {
    if (!hasMorePages.value || loading.value) return;
    
    pagination.value.page++;
    loading.value = true;
    error.value = null;
    
    try {
      // Build parameters from filters, sort, and pagination
      const params = {
        page: pagination.value.page,
        per_page: pagination.value.perPage,
        sort_field: sort.value.field,
        sort_direction: sort.value.direction
      };
      
      // Add filters if present
      if (filters.value.search) params.search = filters.value.search;
      if (filters.value.category) params.category_id = filters.value.category;
      if (filters.value.status) params.status = filters.value.status;
      if (filters.value.minPrice) params.min_price = filters.value.minPrice;
      if (filters.value.maxPrice) params.max_price = filters.value.maxPrice;
      if (filters.value.minStock) params.min_stock = filters.value.minStock;
      if (filters.value.maxStock) params.max_stock = filters.value.maxStock;
      if (filters.value.dateRange.start) params.start_date = filters.value.dateRange.start;
      if (filters.value.dateRange.end) params.end_date = filters.value.dateRange.end;
      
      const response = await fetchFunction(params);
      
      // Append new items
      items.value = [...items.value, ...response.data];
      pagination.value.total = response.meta.total;
    } catch (err) {
      console.error('Error loading more data:', err);
      error.value = err.message || 'Failed to load more data';
      // Revert page increment on error
      pagination.value.page--;
    } finally {
      loading.value = false;
    }
  }
  
  // Set filter
  function setFilter(key, value) {
    filters.value[key] = value;
  }
  
  // Set sort
  function setSort(field, direction = null) {
    if (sort.value.field === field && !direction) {
      // Toggle direction if clicking the same field
      sort.value.direction = sort.value.direction === 'asc' ? 'desc' : 'asc';
    } else {
      sort.value.field = field;
      sort.value.direction = direction || 'asc';
    }
  }
  
  // Reset filters
  function resetFilters() {
    filters.value = {
      search: '',
      category: '',
      status: '',
      minPrice: null,
      maxPrice: null,
      minStock: null,
      maxStock: null,
      dateRange: { start: null, end: null },
      ...initialFilters
    };
  }
  
  // Initialize data
  fetchData();
  
  return {
    filters,
    sort,
    pagination,
    loading,
    items,
    error,
    hasMorePages,
    fetchData,
    loadMore,
    setFilter,
    setSort,
    resetFilters
  };
}