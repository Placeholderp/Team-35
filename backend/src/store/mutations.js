import { normalizePublished } from "../utils/ProductUtils";
import { normalizeActiveStatus } from "../utils/CategoryUtils";

export function setUser(state, user) {
  state.user.data = user;
}

export function setToken(state, token) {
  state.user.token = token;
  if (token) {
    sessionStorage.setItem('TOKEN', token);
  } else {
    sessionStorage.removeItem('TOKEN')
  }
}

// Add setCategories mutation
export function setCategories(state, [loading, data = null]) {
  if (data) {
    // Normalize is_active status if present
    if (Array.isArray(data)) {
      data = data.map(category => ({
        ...category,
        is_active: normalizeActiveStatus(category.is_active)
      }));
    }
    
    state.categories.data = data;
  }
  state.categories.loading = loading;
}

export function setProducts(state, [loading, data = null]) {
  if (data) {
    // Normalize published values in all products
    if (data.data && Array.isArray(data.data)) {
      data.data = data.data.map(product => {
        return {
          ...product,
          published: normalizePublished(product.published)
        };
      });
    }

    state.products = {
      ...state.products,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

function normalizeIsAdmin(value) {
  if (typeof value === 'boolean') return value;
  if (value === 1 || value === '1' || value === 'true') return true;
  return false;
}

export function setUsers(state, [loading, data = null]) {
  if (data) {
    // Normalize is_admin values before storing in state
    let normalizedData = data.data;
    if (normalizedData && Array.isArray(normalizedData)) {
      normalizedData = normalizedData.map(user => ({
        ...user,
        // Convert is_admin to a proper boolean
        is_admin: normalizeIsAdmin(user.is_admin)
      }));
    }

    state.users = {
      ...state.users,
      data: normalizedData,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.users.loading = loading;
}

export function setCustomers(state, [loading, data = null]) {
  if (data) {
    state.customers = {
      ...state.customers,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.customers.loading = loading;
}

// Update in mutations.js
export function setOrders(state, [loading, data = null]) {
  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data || [],
      links: data.meta?.links || [],
      page: data.meta?.current_page || 1,
      limit: data.meta?.per_page || state.orders.limit,
      from: data.meta?.from || null,
      to: data.meta?.to || null,
      total: data.meta?.total || 0,
    }
  }
  state.orders.loading = loading;
}

// Bulk order selection mutations
export function setSelectedOrders(state, orderIds) {
  state.orders.selected = orderIds;
}

export function toggleOrderSelection(state, orderId) {
  const index = state.orders.selected.indexOf(orderId);
  if (index === -1) {
    state.orders.selected.push(orderId);
  } else {
    state.orders.selected.splice(index, 1);
  }
}

export function clearSelectedOrders(state) {
  state.orders.selected = [];
}

export function setBulkLoading(state, isLoading) {
  state.orders.bulkLoading = isLoading;
}

export function showToast(state, messageOrOptions, type = 'success') {
  // Handle both simple message string or options object
  if (typeof messageOrOptions === 'object') {
    const { message, type: optionsType = 'success', title, delay = 5000 } = messageOrOptions;
    state.toast.show = true;
    state.toast.message = message;
    state.toast.type = optionsType;
    state.toast.title = title;
    state.toast.delay = delay;
  } else {
    state.toast.show = true;
    state.toast.message = messageOrOptions;
    state.toast.type = type;
  }
}

export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}

export function setCountries(state, countries) {
  state.countries = countries.data;
}

export function REMOVE_USER(state, userId) {
  if (state.users.data) {
    state.users.data = state.users.data.filter(user => user.id !== userId);
  }
}

// Update products in the list with normalized published values
export function updateProductInList(state, updatedProduct) {
  if (!state.products.data || !Array.isArray(state.products.data)) {
    return;
  }
  
  // Ensure normalized published value
  const normalizedProduct = {
    ...updatedProduct,
    published: normalizePublished(updatedProduct.published)
  };
  
  // Find the product by ID
  const index = state.products.data.findIndex(p => String(p.id) === String(normalizedProduct.id));
  
  if (index !== -1) {
    // Create a new array to maintain reactivity
    const newData = [...state.products.data];
    newData[index] = normalizedProduct;
    state.products.data = newData;
  } else {
    // If product not found in the list, add it (useful for newly created products)
    state.products.data = [...state.products.data, normalizedProduct];
  }
}

// Sync category product counts based on products in state
export function syncCategoryCounts(state) { 
  if (!Array.isArray(state.categories.data) || !Array.isArray(state.products.data)) { 
    return; 
  } 
  
  // Create category ID to count mapping 
  const categoryCounts = {}; 
  
  // Count products for each category 
  state.products.data.forEach(product => { 
    if (!product || !product.category_id) return; 
    const categoryId = product.category_id.toString(); 
    if (!categoryCounts[categoryId]) { 
      categoryCounts[categoryId] = 0; 
    } 
    categoryCounts[categoryId]++; 
  }); 
  
  // Update each category with the correct count 
  state.categories.data = state.categories.data.map(category => { 
    if (!category || !category.id) return category; 
    const categoryId = category.id.toString(); 
    return { 
      ...category, 
      product_count: categoryCounts[categoryId] || 0 
    }; 
  }); 
}

// Dashboard mutations
export function setDashboardLoading(state, loading) {
  state.dashboard.loading = loading;
}

export function setDashboardPeriod(state, period) {
  state.dashboard.period = period;
}

export function setDashboardMetrics(state, metrics) {
  state.dashboard.metrics = { ...state.dashboard.metrics, ...metrics };
}

export function setDashboardCharts(state, charts) {
  state.dashboard.charts = { ...state.dashboard.charts, ...charts };
}

export function setRecentOrders(state, orders) {
  state.dashboard.recentOrders = orders;
}

// Order notes mutations
export function setOrderNotesLoading(state, loading) {
  state.orderNotes.loading = loading;
}

export function setOrderNotesSubmitting(state, submitting) {
  state.orderNotes.submitting = submitting;
}

export function setOrderNotes(state, { orderId, notes }) {
  // Use Vue.set-like approach for reactivity
  state.orderNotes.data = {
    ...state.orderNotes.data,
    [orderId]: notes
  };
}

export function addOrderNote(state, { orderId, note }) {
  const orderNotes = state.orderNotes.data[orderId] || [];
  state.orderNotes.data = {
    ...state.orderNotes.data,
    [orderId]: [note, ...orderNotes]
  };
}