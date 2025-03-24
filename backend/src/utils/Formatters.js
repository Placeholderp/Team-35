// src/utils/Formatters.js

/**
 * Format date to string
 * @param {Date|String|Number} date - Date to format
 * @param {String} format - Date format pattern
 * @returns {String} - Formatted date string
 */
export function formatDate(date, format = 'MM/DD/YYYY') {
  if (!date) return '';
  
  // Convert to Date object if not already
  const dateObj = date instanceof Date ? date : new Date(date);
  
  // Check if date is valid
  if (isNaN(dateObj.getTime())) return '';
  
  const year = dateObj.getFullYear();
  const month = dateObj.getMonth() + 1;
  const day = dateObj.getDate();
  const hours = dateObj.getHours();
  const minutes = dateObj.getMinutes();
  const seconds = dateObj.getSeconds();
  
  // Pad single digits with leading zero
  const pad = (num) => num.toString().padStart(2, '0');
  
  // Month names for formatting
  const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  
  const monthShortNames = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
  ];
  
  // Day names for formatting
  const dayNames = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
  ];
  
  const dayShortNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  
  // Replace tokens in format string
  return format
    .replace(/YYYY/g, year.toString())
    .replace(/YY/g, year.toString().slice(-2))
    .replace(/MMMM/g, monthNames[month - 1])
    .replace(/MMM/g, monthShortNames[month - 1])
    .replace(/MM/g, pad(month))
    .replace(/M/g, month.toString())
    .replace(/DD/g, pad(day))
    .replace(/D/g, day.toString())
    .replace(/dddd/g, dayNames[dateObj.getDay()])
    .replace(/ddd/g, dayShortNames[dateObj.getDay()])
    .replace(/HH/g, pad(hours))
    .replace(/H/g, hours.toString())
    .replace(/hh/g, pad(hours > 12 ? hours - 12 : hours === 0 ? 12 : hours))
    .replace(/h/g, (hours > 12 ? hours - 12 : hours === 0 ? 12 : hours).toString())
    .replace(/mm/g, pad(minutes))
    .replace(/m/g, minutes.toString())
    .replace(/ss/g, pad(seconds))
    .replace(/s/g, seconds.toString())
    .replace(/a/g, hours >= 12 ? 'pm' : 'am')
    .replace(/A/g, hours >= 12 ? 'PM' : 'AM');
}

/**
* Format date and time
* @param {Date|String|Number} date - Date to format
* @param {String} format - Optional custom format
* @returns {String} - Formatted date and time string
*/
export function formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
return formatDate(date, format);
}

/**
* Format time only
* @param {Date|String|Number} date - Date to format
* @param {String} format - Optional custom format
* @returns {String} - Formatted time string
*/
export function formatTime(date, format = 'hh:mm A') {
return formatDate(date, format);
}

/**
 * Format currency value
 * @param {Number|String} value - Value to format
 * @param {String} locale - Locale for formatting
 * @param {Object} options - Formatting options
 * @returns {String} - Formatted currency string
 */
export function formatCurrency(value, locale = 'en-US', options = {}) {
  if (value === null || value === undefined) return '';
  
  // Convert string to number if needed
  const numValue = typeof value === 'string' ? parseFloat(value) : value;
  
  // Check if value is a valid number
  if (isNaN(numValue)) return '';
  
  // Default options
  const defaultOptions = {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  };
  
  // Merge options
  const formatterOptions = { ...defaultOptions, ...options };
  
  try {
    return new Intl.NumberFormat(locale, formatterOptions).format(numValue);
  } catch (error) {
    console.error('Error formatting currency:', error);
    return `$${numValue.toFixed(2)}`;
  }
}

/**
 * Format number with locale formatting
 * @param {Number|String} value - Value to format
 * @param {String} locale - Locale for formatting
 * @param {Object} options - Formatting options
 * @returns {String} - Formatted number string
 */
export function formatNumber(value, locale = 'en-US', options = {}) {
  if (value === null || value === undefined) return '';
  
  // Convert string to number if needed
  const numValue = typeof value === 'string' ? parseFloat(value) : value;
  
  // Check if value is a valid number
  if (isNaN(numValue)) return '';
  
  // Default options
  const defaultOptions = {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  };
  
  // Merge options
  const formatterOptions = { ...defaultOptions, ...options };
  
  try {
    return new Intl.NumberFormat(locale, formatterOptions).format(numValue);
  } catch (error) {
    console.error('Error formatting number:', error);
    return numValue.toString();
  }
}

/**
 * Format percentage value
 * @param {Number|String} value - Value to format
 * @param {String} locale - Locale for formatting
 * @param {Object} options - Formatting options
 * @returns {String} - Formatted percentage string
 */
