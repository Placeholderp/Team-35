// src/utils/ProductUtils.js

/**
 * Clean an ID by removing any colon and subsequent characters
 * @param {string|number} id - The ID to clean
 * @returns {string} - The cleaned ID
 */
export function cleanId(id) {
    if (id === undefined || id === null) return '';
    
    // Convert to string and split on colon
    const idStr = String(id);
    const parts = idStr.split(':');
    
    // Return only the first part (before any colon)
    return parts[0];
  }
  
  /**
   * Normalize a published value to a boolean
   * @param {any} value - The value to normalize
   * @returns {boolean} - The normalized boolean value
   */
  export function normalizePublished(value) {
    if (value === undefined || value === null) {
      return false;
    }
    if (typeof value === 'boolean') {
      return value;
    }
    if (typeof value === 'number') {
      return value === 1;
    }
    if (typeof value === 'string') {
      return value === '1' || value.toLowerCase() === 'true';
    }
    return false;
  }
  
  /**
   * Format a currency value
   * @param {number|string} value - The value to format
   * @returns {string} - The formatted currency string
   */
  export function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(value || 0);
  }
  
  /**
   * Format a date string
   * @param {string} dateString - The date string to format
   * @returns {string} - The formatted date string
   */
  export function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  }
  
  /**
   * Prepare form data for product API requests
   * @param {Object} product - The product object
   * @param {boolean} isUpdate - Whether this is an update operation
   * @returns {FormData} - FormData ready for API submission
   */
  export function prepareProductFormData(product, isUpdate = false) {
    const formData = new FormData();
    
    if (isUpdate) {
      const id = cleanId(product.id);
      formData.append('id', id);
      formData.append('_method', 'PUT');
    }
    
    formData.append('title', product.title || '');
    formData.append('description', product.description || '');
    formData.append('price', product.price || 0);
    formData.append('published', normalizePublished(product.published) ? 1 : 0);
    
    // Only append image if it's a file (new upload)
    if (product.image instanceof File) {
      formData.append('image', product.image);
    }
    
    // Preserve image URL if available
    if (product.image_url) {
      formData.append('image_url', product.image_url);
    }
    
    return formData;
  }