/**
 * Check if a value is a valid number
 * @param {*} value - Value to check
 * @returns {Boolean} - True if value is a valid number
 */
export function isValidNumber(value) {
    // Check if value is defined and not null
    if (value === undefined || value === null) return false;
    
    // Convert to number if it's a string
    const num = typeof value === 'string' ? parseFloat(value) : value;
    
    // Check if it's a number and not NaN
    return typeof num === 'number' && !isNaN(num) && isFinite(num);
  }
  
  /**
   * Check if a value is a valid date
   * @param {*} value - Value to check (Date object or string)
   * @returns {Boolean} - True if value is a valid date
   */
  export function isValidDate(value) {
    if (value === undefined || value === null) return false;
    
    // If it's already a Date object
    if (value instanceof Date) {
      return !isNaN(value.getTime());
    }
    
    // If it's a string or number, try to create a Date
    const date = new Date(value);
    return !isNaN(date.getTime());
  }
  
  /**
   * Check if a value is a valid email address
   * @param {String} value - Email to validate
   * @returns {Boolean} - True if email is valid
   */
  export function isValidEmail(value) {
    if (typeof value !== 'string') return false;
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(value);
  }
  
  /**
   * Check if a string meets minimum length requirement
   * @param {String} value - String to check
   * @param {Number} minLength - Minimum required length
   * @returns {Boolean} - True if string meets minimum length
   */
  export function meetsMinLength(value, minLength) {
    if (typeof value !== 'string') return false;
    return value.length >= minLength;
  }
  
  /**
   * Check if a string meets maximum length requirement
   * @param {String} value - String to check
   * @param {Number} maxLength - Maximum allowed length
   * @returns {Boolean} - True if string meets maximum length
   */
  export function meetsMaxLength(value, maxLength) {
    if (typeof value !== 'string') return false;
    return value.length <= maxLength;
  }
  
  /**
   * Check if a value is within a numeric range
   * @param {Number} value - Value to check
   * @param {Number} min - Minimum value
   * @param {Number} max - Maximum value
   * @returns {Boolean} - True if value is within range
   */
  export function isInRange(value, min, max) {
    if (!isValidNumber(value)) return false;
    return value >= min && value <= max;
  }