import axiosClient from "../axios";
import { cleanId, normalizePublished, prepareProductFormData } from "../utils/ProductUtils";

export function getCurrentUser({commit}, data) {
  return axiosClient.get('/user', data)
    .then(({data}) => {
      commit('setUser', data);
      return data;
    })
}

export function login({commit}, data) {
  return axiosClient.post('/login', data)
    .then(({data}) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}

export function logout({commit}) {
  return axiosClient.post('/logout')
    .then((response) => {
      commit('setToken', null)
      return response;
    })
}

export function getCountries({commit}) {
  return axiosClient.get('countries')
    .then(({data}) => {
      commit('setCountries', data)
    })
}

export function getOrders({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setOrders', [true])
  url = url || '/orders'
  const params = {
    per_page: state.orders.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setOrders', [false, response.data])
    })
    .catch(() => {
      commit('setOrders', [false])
    })
}

export function getOrder({commit}, id) {
  return axiosClient.get(`/orders/${id}`)
}

export function getProducts({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction, force = false} = {}) {
  commit('setProducts', [true])
  url = url || '/products'
  const params = {
    per_page: state.products.limit,
  }
  
  // Add a timestamp parameter when force is true to bust the cache
  if (force) {
    params._t = new Date().getTime();
  }
  
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      // Process the data to normalize published values
      if (response.data && response.data.data) {
        // Normalize published values to boolean
        response.data.data = response.data.data.map(product => ({
          ...product,
          published: normalizePublished(product.published)
        }));
      }
      
      commit('setProducts', [false, response.data])
      return response.data;
    })
    .catch((error) => {
      commit('setProducts', [false])
      throw error;
    })
}

export function getProduct({commit}, id) {
  // Set loading state
  commit('setProducts', [true]);
  
  // Clean the ID
  const productId = cleanId(id);
  
  return axiosClient.get(`/products/${productId}`)
    .then(response => {
      // Reset loading state
      commit('setProducts', [false]);
      // Ensure we're returning the full response object
      return response;
    })
    .catch(error => {
      commit('setProducts', [false]);
      throw error;
    });
}

export function createProduct({commit}, product) {
  if (product instanceof FormData) {
    return axiosClient.post('/products', product);
  }
  
  const formData = prepareProductFormData(product, false);
  return axiosClient.post('/products', formData);
}

export function updateProduct({commit}, product) {
  // Extract and clean the ID upfront
  let id;
  
  if (product instanceof FormData) {
    id = cleanId(product.get('id'));
    
    // Update the ID in the FormData to ensure it's clean
    if (product.has('id')) {
      product.delete('id');
      product.append('id', id);
    }
    
    // Make sure FormData has _method
    if (!product.has('_method')) {
      product.append('_method', 'PUT');
    }
    
    return axiosClient.post(`/products/${id}`, product);
  }
  
  // Clean the ID
  id = cleanId(product.id);
  
  // Use our utility function to prepare the FormData
  const formData = prepareProductFormData(product, true);
  
  // Use clean ID in URL
  return axiosClient.post(`/products/${id}`, formData);
}

export function deleteProduct({commit}, id) {
  // Clean the ID to remove any colons
  const cleanedId = cleanId(id);
  
  return axiosClient.delete(`/products/${cleanedId}`);
}



export function getUsers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setUsers', [true])
  url = url || '/users'
  const params = {
    per_page: state.users.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setUsers', [false, response.data])
    })
    .catch(() => {
      commit('setUsers', [false])
    })
}

export function createUser({commit}, user) {
  return axiosClient.post('/users', user)
}

export function updateUser({commit}, user) {
  return axiosClient.put(`/users/${user.id}`, user)
}

export function getCustomers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setCustomers', [true])
  url = url || '/customers'
  const params = {
    per_page: state.customers.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setCustomers', [false, response.data])
    })
    .catch(() => {
      commit('setCustomers', [false])
    })
}

export function getCustomer({commit}, id) {
  return axiosClient.get(`/customers/${id}`)
}

export function createCustomer({commit}, customer) {
  return axiosClient.post('/customers', customer)
}

export function updateCustomer({commit}, customer) {
  return axiosClient.put(`/customers/${customer.id}`, customer)
}

export function deleteCustomer({commit}, customer) {
  return axiosClient.delete(`/customers/${customer.id}`)
}