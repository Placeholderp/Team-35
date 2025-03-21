import axiosClient from "../axios";
import { cleanId, normalizePublished, prepareProductFormData } from "../utils/ProductUtils";

// Utility function to normalize is_admin to boolean
function normalizeIsAdmin(value) {
  if (typeof value === 'boolean') return value;
  if (value === 1 || value === '1' || value === 'true') return true;
  return false;
}

export function createCategory({commit, dispatch}, categoryData) {
  return axiosClient.post('/categories', categoryData)
    .then(response => {
      // Trigger a refresh of categories after creation
      return dispatch('getCategories', { force: true })
        .then(() => response.data);
    });
}

export function updateCategory({commit, dispatch}, {id, categoryData}) {
  return axiosClient.put(`/categories/${id}`, categoryData)
    .then(response => {
      // Trigger a refresh of categories after update
      return dispatch('getCategories', { force: true })
        .then(() => response.data);
    });
}

export function getCurrentUser({commit}, data) {
  return axiosClient.get('/user', data)
    .then(({data}) => {
      // Normalize is_admin if it exists
      if (data) {
        data.is_admin = normalizeIsAdmin(data.is_admin);
      }
      commit('setUser', data);
      return data;
    })
}

export function login({commit}, data) {
  return axiosClient.post('/login', data)
    .then(({data}) => {
      // Normalize is_admin if it exists
      if (data && data.user) {
        data.user.is_admin = normalizeIsAdmin(data.user.is_admin);
      }
      commit('setUser', data.user);
      commit('setToken', data.token);
      
      // Return the complete data including any first_login flag
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

export function register({commit}, userData) {
  return axiosClient.post('/register', userData)
    .then(({data}) => {
      // We don't set the user or token here since they need to login after registration
      return data;
    });
}

export function changePassword({commit}, passwordData) {
  return axiosClient.post('/change-password', passwordData)
    .then(({data}) => {
      // If the response includes updated user data, update the store
      if (data.user) {
        // Normalize is_admin if it exists
        if (data.user) {
          data.user.is_admin = normalizeIsAdmin(data.user.is_admin);
        }
        commit('setUser', data.user);
      }
      return data;
    });
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

export function getCategories({commit, state}, {
  url = null, 
  search = '', 
  per_page, 
  sort_field, 
  sort_direction, 
  force = false
} = {}) {
  // Commit loading state
  commit('setCategories', [true]);
  
  // Use existing URL or default to categories endpoint
  url = url || '/categories';
  
  const params = {
    per_page: state.categories.limit || 10,
    include: 'products' // Change to 'products' to ensure proper relationship loading
  };
  
  // Add cache-busting timestamp when force is true
  if (force) {
    params._t = new Date().getTime();
  }
  
  // Add optional parameters
  if (search) params.search = search;
  if (per_page) params.per_page = per_page;
  if (sort_field) params.sort_field = sort_field;
  if (sort_direction) params.sort_direction = sort_direction;
  
  console.log('Fetching categories with params:', params);
  
  return axiosClient.get(url, { params })
    .then((response) => {
      console.log('Categories API response:', response.data);
      
      // Process and normalize category data
      const categories = response.data.data || response.data;
      
      // Commit the categories to the store
      commit('setCategories', [false, categories]);
      
      return response.data;
    })
    .catch((error) => {
      console.error('Error fetching categories:', error);
      
      // Commit loading state with failure
      commit('setCategories', [false]);
      
      // Rethrow the error for the caller to handle
      throw error;
    });
}

// Updated getProducts action to include category data
export function getProducts({commit, state}, {
  url = null, 
  search = '', 
  per_page, 
  sort_field, 
  sort_direction, 
  category_id = null, // Add category_id parameter
  force = false
} = {}) {
  commit('setProducts', [true])
  url = url || '/products'
  const params = {
    per_page: state.products.limit,
    include: 'category' // Include category in response data
  }
  
  // Add a timestamp parameter when force is true to bust the cache
  if (force) {
    params._t = new Date().getTime();
  }
  
  // Add category filter if specified
  if (category_id) {
    params.category_id = category_id;
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
  
  return axiosClient.get(`/products/${productId}`, {
    params: {
      include: 'category' // Include category data
    }
  })
    .then(response => {
      // If the response contains product data, normalize published status
      if (response.data) {
        response.data.published = normalizePublished(response.data.published);
      }
      
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

// Update the createProduct function in actions.js to refresh categories after adding a product:

export function createProduct({commit, dispatch}, product) {
  if (product instanceof FormData) {
    const categoryId = product.get('category_id');
    
    return axiosClient.post('/products', product)
      .then(response => {
        // If the product has a category, refresh categories data
        if (categoryId) {
          console.log('New product added to category', categoryId, '- refreshing categories data');
          dispatch('getCategories', { force: true });
        }
        
        return response;
      });
  }
  
  const formData = prepareProductFormData(product, false);
  const categoryId = product.category_id;
  
  return axiosClient.post('/products', formData)
    .then(response => {
      // If the product has a category, refresh categories data
      if (categoryId) {
        console.log('New product added to category', categoryId, '- refreshing categories data');
        dispatch('getCategories', { force: true });
      }
      
      return response;
    });
}

// Replace the updateProduct function in actions.js with this improved version:

export function updateProduct({commit, dispatch}, product) {
  let id;
  let originalCategoryId = null;
  let newCategoryId = null;
  
  if (product instanceof FormData) {
    id = product.get('id');
    id = cleanId(id);
    
    // Extract category IDs for comparison
    originalCategoryId = product.get('original_category_id');
    newCategoryId = product.get('category_id');
    
    // Debug the data being sent
    console.log('UPDATE PRODUCT REQUEST DATA:');
    for (let [key, value] of product.entries()) {
      console.log(`${key}: ${value}`);
    }
    
    // Make the request
    return axiosClient.post(`/products/${id}`, product)
      .then(response => {
        console.log('UPDATE PRODUCT RESPONSE:', response.data);
        
        // Force refresh product data from server
        return axiosClient.get(`/products/${id}`, {
          params: {
            include: 'category' // Include category data when refreshing
          }
        })
          .then(refreshResponse => {
            console.log('REFRESHED PRODUCT DATA:', refreshResponse.data);
            
            // Update in store with the fresh data
            commit('updateProductInList', refreshResponse.data.data);
            
            // If the category has changed, refresh categories data
            if (originalCategoryId !== newCategoryId) {
              console.log('Category changed from', originalCategoryId, 'to', newCategoryId, '- refreshing categories data');
              dispatch('getCategories', { force: true });
            }
            
            return response; // Return original response
          });
      });
  }
  
  // Handle non-FormData product updates
  // Extract category information
  if (product.category_id !== undefined && product.original_category_id !== undefined) {
    originalCategoryId = product.original_category_id;
    newCategoryId = product.category_id;
  }
  
  id = cleanId(product.id);
  const formData = prepareProductFormData(product, false);
  
  return axiosClient.post(`/products/${id}`, formData)
    .then(response => {
      // Refresh product in store
      commit('updateProductInList', response.data);
      
      // If the category has changed, refresh categories data
      if (originalCategoryId !== newCategoryId) {
        console.log('Category changed from', originalCategoryId, 'to', newCategoryId, '- refreshing categories data');
        dispatch('getCategories', { force: true });
      }
      
      return response;
    });
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
      // Properly normalize is_admin values
      if (response.data && response.data.data) {
        response.data.data = response.data.data.map(user => ({
          ...user,
          is_admin: normalizeIsAdmin(user.is_admin)
        }));
      }
      commit('setUsers', [false, response.data])
    })
    .catch(() => {
      commit('setUsers', [false])
    })
}

export function createUser({commit}, user) {
  // Ensure we're working with a copy to avoid mutation
  const userData = { ...user };
  return axiosClient.post('/users', userData)
}

export function updateUser({commit}, user) {
  // Ensure we're working with a copy to avoid mutation
  const userData = { ...user };
  return axiosClient.put(`/users/${userData.id}`, userData)
}

export function deleteUser({commit}, id) {
  return axiosClient.delete(`/users/${id}`)
    .then(response => {
      // Remove the user from the store
      commit('REMOVE_USER', id);
      return response;
    });
}
export function checkUserRole({commit}, {email}) {
  return axiosClient.get('/check-user-role', {
    params: { email }
  })
    .then(({data}) => {
      if (data && data.exists) {
        // Normalize is_admin if it exists
        data.is_admin = normalizeIsAdmin(data.is_admin);
      }
      return data;
    })
    .catch(error => {
      console.error('Error checking user role:', error);
      return { exists: false, is_admin: false };
    });
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