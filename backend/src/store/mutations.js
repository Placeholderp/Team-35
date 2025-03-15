import { normalizePublished } from "../utils/ProductUtils";

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

export function setUsers(state, [loading, data = null]) {
  if (data) {
    state.users = {
      ...state.users,
      data: data.data,
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

export function setOrders(state, [loading, data = null]) {
  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.orders.loading = loading;
}

export function showToast(state, message, type = 'success') {
  state.toast.show = true;
  state.toast.message = message;
  state.toast.type = type;
}

export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}

export function setCountries(state, countries) {
  state.countries = countries.data;
}

export function REMOVE_USER(state, userId) {
  state.users.data = state.users.data.filter(user => user.id !== userId);
}

// Update products in the list with normalized published values
export function updateProductInList(state, updatedProduct) {
  if (!state.products.data || !Array.isArray(state.products.data)) {
    return;
  }
  
  // Find the product by ID
  const index = state.products.data.findIndex(p => p.id === updatedProduct.id);
  if (index !== -1) {
    // Ensure published value is normalized to a boolean
    updatedProduct.published = normalizePublished(updatedProduct.published);
    
    // Create a new array to maintain reactivity
    const newData = [...state.products.data];
    newData[index] = updatedProduct;
    state.products.data = newData;
  }
}