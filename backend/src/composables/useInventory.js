// src/composables/useInventory.js
import { ref, computed, watch } from 'vue';
import { useStore } from 'vuex';

export function useInventory() {
  const store = useStore();
  const searchQuery = ref('');
  
  // First, check if the inventory module exists
  const inventoryModuleExists = computed(() => {
    return store.state && store.state.inventory !== undefined;
  });
  
  // Show warning if module doesn't exist
  if (!inventoryModuleExists.value) {
    console.warn('Inventory module not registered in Vuex store. Please check your store configuration.');
  }
  
  // Safely access the loading state
  const loading = computed(() => {
    if (!inventoryModuleExists.value) return false;
    
    const loadingState = store.state.inventory.loading;
    if (typeof loadingState === 'object' && loadingState !== null) {
      return Boolean(
        loadingState.products || 
        loadingState.movements || 
        loadingState.operation
      );
    }
    
    return Boolean(loadingState);
  });
  
  // Safely access products
  const products = computed(() => {
    if (!inventoryModuleExists.value) return [];
    return store.state.inventory.products || [];
  });
  
  // Safely access filtered products
  const filteredProducts = computed(() => {
    if (!inventoryModuleExists.value) return [];
    try {
      return store.getters['inventory/filteredProducts'] || [];
    } catch (error) {
      console.error('Error accessing filteredProducts getter:', error);
      return [];
    }
  });
  
  // Safely access low stock products
  const lowStockProducts = computed(() => {
    if (!inventoryModuleExists.value) return [];
    try {
      return store.getters['inventory/lowStockProducts'] || [];
    } catch (error) {
      console.error('Error accessing lowStockProducts getter:', error);
      return [];
    }
  });
  
  // Safely access out of stock products
  const outOfStockProducts = computed(() => {
    if (!inventoryModuleExists.value) return [];
    try {
      return store.getters['inventory/outOfStockProducts'] || [];
    } catch (error) {
      console.error('Error accessing outOfStockProducts getter:', error);
      return [];
    }
  });
  
  // Safely access inventory value
  const inventoryValue = computed(() => {
    if (!inventoryModuleExists.value) return 0;
    try {
      return store.getters['inventory/inventoryValue'] || 0;
    } catch (error) {
      console.error('Error accessing inventoryValue getter:', error);
      return 0;
    }
  });
  
  // Safely access inventory movements
  const inventoryMovements = computed(() => {
    if (!inventoryModuleExists.value) return [];
    return store.state.inventory.inventoryMovements || [];
  });
  
  // Safely access stats
  const stats = computed(() => {
    if (!inventoryModuleExists.value) return {
      totalProducts: 0,
      inStockProducts: 0,
      lowStockProducts: 0,
      outOfStockProducts: 0,
      inventoryValue: 0
    };
    
    return store.state.inventory.stats || {
      totalProducts: 0,
      inStockProducts: 0,
      lowStockProducts: 0,
      outOfStockProducts: 0,
      inventoryValue: 0
    };
  });
  
  // Safe dispatch: fetchProducts
  const fetchProducts = async () => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot fetch products: inventory module not registered');
      return { data: [] };
    }
    
    try {
      return await store.dispatch('inventory/fetchProducts');
    } catch (error) {
      console.error('Error fetching products:', error);
      return { data: [] };
    }
  };
  
  // Safe dispatch: fetchInventoryMovements
  const fetchInventoryMovements = async () => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot fetch movements: inventory module not registered');
      return { data: [] };
    }
    
    try {
      // This is the critical part - need to handle the response structure
      const response = await store.dispatch('inventory/fetchInventoryMovements');
      
      // Return a safe response object to prevent errors when accessing properties
      return {
        data: response?.data || [],
        // The error is happening because we're trying to access "current_page" directly,
        // but the store action returns "meta.current_page"
        current_page: response?.meta?.current_page || 1,
        last_page: response?.meta?.last_page || 1,
        total: response?.meta?.total || 0,
        per_page: response?.meta?.per_page || 10
      };
    } catch (error) {
      console.error('Error fetching inventory movements:', error);
      // Return a safe default structure to prevent errors
      return {
        data: [],
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 10
      };
    }
  };
  
  // Safe dispatch: adjustInventory
  const adjustInventory = async (productId, adjustment) => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot adjust inventory: inventory module not registered');
      return { success: false };
    }
    
    try {
      return await store.dispatch('inventory/adjustInventory', { productId, adjustment });
    } catch (error) {
      console.error('Error adjusting inventory:', error);
      return { success: false, error };
    }
  };
  
  // Safe dispatch: bulkAdjustInventory
  const bulkAdjustInventory = async (adjustments) => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot perform bulk adjustment: inventory module not registered');
      return { success: false };
    }
    
    try {
      return await store.dispatch('inventory/bulkAdjustInventory', adjustments);
    } catch (error) {
      console.error('Error performing bulk adjustment:', error);
      return { success: false, error };
    }
  };
  
  // Safe dispatch: setFilter
  const setFilter = (filter) => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot set filter: inventory module not registered');
      return;
    }
    
    try {
      store.dispatch('inventory/setFilter', filter);
    } catch (error) {
      console.error('Error setting filter:', error);
    }
  };
  
  // Safe dispatch: loadMoreProducts
  const loadMoreProducts = async () => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot load more products: inventory module not registered');
      return { success: false };
    }
    
    try {
      return await store.dispatch('inventory/loadMore', 'products');
    } catch (error) {
      console.error('Error loading more products:', error);
      return { success: false, error };
    }
  };
  
  // Safe dispatch: loadMoreMovements
  const loadMoreMovements = async () => {
    if (!inventoryModuleExists.value) {
      console.error('Cannot load more movements: inventory module not registered');
      return { success: false };
    }
    
    try {
      return await store.dispatch('inventory/loadMore', 'movements');
    } catch (error) {
      console.error('Error loading more movements:', error);
      return { success: false, error };
    }
  };
  
  // Watch for changes to searchQuery and update store filter
  watch(searchQuery, (newValue) => {
    if (inventoryModuleExists.value) {
      setFilter({ key: 'search', value: newValue });
    }
  });
  
  return {
    searchQuery,
    loading,
    products,
    filteredProducts,
    lowStockProducts,
    outOfStockProducts,
    inventoryValue,  // Critical: Make sure inventoryValue is exposed
    inventoryMovements,
    stats,  // Expose stats from the store
    fetchProducts,
    fetchInventoryMovements,
    adjustInventory,
    bulkAdjustInventory,
    setFilter,
    loadMoreProducts,
    loadMoreMovements
  };
}

// Import useInventoryStatus separately
export { useInventoryStatus } from './useInventoryStatus';