/**
 * Enhanced utility functions for product management
 */

/**
 * Cleans a product ID by removing any colon and everything after it
 * This is useful when dealing with IDs from URL parameters
 * 
 * @param {string|number} id - The product ID to clean
 * @returns {string} Cleaned ID
 */
export function cleanId(id) {
  if (!id) return '';
  
  // Convert to string if it's a number
  const idStr = id.toString();
  
  // If the ID contains a colon (e.g., "12:1"), keep only the part before the colon
  const colonIndex = idStr.indexOf(':');
  return colonIndex > -1 ? idStr.substring(0, colonIndex) : idStr;
}

/**
 * Normalizes the published status to a boolean
 * Handles various data types that might be used to represent a boolean in data from API responses
 * 
 * @param {any} publishedStatus - The raw published status
 * @returns {boolean} Normalized boolean value
 */
export function normalizePublished(publishedStatus) {
  if (publishedStatus === null || publishedStatus === undefined) return false;
  
  // If it's already a boolean, return it
  if (typeof publishedStatus === 'boolean') return publishedStatus;
  
  // If it's a number, 1 is true, everything else is false
  if (typeof publishedStatus === 'number') return publishedStatus === 1;
  
  // If it's a string, check for various "truthy" values
  if (typeof publishedStatus === 'string') {
    const lowercaseStatus = publishedStatus.toLowerCase();
    return lowercaseStatus === 'true' || 
           lowercaseStatus === 'yes' || 
           lowercaseStatus === '1' || 
           lowercaseStatus === 'on';
  }
  
  // For anything else, convert to boolean
  return Boolean(publishedStatus);
}

/**
 * Normalizes the track inventory status to a boolean
 * 
 * @param {any} trackInventory - The raw track inventory status
 * @returns {boolean} Normalized boolean value
 */
export function normalizeTrackInventory(trackInventory) {
  return normalizePublished(trackInventory); // Reuse the same logic
}

/**
 * Formats a price value into a currency string
 * 
 * @param {number|string} price - The price to format
 * @param {string} currencyCode - The currency code (default: USD)
 * @param {string} locale - The locale to use for formatting (default: en-US)
 * @returns {string} Formatted currency string
 */
export function formatCurrency(price, currencyCode = 'USD', locale = 'en-US') {
  if (price === undefined || price === null || price === '') {
    return '$0.00';
  }
  
  const numericPrice = typeof price === 'string' ? parseFloat(price) : price;
  
  if (isNaN(numericPrice)) {
    return '$0.00';
  }
  
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency: currencyCode,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(numericPrice);
}

/**
 * Formats a date string into a localized date format
 * 
 * @param {string} dateStr - The date string to format
 * @param {string} locale - The locale to use for formatting (default: en-US)
 * @returns {string} Formatted date string
 */