export function formatPercent(value, locale = 'en-US', options = {}) {
  if (value === null || value === undefined) return '';
  
  // Convert string to number if needed
  const numValue = typeof value === 'string' ? parseFloat(value) : value;
  
  // Check if value is a valid number
  if (isNaN(numValue)) return '';
  
  // Default options
  const defaultOptions = {
    style: 'percent',
    minimumFractionDigits: 1,
    maximumFractionDigits: 1
  };
  
  // Merge options
  const formatterOptions = { ...defaultOptions, ...options };
  
  try {
    return new Intl.NumberFormat(locale, formatterOptions).format(numValue / 100);
  } catch (error) {
    console.error('Error formatting percentage:', error);
    return `${numValue.toFixed(1)}%`;
  }
}

/**
 * Format file size in bytes to human-readable format
 * @param {Number} bytes - Size in bytes
 * @param {Number} decimals - Number of decimal places
 * @returns {String} - Formatted file size
 */
export function formatFileSize(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

/**
 * Format time duration in seconds to human-readable format
 * @param {Number} seconds - Duration in seconds
 * @param {Boolean} showSeconds - Whether to show seconds
 * @returns {String} - Formatted duration
 */
export function formatDuration(seconds, showSeconds = true) {
  if (seconds === null || seconds === undefined) return '';
  
  // Convert string to number if needed
  const numSeconds = typeof seconds === 'string' ? parseInt(seconds, 10) : seconds;
  
  // Check if value is a valid number
  if (isNaN(numSeconds)) return '';
  
  const hours = Math.floor(numSeconds / 3600);
  const minutes = Math.floor((numSeconds % 3600) / 60);
  const remainingSeconds = numSeconds % 60;
  
  // Format parts
  const parts = [];
  
  if (hours > 0) {
    parts.push(`${hours}h`);
  }
  
  if (minutes > 0 || (hours > 0 && showSeconds)) {
    parts.push(`${minutes}m`);
  }
  
  if (showSeconds && (remainingSeconds > 0 || parts.length === 0)) {
    parts.push(`${remainingSeconds}s`);
  }
  
  return parts.join(' ');
}

/**
 * Format relative time (e.g., "2 hours ago")
 * @param {Date|String|Number} date - Date to format
 * @returns {String} - Formatted relative time
 */
export function formatRelativeTime(date) {
  if (!date) return '';
  
  // Convert to Date object if not already
  const dateObj = date instanceof Date ? date : new Date(date);
  
  // Check if date is valid
  if (isNaN(dateObj.getTime())) return '';
  
  const now = new Date();
  const diffMs = now - dateObj;
  const diffSec = Math.round(diffMs / 1000);
  const diffMin = Math.round(diffSec / 60);
  const diffHour = Math.round(diffMin / 60);
  const diffDay = Math.round(diffHour / 24);
  const diffMonth = Math.round(diffDay / 30);
  const diffYear = Math.round(diffMonth / 12);
  
  if (diffSec < 60) {
    return diffSec <= 1 ? 'just now' : `${diffSec} seconds ago`;
  } else if (diffMin < 60) {
    return diffMin === 1 ? '1 minute ago' : `${diffMin} minutes ago`;
  } else if (diffHour < 24) {
    return diffHour === 1 ? '1 hour ago' : `${diffHour} hours ago`;
  } else if (diffDay < 30) {
    return diffDay === 1 ? 'yesterday' : `${diffDay} days ago`;
  } else if (diffMonth < 12) {
    return diffMonth === 1 ? '1 month ago' : `${diffMonth} months ago`;
  } else {
    return diffYear === 1 ? '1 year ago' : `${diffYear} years ago`;
  }
}

/**
 * Truncate text with ellipsis
 * @param {String} text - Text to truncate
 * @param {Number} maxLength - Maximum length
 * @returns {String} - Truncated text
 */
export function truncateText(text, maxLength = 50) {
  if (!text) return '';
  
  if (text.length <= maxLength) return text;
  
  return text.substring(0, maxLength) + '...';
}

/**
 * Format phone number
 * @param {String} phoneNumber - Phone number to format
 * @param {String} format - Format pattern
 * @returns {String} - Formatted phone number
 */
export function formatPhoneNumber(phoneNumber, format = '(XXX) XXX-XXXX') {
  if (!phoneNumber) return '';
  
  // Remove non-numeric characters
  const cleaned = phoneNumber.replace(/\D/g, '');
  
  // Replace X with digits
  let formatted = format;
  for (let i = 0; i < cleaned.length && formatted.includes('X'); i++) {
    formatted = formatted.replace('X', cleaned.charAt(i));
  }
  
  // Remove any remaining X placeholders
  formatted = formatted.replace(/X+/g, '');
  
  return formatted;
}