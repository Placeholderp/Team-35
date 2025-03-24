import { isValidNumber, isValidDate } from '../utils/Commonvalidators';
import store from '../store';

/**
 * Validate user input for XSS protection
 * @param {String} input - User input
 * @returns {Boolean} - True if input is safe
 */
export function isSafeInput(input) {
  if (typeof input !== 'string') return true;
  
  // Check for potentially dangerous HTML or script tags
  const dangerousPatterns = [
    /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
    /<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,
    /<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/gi,
    /<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi,
    /javascript:/gi,
    /on\w+=/gi
  ];
  
  return !dangerousPatterns.some(pattern => pattern.test(input));
}

/**
 * Sanitize user input
 * @param {String} input - User input
 * @returns {String} - Sanitized input
 */
export function sanitizeInput(input) {
  if (typeof input !== 'string') return input;
  
  // Replace potentially dangerous characters
  return input
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/\//g, '&#x2F;');
}

/**
 * Validate inventory adjustment form data (comprehensive version)
 * @param {Object} data - Form data
 * @returns {Object} - Validation result
 */
export function validateInventoryAdjustment(data) {
  const errors = {};
  
  // Validate data exists
  if (!data) {
    errors._form = 'No adjustment data provided';
    return {
      isValid: false,
      errors
    };
  }
  
  // Existing validation...
  
  // Special handling for quantity modes
  if (data.quantity !== undefined && data.quantity !== null && isValidNumber(data.quantity)) {
    if (data.quantityMode === 'set') {
      // For "set exact quantity" mode, allow zero
      if (data.quantity < 0) {
        errors.quantity = 'Quantity cannot be negative when setting exact quantity';
      }
    } else {
      // For add/remove mode
      if (data.quantity <= 0 && data.type !== 'adjustment') {
        errors.quantity = 'Quantity must be greater than zero';
      }
    }
  }
  
  // Validate reference
  if (data.reference && !isSafeInput(data.reference)) {
    errors.reference = 'Reference contains invalid characters';
  }
  
  // Validate notes
  if (data.notes && !isSafeInput(data.notes)) {
    errors.notes = 'Notes contain invalid characters';
  }
  
  return {
    isValid: Object.keys(errors).length === 0,
    errors
  };
}

/**
 * Validate bulk inventory adjustment
 * @param {Object} bulkAdjustment - Bulk adjustment data
 * @returns {Object} - Validation result
 */
export function validateBulkAdjustment(bulkAdjustment) {
  const errors = {};
  
  if (!bulkAdjustment.selectedProductIds || bulkAdjustment.selectedProductIds.length === 0) {
    errors.selectedProductIds = 'Please select at least one product';
  }
  
  if (!bulkAdjustment.adjustmentType) {
    errors.adjustmentType = 'Adjustment type is required';
  }
  
  if (bulkAdjustment.quantity === undefined || bulkAdjustment.quantity === null) {
    errors.quantity = 'Quantity is required';
  } else if (isNaN(bulkAdjustment.quantity) || bulkAdjustment.quantity <= 0) {
    errors.quantity = 'Quantity must be a positive number';
  }
  
  // Validate reference (if present)
  if (bulkAdjustment.reference && !isSafeInput(bulkAdjustment.reference)) {
    errors.reference = 'Reference contains invalid characters';
  }
  
  // Validate notes (if present)
  if (bulkAdjustment.notes && !isSafeInput(bulkAdjustment.notes)) {
    errors.notes = 'Notes contain invalid characters';
  }
  
  return {
    isValid: Object.keys(errors).length === 0,
    errors
  };
}

/**
 * Validate product form data
 * @param {Object} data - Form data
 * @returns {Object} - Validation result
 */
export function validateProductForm(data) {
  const errors = {};
  
  // Validate title
  if (!data.title) {
    errors.title = 'Please enter a product title';
  } else if (data.title.length < 3) {
    errors.title = 'Title must be at least 3 characters';
  } else if (!isSafeInput(data.title)) {
    errors.title = 'Title contains invalid characters';
  }
  
  // Validate SKU
  if (data.sku && !isSafeInput(data.sku)) {
    errors.sku = 'SKU contains invalid characters';
  }
  
  // Validate price
  if (data.price === undefined || data.price === null) {
    errors.price = 'Please enter a price';
  } else if (!isValidNumber(data.price)) {
    errors.price = 'Price must be a valid number';
  } else if (data.price < 0) {
    errors.price = 'Price cannot be negative';
  }
  
  // Validate quantity
  if (data.track_inventory) {
    if (data.quantity === undefined || data.quantity === null) {
      errors.quantity = 'Please enter a quantity';
    } else if (!isValidNumber(data.quantity)) {
      errors.quantity = 'Quantity must be a valid number';
    } else if (data.quantity < 0) {
      errors.quantity = 'Quantity cannot be negative';
    }
  }
  
  // Validate reorder level
  if (data.track_inventory && data.reorder_level !== undefined && data.reorder_level !== null) {
    if (!isValidNumber(data.reorder_level)) {
      errors.reorder_level = 'Reorder level must be a valid number';
    } else if (data.reorder_level < 0) {
      errors.reorder_level = 'Reorder level cannot be negative';
    }
  }
  
  return {
    isValid: Object.keys(errors).length === 0,
    errors
  };
}

/**
 * Handle API errors and show toast notifications
 * @param {Error} error - Error object from API call
 * @param {String} defaultMessage - Default error message
 * @returns {Object} - Error details
 */
export function handleApiError(error, defaultMessage = 'Operation failed') {
  console.error('API Error:', error);
  
  // Extract error message
  let message = defaultMessage;
  let validationErrors = null;
  
  if (error.response) {
    if (error.response.data && error.response.data.message) {
      message = error.response.data.message;
    }
    
    if (error.response.data && error.response.data.errors) {
      validationErrors = error.response.data.errors;
    }
  }
  
  // Show toast notification
  store.commit('showToast', {
    type: 'error',
    message,
    title: 'Error'
  });
  
  return {
    message,
    validationErrors
  };
}

/**
 * Simple validation for checking if value is empty
 * @param {*} value - Value to check
 * @returns {Boolean} - True if value is not empty
 */
export function isNotEmpty(value) {
  if (value === undefined || value === null) return false;
  if (typeof value === 'string') return value.trim().length > 0;
  if (Array.isArray(value)) return value.length > 0;
  return true;
}