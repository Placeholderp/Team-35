import axiosClient from "../axios";
import OrderService from '../services/OrderService';
import { cleanId, normalizePublished, prepareProductFormData } from "../utils/ProductUtils";
import { exportOrdersToCsv } from '../utils/exportUtils';

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

export function getCustomers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction, status} = {}) {
  commit('setCustomers', [true])
  url = url || '/customers'
  const params = {
    per_page: state.customers.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction, status
    }
  })
  .then((response) => {
    commit('setCustomers', [false, response.data])
  })
  .catch((error) => {
    commit('setCustomers', [false])
    console.error("Error fetching customers:", error);
  })
}


export function getCustomer({commit}, userId) {
  // Extract user_id consistently, prioritizing user_id if available
  const user_id = typeof userId === 'object' ? (userId.user_id || userId.id) : userId;
    
  return axiosClient.get(`/customers/${user_id}`)
    .then(response => {
      // Initialize empty address objects if they don't exist
      if (response.data && !response.data.shippingAddress) {
        response.data.shippingAddress = {
          address1: '',
          address2: '',
          city: '',
          state: '',
          zipcode: '',
          country_code: ''
        };
      }
      
      if (response.data && !response.data.billingAddress) {
        response.data.billingAddress = {
          address1: '',
          address2: '',
          city: '',
          state: '',
          zipcode: '',
          country_code: ''
        };
      }
      
      return response;
    });
}

export function updateCustomer({commit}, customer) {
  // Create a clean copy of the customer data
  const customerData = {
    user_id: customer.user_id,
    first_name: customer.first_name,
    last_name: customer.last_name,
    email: customer.email,
    status: customer.status
  };
  
  // Check if we have valid address data
  if (customer.billingAddress && customer.billingAddress.address1 && 
      customer.billingAddress.address1.trim() !== '') {
    
    // Create a clean billing address with explicit empty strings for all fields
    customerData.billingAddress = {
      user_id: customerData.user_id,
      type: 'billing',
      address1: customer.billingAddress.address1 || '',
      address2: customer.billingAddress.address2 || '', // Ensures never null
      city: customer.billingAddress.city || '',
      state: customer.billingAddress.state || '',
      zipcode: customer.billingAddress.zipcode || '',
      country_code: customer.billingAddress.country_code || ''
    };
  }
  
  if (customer.shippingAddress && customer.shippingAddress.address1 && 
      customer.shippingAddress.address1.trim() !== '') {
    
    // Create a clean shipping address with explicit empty strings for all fields
    customerData.shippingAddress = {
      user_id: customerData.user_id,
      type: 'shipping',
      address1: customer.shippingAddress.address1 || '',
      address2: customer.shippingAddress.address2 || '', // Ensures never null
      city: customer.shippingAddress.city || '',
      state: customer.shippingAddress.state || '',
      zipcode: customer.shippingAddress.zipcode || '',
      country_code: customer.shippingAddress.country_code || ''
    };
  }
  
  console.log('Sending null-safe customer data:', JSON.stringify(customerData, null, 2));
  
  // Send all data in a single request
  return axiosClient.put(`/customers/${customerData.user_id}`, customerData);
}
// Add this function to your actions.js file alongside your other order-related actions

/**
 * Get a single order by ID
 * @param {Object} context - Vuex action context
 * @param {number|string} orderId - The ID of the order to retrieve
 * @returns {Promise} - Promise with the order data
 */
export function getOrder({commit}, orderId) {
  // You could add a loading state here if needed
  // commit('setOrderLoading', true);
  
  return OrderService.getOrder(orderId)
    .then(response => {
      console.log('Order fetched successfully:', response.data);
      
      // You could store the current order in state if needed for reuse
      // commit('setCurrentOrder', response.data);
      
      return response;
    })
    .catch(error => {
      console.error('Error fetching order details:', error);
      
      // Show toast notification for error
      commit('showToast', {
        message: 'Error loading order details.',
        type: 'error'
      });
      
      throw error;
    });
}

// In actions.js
export function getOrders({commit, state}, params = {}) {
  commit('setOrders', [true]);
  
  return OrderService.getOrders(params)
    .then((response) => {
      // Debug the incoming data structure
      console.log('API response structure:', response);
      
      // Make sure we're properly extracting the data
      // Laravel APIs often wrap the data in a 'data' property
      const responseData = response.data;
      
      // Ensure all orders have IDs for display
      if (responseData && responseData.data) {
        responseData.data = responseData.data.map(order => {
          if (!order.id) {
            console.warn('Order missing ID:', order);
            // Provide a temporary ID if needed
            return { ...order, id: `tmp-${Date.now()}` };
          }
          return order;
        });
      }
      
      commit('setOrders', [false, responseData]);
      return responseData;
    })
    .catch((error) => {
      // Your existing error handling...
    });
}

