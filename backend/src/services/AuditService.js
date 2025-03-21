import axiosClient from '../axios';
import store from '../store';

export default {
  /**
   * Log audit event
   * @param {String} action - Action performed
   * @param {Object} details - Action details
   * @returns {Promise} - Promise resolving to log response
   */
  async log(action, details = {}) {
    try {
      const user = store.state.auth.user;
      
      const auditLog = {
        action,
        details,
        user_id: user ? user.id : null,
        user_name: user ? user.name : 'System',
        ip_address: window.userIp || null, // IP would be set by backend
        timestamp: new Date().toISOString()
      };
      
      const response = await axiosClient.post('/audit-logs', auditLog);
      return response.data;
    } catch (error) {
      console.error('Error logging audit event:', error);
      // Don't throw - audit logging should not interrupt normal operation
      return null;
    }
  },
  
  /**
   * Get audit logs
   * @param {Object} params - Query parameters
   * @returns {Promise} - Promise resolving to logs data
   */
  async getLogs(params = {}) {
    try {
      const response = await axiosClient.get('/audit-logs', { params });
      return response.data;
    } catch (error) {
      console.error('Error fetching audit logs:', error);
      throw error;
    }
  },
  
  /**
   * Log inventory adjustment
   * @param {Object} adjustment - Adjustment details
   * @returns {Promise} - Promise resolving to log response
   */
  async logInventoryAdjustment(adjustment) {
    return this.log('inventory_adjustment', {
      product_id: adjustment.product_id,
      product_name: adjustment.product_name || 'Unknown Product',
      quantity: adjustment.quantity,
      previous_quantity: adjustment.previous_quantity,
      new_quantity: adjustment.new_quantity,
      type: adjustment.type,
      reference: adjustment.reference
    });
  },
  
  /**
   * Log bulk inventory adjustment
   * @param {Object} bulkAdjustment - Bulk adjustment details
   * @returns {Promise} - Promise resolving to log response
   */
  async logBulkInventoryAdjustment(bulkAdjustment) {
    return this.log('bulk_inventory_adjustment', {
      product_count: bulkAdjustment.product_count,
      adjustment_type: bulkAdjustment.adjustment_type,
      quantity: bulkAdjustment.quantity,
      reference: bulkAdjustment.reference
    });
  },
  
  /**
   * Log product creation
   * @param {Object} product - Product details
   * @returns {Promise} - Promise resolving to log response
   */
  async logProductCreation(product) {
    return this.log('product_created', {
      product_id: product.id,
      product_name: product.title,
      sku: product.sku
    });
  },
  
  /**
   * Log product update
   * @param {Object} product - Product details
   * @returns {Promise} - Promise resolving to log response
   */
  async logProductUpdate(product) {
    return this.log('product_updated', {
      product_id: product.id,
      product_name: product.title,
      sku: product.sku,
      changes: product.changes // Object with old and new values
    });
  }
};