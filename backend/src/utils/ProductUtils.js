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
 * Generate full URL for product images
 * @param {string} imagePath - The image path or filename
 * @returns {string|null} - The complete image URL or null if no image
 */
export function getImageUrl(imagePath) {
  if (!imagePath) return null;
  
  // If it's already a full URL, return it as is
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  // For relative paths, construct the full URL
  // Adjust the base URL according to your environment
  const baseUrl = window.location.origin;
  return `${baseUrl}/storage/products/${imagePath}`;
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
  
  // Handle image correctly for both new uploads and updates
  if (product.image instanceof File) {
    // New file being uploaded
    formData.append('image', product.image);
    console.log('Uploading new image file:', product.image.name);
  } else if (isUpdate && product.image === null) {
    // Explicitly remove the image
    formData.append('_remove_image', '1');
  }
  // If no new image and not explicitly removing, don't append anything
  // This indicates to the server to keep the existing image
  
  return formData;
}

  