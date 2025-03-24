// src/composables/useInventoryStatus.js
import { computed, h } from 'vue';

/**
 * Composable for inventory status management
 * Provides computed properties for determining and displaying product inventory status
 * 
 * @param {Ref<Object>} product - Reactive reference to a product object
 * @returns {Object} - Object containing computed properties for inventory status
 */
export function useInventoryStatus(product) {
  // Check if product is out of stock
  const isOutOfStock = computed(() => {
    return product.value.track_inventory && product.value.quantity <= 0;
  });
  
  // Check if product is low on stock
  const isLowStock = computed(() => {
    return product.value.track_inventory && 
           product.value.quantity > 0 && 
           product.value.quantity <= (product.value.reorder_level || 5);
  });
  
  // Calculate inventory status
  const stockStatus = computed(() => {
    if (!product.value.track_inventory) return 'not_tracked';
    if (isOutOfStock.value) return 'out_of_stock';
    if (isLowStock.value) return 'low_stock';
    return 'in_stock';
  });
  
  // Status text
  const statusText = computed(() => {
    switch (stockStatus.value) {
      case 'not_tracked': return 'Not Tracked';
      case 'out_of_stock': return 'Out of Stock';
      case 'low_stock': return 'Low Stock';
      case 'in_stock': return 'In Stock';
      default: return 'Unknown';
    }
  });
  
  // CSS classes for status indicators
  const statusClasses = computed(() => {
    switch (stockStatus.value) {
      case 'not_tracked':
        return {
          badge: 'bg-gray-100 text-gray-800',
          text: 'text-gray-600',
          icon: 'text-gray-400',
          alert: 'bg-gray-50 border border-gray-200'
        };
      case 'out_of_stock':
        return {
          badge: 'bg-red-100 text-red-800',
          text: 'text-red-600',
          icon: 'text-red-400',
          alert: 'bg-red-50 border border-red-200'
        };
      case 'low_stock':
        return {
          badge: 'bg-yellow-100 text-yellow-800',
          text: 'text-yellow-600',
          icon: 'text-yellow-400',
          alert: 'bg-yellow-50 border border-yellow-200'
        };
      case 'in_stock':
        return {
          badge: 'bg-green-100 text-green-800',
          text: 'text-green-600',
          icon: 'text-green-400',
          alert: 'bg-green-50 border border-green-200'
        };
      default:
        return {
          badge: 'bg-gray-100 text-gray-800',
          text: 'text-gray-600',
          icon: 'text-gray-400',
          alert: 'bg-gray-50 border border-gray-200'
        };
    }
  });
  
  // Status icon render functions
  const statusIcon = computed(() => {
    switch (stockStatus.value) {
      case 'out_of_stock':
        return {
          render() {
            return h('svg', { 
              xmlns: 'http://www.w3.org/2000/svg', 
              viewBox: '0 0 20 20', 
              fill: 'currentColor',
              class: 'h-5 w-5'
            }, [
              h('path', { 
                'fill-rule': 'evenodd',
                'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
                'clip-rule': 'evenodd'
              })
            ]);
          }
        };
      case 'low_stock':
        return {
          render() {
            return h('svg', { 
              xmlns: 'http://www.w3.org/2000/svg', 
              viewBox: '0 0 20 20', 
              fill: 'currentColor',
              class: 'h-5 w-5'
            }, [
              h('path', { 
                'fill-rule': 'evenodd',
                'd': 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
                'clip-rule': 'evenodd'
              })
            ]);
          }
        };
      case 'in_stock':
        return {
          render() {
            return h('svg', { 
              xmlns: 'http://www.w3.org/2000/svg', 
              viewBox: '0 0 20 20', 
              fill: 'currentColor',
              class: 'h-5 w-5'
            }, [
              h('path', { 
                'fill-rule': 'evenodd',
                'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
                'clip-rule': 'evenodd'
              })
            ]);
          }
        };
      default:
        return {
          render() {
            return h('svg', { 
              xmlns: 'http://www.w3.org/2000/svg', 
              viewBox: '0 0 20 20', 
              fill: 'currentColor',
              class: 'h-5 w-5'
            }, [
              h('path', { 
                'fill-rule': 'evenodd',
                'd': 'M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8l4-2 4 2V6z',
                'clip-rule': 'evenodd'
              })
            ]);
          }
        };
    }
  });
  
  // Stock level percentage relative to reorder level
  const stockLevelPercentage = computed(() => {
    if (!product.value.track_inventory) return 100;
    if (!product.value.reorder_level || product.value.reorder_level <= 0) return 100;
    
    const percentage = (product.value.quantity / (product.value.reorder_level * 2)) * 100;
    return Math.min(Math.max(percentage, 0), 100); // Clamp between 0-100
  });
  
  // Days until stockout estimation based on current stock and average daily usage
  const estimatedDaysUntilStockout = computed(() => {
    if (!product.value.track_inventory || product.value.quantity <= 0) return 0;
    if (!product.value.average_daily_usage || product.value.average_daily_usage <= 0) return null;
    
    return Math.ceil(product.value.quantity / product.value.average_daily_usage);
  });
  
  // Recommended order quantity
  const recommendedOrderQuantity = computed(() => {
    if (!product.value.track_inventory) return 0;
    
    const reorderLevel = product.value.reorder_level || 5;
    const currentStock = product.value.quantity || 0;
    
    // If below reorder level, order enough to reach 2x reorder level
    if (currentStock <= reorderLevel) {
      return Math.max((reorderLevel * 2) - currentStock, 5);
    }
    
    return 0;
  });
  
  return {
    isOutOfStock,
    isLowStock,
    stockStatus,
    statusText,
    statusClasses,
    statusIcon,
    stockLevelPercentage,
    estimatedDaysUntilStockout,
    recommendedOrderQuantity
  };
}