// Bulk action for orders
// Replace this function in your actions.js file

export function updateOrdersStatus({commit, dispatch}, {orderIds, status}) {
  commit('setBulkLoading', true);
  
  // Let OrderService handle the actual API calls
  return OrderService.updateOrdersStatus(orderIds, status)
    .then(results => {
      // Check for any failed updates
      const failedUpdates = results.filter(result => result && result.error);
      
      if (failedUpdates.length > 0) {
        console.warn(`${failedUpdates.length} of ${orderIds.length} order updates failed`);
        
        if (failedUpdates.length < orderIds.length) {
          // Some succeeded, show partial success message
          commit('showToast', {
            message: `Updated ${orderIds.length - failedUpdates.length} orders successfully. ${failedUpdates.length} failed.`,
            type: 'warning'
          });
        } else {
          // All failed
          throw new Error('All order updates failed');
        }
      } else {
        // All succeeded
        commit('showToast', {
          message: `Status updated for ${orderIds.length} orders`,
          type: 'success'
        });
      }
      
      // Clear selection
      commit('clearSelectedOrders');
      
      // Refresh orders list
      return dispatch('getOrders');
    })
    .catch(error => {
      console.error('Error updating orders status:', error);
      commit('showToast', {
        message: 'Failed to update orders status',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setBulkLoading', false);
    });
}

export function printOrders({commit}, orderIds) {
  commit('setBulkLoading', true);
  
  return OrderService.generateOrdersPdf(orderIds)
    .then(response => {
      // Create a blob from the PDF data
      const blob = new Blob([response.data], { type: 'application/pdf' });
      const url = window.URL.createObjectURL(blob);
      
      // Open the PDF in a new tab
      window.open(url, '_blank');
      
      commit('showToast', {
        message: 'Orders prepared for printing',
        type: 'success'
      });
    })
    .catch(error => {
      console.error('Error generating PDF:', error);
      commit('showToast', {
        message: 'Failed to prepare orders for printing',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setBulkLoading', false);
    });
}

export function exportOrders({commit}, orderIds) {
  commit('setBulkLoading', true);
  
  return OrderService.getOrdersForExport(orderIds)
    .then(response => {
      const orders = response.data;
      
      // Use the exportOrdersToCsv utility function
      const filename = `orders_export_${new Date().toISOString().slice(0, 10)}.csv`;
      exportOrdersToCsv(orders, filename);
      
      commit('showToast', {
        message: `${orders.length} orders exported successfully`,
        type: 'success'
      });
    })
    .catch(error => {
      console.error('Error exporting orders:', error);
      commit('showToast', {
        message: 'Failed to export orders',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setBulkLoading', false);
    });
}

// Dashboard data action
export function getDashboardData({commit, state}, period = null) {
  commit('setDashboardLoading', true);
  
  // Use provided period or current period from state
  period = period || state.dashboard.period;
  
  // Update period in state
  if (period !== state.dashboard.period) {
    commit('setDashboardPeriod', period);
  }
  
  return OrderService.getDashboardData(period)
    .then(response => {
      const data = response.data;
      
      // Update metrics
      commit('setDashboardMetrics', data.metrics);
      
      // Update charts
      commit('setDashboardCharts', data.charts);
      
      // Update recent orders
      commit('setRecentOrders', data.recentOrders);
      
      return data;
    })
    .catch(error => {
      console.error('Error fetching dashboard data:', error);
      commit('showToast', {
        message: 'Failed to load dashboard data',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setDashboardLoading', false);
    });
}

// Order notes actions
export function getOrderNotes({commit}, orderId) {
  commit('setOrderNotesLoading', true);
  
  return OrderService.getOrderNotes(orderId)
    .then(response => {
      commit('setOrderNotes', {
        orderId,
        notes: response.data
      });
      return response.data;
    })
    .catch(error => {
      console.error('Error fetching order notes:', error);
      commit('showToast', {
        message: 'Failed to load order notes',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setOrderNotesLoading', false);
    });
}

export function createOrderNote({commit}, {orderId, note}) {
  commit('setOrderNotesSubmitting', true);
  
  return OrderService.createOrderNote(orderId, note)
    .then(response => {
      commit('addOrderNote', {
        orderId,
        note: response.data
      });
      commit('showToast', {
        message: 'Note added successfully',
        type: 'success'
      });
      return response.data;
    })
    .catch(error => {
      console.error('Error adding order note:', error);
      commit('showToast', {
        message: 'Failed to add note',
        type: 'error'
      });
      throw error;
    })
    .finally(() => {
      commit('setOrderNotesSubmitting', false);
    });
}