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
    
    // Parent ID (if provided)
    if (category.parent_id) {
      formData.append('parent_id', category.parent_id.toString());
    }
    
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
  
  /**
   * Build a hierarchical tree from flat category list
   * @param {Array} categories - Flat list of categories
   * @param {number|null} parentId - Parent ID for current level
   * @returns {Array} Hierarchical tree of categories
   */
  export function buildCategoryTree(categories, parentId = null) {
    return categories
      .filter(category => category.parent_id === parentId)
      .map(category => ({
        ...category,
        children: buildCategoryTree(categories, category.id)
      }));
  }
  
  /**
   * Find a category by ID in a hierarchical tree
   * @param {Array} tree - Hierarchical tree of categories
   * @param {number} id - Category ID to find
   * @returns {Object|null} Found category or null
   */
  export function findCategoryInTree(tree, id) {
    for (const category of tree) {
      if (category.id === id) {
        return category;
      }
      
      if (category.children && category.children.length > 0) {
        const found = findCategoryInTree(category.children, id);
        if (found) return found;
      }
    }
    
    return null;
  }
  
  /**
   * Get the full path of a category (breadcrumb)
   * @param {Array} categories - Flat list of all categories
   * @param {number} categoryId - Category ID to get path for
   * @returns {Array} Array of category objects forming the path
   */
  export function getCategoryPath(categories, categoryId) {
    const path = [];
    let currentId = categoryId;
    
    while (currentId) {
      const category = categories.find(c => c.id === currentId);
      if (!category) break;
      
      path.unshift(category);
      currentId = category.parent_id;
    }
    
    return path;
  }
  
  /**
   * Get a formatted breadcrumb string for a category
   * @param {Array} categories - Flat list of all categories
   * @param {number} categoryId - Category ID to get breadcrumb for
   * @param {string} separator - Separator character (default: ">")
   * @returns {string} Formatted breadcrumb string
   */
  export function getCategoryBreadcrumb(categories, categoryId, separator = ' > ') {
    const path = getCategoryPath(categories, categoryId);
    return path.map(category => category.name).join(separator);
  }
  
  /**
   * Get all category IDs in a branch (including the root)
   * @param {Array} categories - Flat list of all categories
   * @param {number} rootId - Root category ID
   * @returns {Array} Array of category IDs in the branch
   */
  export function getCategoryBranchIds(categories, rootId) {
    const ids = [rootId];
    
    const getChildrenIds = (parentId) => {
      const children = categories.filter(c => c.parent_id === parentId);
      children.forEach(child => {
        ids.push(child.id);
        getChildrenIds(child.id);
      });
    };
    
    getChildrenIds(rootId);
    
    return ids;
  }