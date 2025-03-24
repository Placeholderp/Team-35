import axiosClient from '../axios';
import CacheService from '../services/CachService';
import store from '../store';

export default {
  /**
   * Default response structure for normalized API responses
   */
  defaultResponseStructure: {
    data: [],
    meta: {
      current_page: 1,
      last_page: 1,
      total: 0,
      per_page: 10
    }
  },

  /**
   * Normalize API response to ensure consistent structure
   * @param {Object} response - API response object
   * @returns {Object} - Normalized response
   */
  normalizeResponse(response) {
    if (!response) return { ...this.defaultResponseStructure };
    
    return {
      data: response.data || [],
      meta: {
        current_page: response.meta?.current_page || 1,
        last_page: response.meta?.last_page || 1,
        total: response.meta?.total || 0,
        per_page: response.meta?.per_page || 10
      }
    };
  },

  /**
   * Fetch all products with inventory information
   * @param {Object} params - Query parameters for filtering
   * @returns {Promise} - Promise resolving to product data
   */
  async getProducts(params = {}) {
    // Generate cache key based on params
    const cacheKey = `products_${JSON.stringify(params)}`;
    
    try {
      // Try to get from cache or fetch
      const response = await CacheService.getOrFetch(
        cacheKey,
        async () => {
          const response = await axiosClient.get('/products', { params });
          return response.data;
        },
        60 // Cache for 1 minute
      );
      
      // Normalize response before returning
      return this.normalizeResponse(response);
    } catch (error) {
      this.handleApiError(error, 'Failed to fetch products');
      // Return default structure instead of throwing
      return { ...this.defaultResponseStructure };
    }
  },

  /**
   * Fetch products with inventory-specific filtering
   * @param {Object} params - Query parameters for filtering
   * @returns {Promise} - Promise resolving to product data
   */
  async getInventoryProducts(params = {}) {
    // Generate cache key based on params
    const cacheKey = `inventory_products_${JSON.stringify(params)}`;
    
    try {
      // Try to get from cache or fetch
      const response = await CacheService.getOrFetch(
        cacheKey,
        async () => {
          console.log('Fetching inventory products with params:', params);
          
          // If the special endpoint doesn't exist, fall back to the regular products endpoint
          // with adapted parameters to filter by stock status
          let url = '/products';
          
          const requestParams = { ...params };
if (requestParams.stock_status) {
  if (requestParams.stock_status === 'low') {
    requestParams.stock_status = 'low';
    requestParams.track_inventory = 1;
  } else if (requestParams.stock_status === 'out') {
    requestParams.stock_status = 'out';
    requestParams.track_inventory = 1;
  }
}
const response = await axiosClient.get(url, { params: requestParams });
          return response.data;
        },
        60 // Cache for 1 minute
      );
      
      // Normalize response before returning
      return this.normalizeResponse(response);
    } catch (error) {
      this.handleApiError(error, 'Failed to fetch inventory products');
      // Return default structure instead of throwing
      return { ...this.defaultResponseStructure };
    }
  },

  /**
   * Adjust inventory for a specific product
   * @param {Number|String} productId - Product ID
   * @param {Object} adjustment - Adjustment data
   * @returns {Promise} - Promise resolving to updated product
   */
  async adjustInventory(productId, adjustment) {
    try {
      const response = await axiosClient.post(`/products/${productId}/adjust-inventory`, {
        product_id: productId,
        quantity: adjustment.quantity,
        type: adjustment.type,
        reference: adjustment.reference || `${adjustment.type} adjustment`,
        notes: adjustment.notes || ''
      });
      
      // Invalidate product cache after adjustment
      this.invalidateProductCache();
      
      return response.data;
    } catch (error) {
      this.handleApiError(error, 'Failed to adjust inventory');
      throw error;
    }
  },

  /**
   * Perform bulk inventory adjustment for multiple products
   * @param {Array} adjustments - Array of product adjustments (will be sent as 'products' field to API)
   * @returns {Promise} - Promise resolving to adjustment results
   */
  async bulkAdjustInventory(adjustments) {
    try {
      // Validate the adjustments array
      if (!Array.isArray(adjustments) || adjustments.length === 0) {
        throw new Error('No valid adjustments provided');
      }
      
      // Validate each adjustment
      adjustments.forEach(adjustment => {
        if (!adjustment.product_id) {
          throw new Error('Product ID is required for all adjustments');
        }
        if (adjustment.quantity <= 0) {
          throw new Error('Quantity must be greater than zero');
        }
        if (!['purchase', 'adjustment', 'sale', 'return'].includes(adjustment.type)) {
          throw new Error(`Invalid adjustment type: ${adjustment.type}`);
        }
      });
      
      console.log('Sending bulk adjustment payload:', { products: adjustments });
      
      const response = await axiosClient.post('/inventory/bulk-adjust', { products: adjustments });
      
      // Invalidate product cache after bulk adjustment
      this.invalidateProductCache();
      
      return response.data;
    } catch (error) {
      // Log more detailed error information
      console.error('Bulk adjustment error:', error);
      if (error.response) {
        console.error('Server response:', error.response.status, error.response.data);
      }
      
      this.handleApiError(error, 'Failed to perform bulk adjustment');
      throw error;
    }
  },

  /**
   * Fetch inventory movement history
   * @param {Object} params - Query parameters
   * @returns {Promise} - Promise resolving to movement history
   */
  async getInventoryMovements(params = {}) {
    // Generate cache key for inventory movements
    const cacheKey = `inventory_movements_${JSON.stringify(params)}`;
    
    try {
      // Try to get from cache or fetch
      const cacheTime = params.stock_status ? 60 : 300;
      const response = await CacheService.getOrFetch(
        cacheKey,
        async () => {
          const response = await axiosClient.get('/inventory/movements', { params });
          return response.data;
        },
        cacheTime // Cache for 30 seconds (shorter time since movements may change frequently)
      );
      
      // Normalize response before returning
      return this.normalizeResponse(response);
    } catch (error) {
      this.handleApiError(error, 'Failed to fetch inventory history');
      // Return default structure instead of throwing
      return { ...this.defaultResponseStructure };
    }
  },

  /**
   * Invalidate product cache when data changes
   */
  invalidateProductCache() {
    // Clear all product-related caches
    const cacheKeys = Array.from(CacheService.cache.keys())
      .filter(key => key.startsWith('products_') || key.startsWith('inventory_'));
    
    cacheKeys.forEach(key => CacheService.remove(key));
  },

  /**
   * Handle API errors consistently
   * @param {Error} error - Error object
   * @param {String} defaultMessage - Default error message
   */
  handleApiError(error, defaultMessage = 'Operation failed') {
    console.error('API Error:', error);
    
    const message = error.response?.data?.message || defaultMessage;
    
    store.commit('showToast', {
      type: 'error',
      message,
      title: 'Error'
    });
  }
};