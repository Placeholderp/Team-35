// src/utils/InventoryUtils.js

/**
 * Get stock status class based on inventory levels
 * @param {Object} product - The product object
 * @returns {string} - CSS class name
 */
export function getStockStatusClass(product) {
    if (!product.track_inventory) return 'text-gray-500';
    if (product.quantity <= 0) return 'text-red-600';
    if (product.quantity <= product.reorder_level) return 'text-yellow-600';
    return 'text-green-600';
  }
  
  /**
   * Get human-readable stock status text
   * @param {Object} product - The product object
   * @returns {string} - Status text
   */
  export function getStockStatusText(product) {
    if (!product.track_inventory) return 'Not Tracked';
    if (product.quantity <= 0) return 'Out of Stock';
    if (product.quantity <= product.reorder_level) return 'Low Stock';
    return 'In Stock';
  }
  
  /**
   * Get badge class for stock status
   * @param {Object} product - The product object
   * @returns {string} - CSS class names for badge
   */
  export function getStockStatusBadgeClass(product) {
    if (!product.track_inventory) return 'bg-gray-100 text-gray-800';
    if (product.quantity <= 0) return 'bg-red-100 text-red-800';
    if (product.quantity <= product.reorder_level) return 'bg-yellow-100 text-yellow-800';
    return 'bg-green-100 text-green-800';
  }
  
  /**
   * Format stock movement type to human-readable text
   * @param {string} type - Movement type
   * @returns {string} - Formatted text
   */
  export function formatMovementType(type) {
    if (!type) return 'Unknown';
    
    const typeMap = {
      'purchase': 'Purchase',
      'sale': 'Sale',
      'adjustment': 'Adjustment',
      'return': 'Return',
      'initial': 'Initial Stock'
    };
    
    return typeMap[type] || type.charAt(0).toUpperCase() + type.slice(1);
  }
  
  /**
   * Get class for stock movement badge
   * @param {string} type - Movement type
   * @returns {string} - CSS class names
   */
  export function getMovementTypeBadgeClass(type) {
    const classMap = {
      'purchase': 'bg-green-100 text-green-800',
      'sale': 'bg-blue-100 text-blue-800',
      'adjustment': 'bg-purple-100 text-purple-800',
      'return': 'bg-yellow-100 text-yellow-800',
      'initial': 'bg-gray-100 text-gray-800'
    };
    
    return classMap[type] || 'bg-gray-100 text-gray-800';
  }
  
  /**
   * Calculate days of inventory based on average daily sales
   * @param {Object} product - The product object
   * @param {number} avgDailySales - Average daily sales
   * @returns {number|string} - Days of inventory or 'N/A'
   */
  export function calculateDaysOfInventory(product, avgDailySales) {
    if (!product.track_inventory || avgDailySales <= 0) return 'N/A';
    
    return Math.round(product.quantity / avgDailySales);
  }
  
  /**
   * Calculate reorder quantity based on target days of inventory
   * @param {Object} product - The product object
   * @param {number} avgDailySales - Average daily sales
   * @param {number} targetDays - Target days of inventory
   * @param {number} leadTimeDays - Lead time in days
   * @returns {number} - Recommended reorder quantity
   */
  export function calculateReorderQuantity(product, avgDailySales, targetDays, leadTimeDays) {
    if (!product.track_inventory || avgDailySales <= 0) return 0;
    
    // Calculate ideal inventory level
    const targetInventory = avgDailySales * targetDays;
    
    // Account for lead time
    const bufferStock = avgDailySales * leadTimeDays;
    
    // Calculate reorder quantity
    const reorderQty = Math.max(0, targetInventory + bufferStock - product.quantity);
    
    // Round up to nearest whole number
    return Math.ceil(reorderQty);
  }
  
  /**
   * Format quantity change with sign (+ or -)
   * @param {number} quantity - Quantity to format
   * @returns {string} - Formatted quantity
   */
  export function formatQuantityChange(quantity) {
    if (quantity > 0) return `+${quantity}`;
    return quantity.toString();
  }
  
  /**
   * Get appropriate CSS class for quantity changes
   * @param {number} quantity - Quantity
   * @returns {string} - CSS class
   */
  export function getQuantityChangeClass(quantity) {
    if (quantity > 0) return 'text-green-600';
    if (quantity < 0) return 'text-red-600';
    return 'text-gray-500';
  }
  
  /**
   * Format number as percentage
   * @param {number} value - Number to format
   * @param {number} decimals - Number of decimal places
   * @returns {string} - Formatted percentage
   */
  export function formatPercentage(value, decimals = 1) {
    if (isNaN(value)) return 'N/A';
    return `${(value * 100).toFixed(decimals)}%`;
  }
  
  /**
   * Format date as relative time (e.g., "2 days ago")
   * @param {string} dateString - ISO date string
   * @returns {string} - Formatted relative date
   */
  export function formatRelativeDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'just now';
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}h ago`;
    
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays}d ago`;
    
    // For older dates, return formatted date
    return date.toLocaleDateString();
  }
  
  /**
   * Convert a value to a SKU-friendly string
   * @param {string} value - String to convert
   * @returns {string} - SKU-friendly string
   */
  export function generateSkuString(value) {
    if (!value) return '';
    
    // Remove special characters and spaces
    return value
      .toUpperCase()
      .replace(/[^A-Z0-9]/g, '')
      .substring(0, 8);
  }