// Create a new file: src/utils/orderStatusUtils.js

/**
 * Standardized utilities for handling order statuses
 */

// All possible order statuses
export const ORDER_STATUSES = [
    'pending',
    'processing',
    'paid',
    'shipped',
    'delivered',
    'completed',
    'cancelled',
    'refunded'
  ];
  
  // Map numeric codes to status strings
  export const STATUS_CODE_MAP = {
    '1': 'pending',
    '2': 'processing',
    '3': 'paid',
    '4': 'shipped',
    '5': 'delivered',
    '6': 'completed',
    '7': 'cancelled',
    '8': 'refunded'
  };
  
  // Status display styling
  export const STATUS_STYLES = {
    'pending': {
      bgClass: 'bg-yellow-100 text-yellow-800',
      dotClass: 'bg-yellow-500'
    },
    'processing': {
      bgClass: 'bg-indigo-100 text-indigo-800',
      dotClass: 'bg-indigo-500'
    },
    'paid': {
      bgClass: 'bg-green-100 text-green-800',
      dotClass: 'bg-green-500'
    },
    'shipped': {
      bgClass: 'bg-blue-100 text-blue-800',
      dotClass: 'bg-blue-500'
    },
    'delivered': {
      bgClass: 'bg-teal-100 text-teal-800',
      dotClass: 'bg-teal-500'
    },
    'completed': {
      bgClass: 'bg-green-100 text-green-800',
      dotClass: 'bg-green-500'
    },
    'cancelled': {
      bgClass: 'bg-red-100 text-red-800',
      dotClass: 'bg-red-500'
    },
    'refunded': {
      bgClass: 'bg-gray-100 text-gray-800',
      dotClass: 'bg-gray-500'
    },
    // Default styling
    'default': {
      bgClass: 'bg-gray-100 text-gray-800',
      dotClass: 'bg-gray-500'
    }
  };
  
  /**
   * Get the display style for a status
   * @param {String} status Status code or string
   * @returns {Object} Class names for styling
   */
  export function getStatusStyle(status) {
    if (!status) return STATUS_STYLES.default;
    
    // Convert status code to string if needed
    const statusStr = typeof status === 'number' ? STATUS_CODE_MAP[status.toString()] : status;
    
    // Return style config or default
    return STATUS_STYLES[statusStr] || STATUS_STYLES.default;
  }
  
  /**
   * Cleans and normalizes an order status
   * @param {String|Number} status The status to clean
   * @returns {String} Cleaned status value
   */
  export function normalizeStatus(status) {
    if (!status) return '';
    
    // Convert to string
    const statusStr = status.toString();
    
    // Handle colon format (e.g. ":1")
    if (statusStr.includes(':')) {
      const parts = statusStr.split(':');
      const statusCode = parts.length > 1 ? parts[1] : parts[0];
      return STATUS_CODE_MAP[statusCode] || '';
    }
    
    // Handle numeric status
    if (/^\d+$/.test(statusStr)) {
      return STATUS_CODE_MAP[statusStr] || '';
    }
    
    // It's already a string status
    return statusStr.toLowerCase();
  }
  
  /**
   * Format a status for display (capitalized)
   * @param {String} status The status to format
   * @returns {String} Formatted status for display
   */
  export function formatStatusLabel(status) {
    const normalized = normalizeStatus(status);
    if (!normalized) return '';
    return normalized.charAt(0).toUpperCase() + normalized.slice(1);
  }