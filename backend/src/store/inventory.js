import InventoryService from '../services/InventoryService';

export default {
  namespaced: true,
  
  state: {
    products: [],
    inventoryMovements: [],
    loading: {
      products: false,
      movements: false,
      operation: false
    },
    filters: {
      category: '',
      status: 'all',
      search: '',
      dateRange: { start: null, end: null }
    },
    pagination: {
      products: { page: 1, total: 0, perPage: 20 },
      movements: { page: 1, total: 0, perPage: 20 }
    },
    stats: {
      totalProducts: 0,
      inStockProducts: 0,
      lowStockProducts: 0,
      outOfStockProducts: 0,
      inventoryValue: 0
    }
  },
  
  getters: {
    filteredProducts: (state) => {
      // Handle potential null/undefined products array
      if (!state.products || !Array.isArray(state.products)) {
        return [];
      }
      
      let result = [...state.products];
      
      // Apply category filter with null checks
      if (state.filters && state.filters.category) {
        result = result.filter(product => {
          if (!product) return false;
          return product.category_id === state.filters.category ||
            (product.category && product.category.id === state.filters.category);
        });
      }
      
      // Apply status filter with null checks
      if (state.filters && state.filters.status) {
        if (state.filters.status === 'low') {
          result = result.filter(product => {
            if (!product) return false;
            return product.track_inventory && 
              typeof product.quantity === 'number' && 
              product.quantity > 0 && 
              product.quantity <= (product.reorder_level || 5);
          });
        } else if (state.filters.status === 'out') {
          result = result.filter(product => {
            if (!product) return false;
            return product.track_inventory && 
              typeof product.quantity === 'number' && 
              product.quantity === 0;
          });
        }
      }
      
      // Apply search filter with null checks
      if (state.filters && state.filters.search) {
        const searchTerm = state.filters.search.toLowerCase();
        result = result.filter(product => {
          if (!product) return false;
          return (product.title && product.title.toLowerCase().includes(searchTerm)) ||
            (product.sku && product.sku.toLowerCase().includes(searchTerm));
        });
      }
      
      return result;
    },
    
    productById: (state) => (id) => {
      if (!state.products || !Array.isArray(state.products)) {
        return null;
      }
      return state.products.find(product => product && product.id == id);
    },
    
    lowStockProducts: (state) => {
      if (!state.products || !Array.isArray(state.products)) {
        return [];
      }
      
      return state.products.filter(product => {
        if (!product) return false;
        return product.track_inventory && 
          typeof product.quantity === 'number' && 
          product.quantity > 0 && 
          product.quantity <= (product.reorder_level || 5);
      });
    },
    
    outOfStockProducts: (state) => {
      if (!state.products || !Array.isArray(state.products)) {
        return [];
      }
      
      return state.products.filter(product => {
        if (!product) return false;
        return product.track_inventory && 
          typeof product.quantity === 'number' && 
          product.quantity === 0;
      });
    },
    
    inventoryValue: (state) => {
      if (!state.products || !Array.isArray(state.products)) {
        return 0;
      }
      
      return state.products.reduce((total, product) => {
        if (!product) return total;
        const price = parseFloat(product.price) || 0;
        const quantity = typeof product.quantity === 'number' ? product.quantity : 0;
        return total + (price * quantity);
      }, 0);
    }
  },
  
  mutations: {
    setProducts(state, products) {
      state.products = products;
    },
    
    updateProductsStats(state) {
      state.stats.totalProducts = state.products.length;
      state.stats.inStockProducts = state.products.filter(p => p.quantity > 0).length;
      state.stats.lowStockProducts = state.products.filter(p => 
        p.track_inventory && 
        p.quantity > 0 && 
        p.quantity <= (p.reorder_level || 5)
      ).length;
      state.stats.outOfStockProducts = state.products.filter(p => 
        p.track_inventory && p.quantity === 0
      ).length;
      state.stats.inventoryValue = state.products.reduce((total, product) => {
        const price = parseFloat(product.price) || 0;
        const quantity = product.quantity || 0;
        return total + (price * quantity);
      }, 0);
    },
    
    setInventoryMovements(state, movements) {
      state.inventoryMovements = movements;
    },
    
    appendInventoryMovements(state, movements) {
      state.inventoryMovements = [...state.inventoryMovements, ...movements];
    },
    
    updateProduct(state, updatedProduct) {
      const index = state.products.findIndex(p => p.id == updatedProduct.id);
      if (index !== -1) {
        state.products.splice(index, 1, updatedProduct);
      }
    },
    
    setLoading(state, { key, value }) {
      state.loading[key] = value;
    },
    
    setFilter(state, { key, value }) {
      state.filters[key] = value;
    },
    
    setPagination(state, { key, data }) {
      state.pagination[key] = { ...state.pagination[key], ...data };
    }
  },
  
  actions: {
    async fetchProducts({ commit, state }, params = {}) {
      commit('setLoading', { key: 'products', value: true });
      
      try {
        // Start with the current filters from the state
        const requestParams = { ...params };
        
        // Add filters from state if they're not already in the passed params
        if (state.filters.category && !requestParams.category_id) {
          requestParams.category_id = state.filters.category;
        }
        
        if (state.filters.search && !requestParams.search) {
          requestParams.search = state.filters.search;
        }
        
        // Handle stock status filters
        if (state.filters.status && !requestParams.stock_status) {
          requestParams.stock_status = state.filters.status;
        }
        
        // For pagination
        if (!requestParams.page) {
          requestParams.page = state.pagination.products.page;
        }
        
        if (!requestParams.per_page) {
          requestParams.per_page = state.pagination.products.perPage;
        }
        
        // Log the final request parameters
        console.log('Fetching products with params:', requestParams);
        
        // Use the new getInventoryProducts method for better filtering
        const response = await InventoryService.getInventoryProducts(requestParams);
        
        // Log the response
        console.log('API response:', response);
        
        commit('setProducts', response.data);
        commit('updateProductsStats');
        commit('setPagination', { 
          key: 'products', 
          data: { 
            page: response.meta.current_page,
            total: response.meta.total
          }
        });
        
        return response;
      } catch (error) {
        console.error('Error fetching products:', error);
        throw error;
      } finally {
        commit('setLoading', { key: 'products', value: false });
      }
    },
    
    
    async fetchInventoryMovements({ commit, state }) {
      commit('setLoading', { key: 'movements', value: true });
      
      try {
        const params = {
          page: state.pagination.movements.page,
          per_page: state.pagination.movements.perPage
        };
        
        // Add filters if present
        if (state.filters.dateRange.start) params.start_date = state.filters.dateRange.start;
        if (state.filters.dateRange.end) params.end_date = state.filters.dateRange.end;
        
        const response = await InventoryService.getInventoryMovements(params);
        
        if (state.pagination.movements.page === 1) {
          commit('setInventoryMovements', response.data);
        } else {
          commit('appendInventoryMovements', response.data);
        }
        
        commit('setPagination', { 
          key: 'movements', 
          data: { 
            page: response.meta.current_page,
            total: response.meta.total
          }
        });
        
        return response.data;
      } catch (error) {
        console.error('Error fetching inventory movements:', error);
        throw error;
      } finally {
        commit('setLoading', { key: 'movements', value: false });
      }
    },
    
    async adjustInventory({ commit }, { productId, adjustment }) {
      commit('setLoading', { key: 'operation', value: true });
      
      try {
        const response = await InventoryService.adjustInventory(productId, adjustment);
        
        if (response.data && response.data.product) {
          commit('updateProduct', response.data.product);
          commit('updateProductsStats');
        }
        
        return response;
      } catch (error) {
        console.error('Error adjusting inventory:', error);
        throw error;
      } finally {
        commit('setLoading', { key: 'operation', value: false });
      }
    },
    
    async bulkAdjustInventory({ commit, dispatch }, adjustments) {
      commit('setLoading', { key: 'operation', value: true });
      
      try {
        const response = await InventoryService.bulkAdjustInventory(adjustments);
        
        // Refresh products to get updated data
        await dispatch('fetchProducts');
        
        return response;
      } catch (error) {
        console.error('Error performing bulk adjustment:', error);
        throw error;
      } finally {
        commit('setLoading', { key: 'operation', value: false });
      }
    },
    
    setFilter({ commit, dispatch }, { key, value }) {
      commit('setFilter', { key, value });
      
      // Reset pagination when filter changes
      commit('setPagination', { key: 'products', data: { page: 1 } });
      commit('setPagination', { key: 'movements', data: { page: 1 } });
      
      // Fetch filtered data
      if (['category', 'status', 'search'].includes(key)) {
        dispatch('fetchProducts');
      } else if (key === 'dateRange') {
        dispatch('fetchInventoryMovements');
      }
    },
    
    loadMore({ commit, dispatch, state }, key) {
      const pagination = state.pagination[key];
      const nextPage = pagination.page + 1;
      
      if (nextPage <= Math.ceil(pagination.total / pagination.perPage)) {
        commit('setPagination', { key, data: { page: nextPage } });
        
        if (key === 'products') {
          dispatch('fetchProducts');
        } else if (key === 'movements') {
          dispatch('fetchInventoryMovements');
        }
      }
    }
  }
};