export function formatDate(dateStr, locale = 'en-US') {
  if (!dateStr) return 'N/A';
  
  try {
    const date = new Date(dateStr);
    
    // Check if date is valid
    if (isNaN(date.getTime())) {
      return 'Invalid Date';
    }
    
    return new Intl.DateTimeFormat(locale, {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  } catch (error) {
    console.error('Error formatting date:', error);
    return 'Error';
  }
}

/**
 * Gets the correct image URL for a product
 * @param {string} url - The image URL or path from the server
 * @param {boolean} forceRefresh - Add cache-busting parameter
 * @returns {string|null} - Properly formatted image URL
 */
export function getImageUrl(url, forceRefresh = false) {
  if (!url) return null;
  
  console.log('Original image URL:', url);
  
  // Define the backend API URL - adjust this to match your Laravel backend
  const backendBaseUrl = 'http://localhost:8000';
  
  // Handle different development environments
  if (url) {
    // Case 1: If we have a full URL with localhost:8000 but we're running on localhost:5173 (Vite dev)
    if (url.includes('localhost:8000') && 
        (window.location.port === '5173' || window.location.hostname.includes('localhost:5173'))) {
      try {
        // Extract just the path portion (e.g., /storage/products/filename)
        const urlObj = new URL(url);
        url = urlObj.pathname;
        
        // Prepend backend base URL to the path
        url = `${backendBaseUrl}${url}`;
        console.log('Using full backend URL for image:', url);
      } catch (error) {
        console.error('Invalid URL:', url);
      }
    }
    // Case 2: Handle relative paths that start with /storage but don't have the backend URL
    else if (url.startsWith('/storage/') && 
             (window.location.port === '5173' || window.location.hostname.includes('localhost:5173'))) {
      url = `${backendBaseUrl}${url}`;
      console.log('Adding backend base URL to storage path:', url);
    }
    // Case 3: Handle paths that don't include the full domain but should
    else if (url.includes('products/') && !url.includes('http')) {
      // Ensure path starts with a slash
      if (!url.startsWith('/')) {
        url = '/storage/' + url;
      } else if (!url.startsWith('/storage/')) {
        url = '/storage' + url;
      }
      
      // Add backend base URL
      url = `${backendBaseUrl}${url}`;
      console.log('Fixed storage path with backend URL:', url);
    }
  }
  
  // Add cache-busting parameter if requested
  const finalUrl = forceRefresh ? `${url}?t=${new Date().getTime()}` : url;
  console.log('Final image URL:', finalUrl);
  
  return finalUrl;
}
/**
 * Validates a product object against required fields and constraints
 * 
 * @param {Object} product - The product object to validate
 * @returns {Object} Object with valid (boolean) and errors (object) properties
 */
export function validateProduct(product) {
  const errors = {};
  
  // Required fields
  if (!product.title || !product.title.trim()) {
    errors.title = 'Product title is required';
  } else if (product.title.length > 255) {
    errors.title = 'Product title must be less than 255 characters';
  }
  
  if (!product.price) {
    errors.price = 'Price is required';
  } else {
    const price = parseFloat(product.price);
    if (isNaN(price) || price < 0) {
      errors.price = 'Price must be a positive number';
    }
  }
  
  // Optional fields with constraints
  if (product.description && product.description.length > 1000) {
    errors.description = 'Description must be less than 1000 characters';
  }
  
  // Inventory validation
  if (product.track_inventory) {
    const quantity = parseInt(product.quantity);
    if (isNaN(quantity)) {
      errors.quantity = 'Quantity must be a valid number';
    } else if (quantity < 0) {
      errors.quantity = 'Quantity cannot be negative';
    }
    
    const reorderLevel = parseInt(product.reorder_level);
    if (isNaN(reorderLevel)) {
      errors.reorder_level = 'Reorder level must be a valid number';
    } else if (reorderLevel < 0) {
      errors.reorder_level = 'Reorder level cannot be negative';
    }
  }
  
  return {
    valid: Object.keys(errors).length === 0,
    errors
  };
}

/**
 * Safely prepares a product object for form submission
 * Handles data type conversion and validation
 * 
 * @param {Object} product - The product data to prepare
 * @param {boolean} forUpdate - Whether this is for an update operation (vs. create)
 * @returns {FormData} FormData object ready for submission
 */
/**
 * Safely prepares a product object for form submission
 * Handles data type conversion and validation
 * 
 * @param {Object} product - The product data to prepare
 * @param {boolean} forUpdate - Whether this is for an update operation (vs. create)
 * @returns {FormData} FormData object ready for submission
 */
export function prepareProductFormData(product, forUpdate = false) {
  // Ensure product is an object
  if (!product || typeof product !== 'object') {
    console.error('Invalid product object:', product);
    throw new Error('Invalid product object provided');
  }

  try {
    // Validate the product first
    const { valid, errors } = validateProduct(product);
    
    if (!valid) {
      console.warn('Product validation warnings:', errors);
      // Continue anyway but log the warnings
    }
    
    const formData = new FormData();
    
    // Handle the ID and method for updates
    if (forUpdate) {
      if (!product.id) {
        console.error('No product ID provided for update operation');
        throw new Error('Product ID is required for update operations');
      }
      
      const cleanedId = cleanId(product.id);
      formData.append('id', cleanedId);
      formData.append('_method', 'PUT');
      
      console.debug('Updating product with ID:', cleanedId);
    }
    
    // Basic fields - with explicit type conversion
    formData.append('title', String(product.title || '').trim());
    formData.append('description', String(product.description || ''));
    
    // Ensure price is a proper number
    let price = 0;
    try {
      price = parseFloat(product.price);
      if (isNaN(price)) price = 0;
    } catch (e) {
      price = 0;
    }
    formData.append('price', price.toString());
    
    // Boolean fields - convert to 0/1 strings explicitly
    const isPublished = normalizePublished(product.published);
    formData.append('published', isPublished ? '1' : '0');
    
    const trackInventory = normalizeTrackInventory(product.track_inventory);
    formData.append('track_inventory', trackInventory ? '1' : '0');
    
    // Category ID handling - convert to string and handle null/undefined
    if (product.category_id !== null && product.category_id !== undefined && product.category_id !== '') {
      formData.append('category_id', product.category_id.toString());
    } else {
      // Send empty string for null category
      formData.append('category_id', '');
    }
    
    // Inventory fields - only include if track_inventory is true
    if (trackInventory) {
      let quantity = 0;
      try {
        quantity = parseInt(product.quantity);
        if (isNaN(quantity)) quantity = 0;
      } catch (e) {
        quantity = 0;
      }
      formData.append('quantity', quantity.toString());
      
      let reorderLevel = 5;
      try {
        reorderLevel = parseInt(product.reorder_level);
        if (isNaN(reorderLevel)) reorderLevel = 5;
      } catch (e) {
        reorderLevel = 5;
      }
      formData.append('reorder_level', reorderLevel.toString());
    }
    
    // Handle image - only append if it's a File object
    if (product.image instanceof File) {
      formData.append('image', product.image);
    }
    
    return formData;
  } catch (error) {
    console.error('Error preparing product form data:', error);
    throw new Error(`Product validation failed: ${error.message}`);
  }
}

/**
 * Gets CSS class for stock status based on quantity and reorder level
 * 
 * @param {Object} product - The product object
 * @returns {string} CSS class for styling the stock status
 */
export function getStockStatusClass(product) {
  if (!product.track_inventory) return 'text-gray-500';
  
  if (product.quantity <= 0) {
    return 'text-red-600 font-medium';
  }
  
  const reorderLevel = product.reorder_level || 5;
  
  if (product.quantity <= reorderLevel) {
    return 'text-yellow-600 font-medium';
  }
  
  return 'text-green-600 font-medium';
}

function handleApiError(error) {
  console.group('API Error Details');
  console.error('Error object:', error);
  console.error('Response data:', error.response?.data);
  console.error('Status code:', error.response?.status);
  console.error('Validation errors:', error.response?.data?.errors);
  console.groupEnd();

  if (error.response && error.response.data) {
    // Handle Laravel validation errors
    if (error.response.status === 422 && error.response.data.errors) {
      // Map backend validation errors to our local error object
      const backendErrors = error.response.data.errors;
      Object.keys(backendErrors).forEach(key => {
        // Handle Laravel's array of error messages
        if (Array.isArray(backendErrors[key]) && backendErrors[key].length > 0) {
          errors[key] = backendErrors[key][0];
        } else {
          errors[key] = backendErrors[key];
        }
      });
      
      // Scroll to first error field
      setTimeout(() => {
        const firstErrorField = document.querySelector('.text-red-600');
        if (firstErrorField) {
          firstErrorField.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }, 100);
    } else if (error.response.data.message) {
      // Show general error message from server
      notifyError(error.response.data.message);
    } else {
      // Fallback error message
      notifyError('An error occurred while saving the product.');
    }
  } else {
    // Network or other error
    notifyError('Network error. Please check your connection and try again.');
  }
}