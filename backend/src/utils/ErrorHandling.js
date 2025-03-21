// src/utils/ErrorHandling.js
import store from '../store';

/**
 * Handle API error and show toast notification
 * @param {Error} error - Error object
 * @param {String} defaultMessage - Default error message
 * @returns {Object} - Error details
 */
export const handleApiError = (error, defaultMessage = 'Operation failed') => {
  console.error('API Error:', error);
  
  // Extract error message
  let message = defaultMessage;
  let validationErrors = null;
  let statusCode = null;
  
  if (error.response) {
    statusCode = error.response.status;
    
    if (error.response.data && error.response.data.message) {
      message = error.response.data.message;
    }
    
    if (error.response.data && error.response.data.errors) {
      validationErrors = error.response.data.errors;
    }
    
    // Handle specific status codes
    switch (statusCode) {
      case 401:
        // Unauthorized - clear auth state and redirect to login
        message = 'Your session has expired. Please log in again.';
        store.dispatch('auth/logout');
        break;
      
      case 403:
        // Forbidden - user doesn't have permission
        message = 'You don\'t have permission to perform this action.';
        break;
      
      case 404:
        // Not found
        message = 'The requested resource was not found.';
        break;
      
      case 422:
        // Validation error
        message = 'Please check the form for errors.';
        break;
      
      case 429:
        // Too many requests
        message = 'Too many requests. Please try again later.';
        break;
      
      case 500:
      case 502:
      case 503:
      case 504:
        // Server error
        message = 'A server error occurred. Please try again later.';
        break;
    }
  } else if (error.request) {
    // The request was made but no response was received
    message = 'No response from server. Please check your internet connection.';
  }
  
  // Show toast notification
  store.commit('showToast', {
    type: 'error',
    message,
    title: 'Error'
  });
  
  return {
    message,
    validationErrors,
    statusCode
  };
};

/**
 * Format validation errors from API response
 * @param {Object} errors - Validation errors object
 * @returns {Object} - Formatted errors object
 */
export const formatValidationErrors = (errors) => {
  if (!errors) return {};
  
  const formattedErrors = {};
  
  // Convert array of errors to single string for each field
  Object.keys(errors).forEach(field => {
    formattedErrors[field] = Array.isArray(errors[field]) ? 
      errors[field][0] : 
      errors[field];
  });
  
  return formattedErrors;
};

/**
 * Try to execute a function with error handling
 * @param {Function} fn - Function to execute
 * @param {Object} options - Options
 * @returns {Promise} - Promise resolving to result or error
 */
export const tryCatch = async (fn, options = {}) => {
  const {
    defaultErrorMessage = 'An error occurred',
    showToast = true,
    logError = true,
    rethrow = false
  } = options;
  
  try {
    return await fn();
  } catch (error) {
    if (logError) {
      console.error('Error caught in tryCatch:', error);
    }
    
    if (showToast) {
      store.commit('showToast', {
        type: 'error',
        message: error.message || defaultErrorMessage,
        title: 'Error'
      });
    }
    
    if (rethrow) {
      throw error;
    }
    
    return {
      error,
      message: error.message || defaultErrorMessage
    };
  }
};

/**
 * Create an error with a specific code
 * @param {String} message - Error message
 * @param {String} code - Error code
 * @returns {Error} - Error object with code
 */
export const createError = (message, code) => {
  const error = new Error(message);
  error.code = code;
  return error;
};

/**
 * Get error message for a specific error code
 * @param {String} code - Error code
 * @returns {String} - Error message
 */
export const getErrorMessage = (code) => {
  const errorMessages = {
    'NETWORK_ERROR': 'Network error. Please check your internet connection.',
    'UNAUTHORIZED': 'You are not authorized to perform this action.',
    'FORBIDDEN': 'You don\'t have permission to access this resource.',
    'NOT_FOUND': 'The requested resource was not found.',
    'VALIDATION_ERROR': 'Please check the form for errors.',
    'SERVER_ERROR': 'A server error occurred. Please try again later.',
    'TIMEOUT': 'The request timed out. Please try again.',
    'CONFLICT': 'This operation cannot be completed due to a conflict.',
    'BAD_REQUEST': 'The request was invalid or cannot be processed.',
    'RATE_LIMITED': 'Too many requests. Please try again later.'
  };
  
  return errorMessages[code] || 'An unexpected error occurred.';
};

/**
 * Retry a function if it fails
 * @param {Function} fn - Function to retry
 * @param {Object} options - Retry options
 * @returns {Promise} - Promise resolving to result or error
 */
export const retry = async (fn, options = {}) => {
  const {
    retries = 3,
    retryDelay = 1000,
    onRetry = null,
    shouldRetry = () => true
  } = options;
  
  let lastError;
  
  for (let attempt = 0; attempt <= retries; attempt++) {
    try {
      return await fn();
    } catch (error) {
      lastError = error;
      
      // Check if we should retry
      if (attempt >= retries || !shouldRetry(error, attempt)) {
        break;
      }
      
      // Calculate delay with exponential backoff
      const delay = retryDelay * Math.pow(2, attempt);
      
      // Call onRetry callback if provided
      if (onRetry) {
        onRetry(error, attempt, delay);
      }
      
      // Wait before next attempt
      await new Promise(resolve => setTimeout(resolve, delay));
    }
  }
  
  throw lastError;
};

/**
 * Debounce a function
 * @param {Function} fn - Function to debounce
 * @param {Number} delay - Delay in milliseconds
 * @returns {Function} - Debounced function
 */
export const debounce = (fn, delay) => {
  let timeout;
  
  return function(...args) {
    if (timeout) {
      clearTimeout(timeout);
    }
    
    timeout = setTimeout(() => {
      fn.apply(this, args);
      timeout = null;
    }, delay);
  };
};

/**
 * Throttle a function
 * @param {Function} fn - Function to throttle
 * @param {Number} limit - Limit in milliseconds
 * @returns {Function} - Throttled function
 */
export const throttle = (fn, limit) => {
  let inThrottle;
  
  return function(...args) {
    if (!inThrottle) {
      fn.apply(this, args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
};