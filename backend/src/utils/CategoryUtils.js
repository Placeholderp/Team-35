/**
 * Utility functions for category management
 */

/**
 * Normalizes the active status to a boolean
 * @param {any} activeStatus - The raw active status
 * @returns {boolean} Normalized boolean value
 */
export function normalizeActiveStatus(activeStatus) {
  if (activeStatus === null || activeStatus === undefined) return false;
  
  // If it's already a boolean, return it
  if (typeof activeStatus === 'boolean') return activeStatus;
  
  // If it's a number, 1 is true, everything else is false
  if (typeof activeStatus === 'number') return activeStatus === 1;
  
  // If it's a string, check for various "truthy" values
  if (typeof activeStatus === 'string') {
    const lowercaseStatus = activeStatus.toLowerCase();
    return lowercaseStatus === 'true' || 
           lowercaseStatus === 'yes' || 
           lowercaseStatus === '1' || 
           lowercaseStatus === 'on' ||
           lowercaseStatus === 'active';
  }
  
  // For anything else, convert to boolean
  return Boolean(activeStatus);
}

/**
 * Prepares a category object for form submission
 * @param {Object} category - The category data
 * @param {boolean} forUpdate - Whether it's for update operation
 * @returns {FormData} FormData object for submission
 */
export function prepareCategoryFormData(category, forUpdate = false) {
  if (!category || typeof category !== 'object') {
    console.error('Invalid category object:', category);
    throw new Error('Invalid category object provided');
  }

  const formData = new FormData();
  
  // Basic fields
  formData.append('name', String(category.name || '').trim());
  formData.append('description', String(category.description || ''));
  formData.append('is_active', normalizeActiveStatus(category.is_active) ? '1' : '0');
  
  // Sort order (default to 0)
  let sortOrder = 0;
  try {
    sortOrder = parseInt(category.sort_order);
    if (isNaN(sortOrder)) sortOrder = 0;
  } catch (e) {
    sortOrder = 0;
  }
  formData.append('sort_order', sortOrder.toString());
  
  // Handle image upload
  if (category.image instanceof File) {
    formData.append('image', category.image);
  }
  
  // Handle image removal
  if (category.remove_image) {
    formData.append('remove_image', '1');
  }
  
  // For updating existing categories
  if (forUpdate) {
    if (!category.id) {
      console.error('No category ID provided for update operation');
      throw new Error('Category ID is required for update operations');
    }
    
    formData.append('id', category.id.toString());
    formData.append('_method', 'PUT');
  }
  
  return formData;
}