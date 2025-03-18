<!-- InventoryAlert.vue -->
<template>
    <div 
      v-if="product && product.track_inventory"
      class="rounded-md p-4 mb-4"
      :class="alertClass"
    >
      <div class="flex">
        <div class="flex-shrink-0">
          <component :is="alertIcon" class="h-5 w-5" :class="iconClass" aria-hidden="true" />
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium" :class="textClass">
            {{ alertTitle }}
          </h3>
          <div class="mt-2 text-sm" :class="textClass">
            <p>{{ alertMessage }}</p>
          </div>
          <div v-if="showAction" class="mt-4">
            <div class="-mx-2 -my-1.5 flex">
              <button
                type="button"
                @click="$emit('action-click', product)"
                class="rounded-md px-2 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2"
                :class="buttonClass"
              >
                {{ actionText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed, defineEmits, defineProps } from 'vue';
  
  const emit = defineEmits(['action-click']);
  
  const props = defineProps({
    product: {
      type: Object,
      required: true
    }
  });
  
  // Check if product is out of stock
  const isOutOfStock = computed(() => {
    return props.product.track_inventory && props.product.quantity <= 0;
  });
  
  // Check if product is low on stock
  const isLowStock = computed(() => {
    return props.product.track_inventory && 
           props.product.quantity > 0 && 
           props.product.quantity <= (props.product.reorder_level || 5);
  });
  
  // Alert styling based on stock status
  const alertClass = computed(() => {
    if (isOutOfStock.value) return 'bg-red-50 border border-red-200';
    if (isLowStock.value) return 'bg-yellow-50 border border-yellow-200';
    return 'bg-green-50 border border-green-200';
  });
  
  const textClass = computed(() => {
    if (isOutOfStock.value) return 'text-red-800';
    if (isLowStock.value) return 'text-yellow-800';
    return 'text-green-800';
  });
  
  const iconClass = computed(() => {
    if (isOutOfStock.value) return 'text-red-400';
    if (isLowStock.value) return 'text-yellow-400';
    return 'text-green-400';
  });
  
  const buttonClass = computed(() => {
    if (isOutOfStock.value) return 'bg-red-100 text-red-800 hover:bg-red-200 focus:ring-red-500 focus:ring-offset-red-50';
    if (isLowStock.value) return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 focus:ring-yellow-500 focus:ring-offset-yellow-50';
    return 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500 focus:ring-offset-green-50';
  });
  
  // Alert content based on stock status
  const alertTitle = computed(() => {
    if (isOutOfStock.value) return 'Out of Stock';
    if (isLowStock.value) return 'Low Stock Alert';
    return 'In Stock';
  });
  
  const alertMessage = computed(() => {
    if (isOutOfStock.value) {
      return 'This product is currently out of stock. Consider restocking soon to continue sales.';
    }
    if (isLowStock.value) {
      return `Only ${props.product.quantity} units remaining. This is below the reorder level of ${props.product.reorder_level}.`;
    }
    return `Currently ${props.product.quantity} units in stock, which is above the reorder level.`;
  });
  
  // Show action button based on stock status
  const showAction = computed(() => {
    return isOutOfStock.value || isLowStock.value;
  });
  
  const actionText = computed(() => {
    if (isOutOfStock.value) return 'Restock Now';
    if (isLowStock.value) return 'Order More';
    return '';
  });
  
  // Define alert icons using render functions
  const alertIcon = computed(() => {
    if (isOutOfStock.value) {
      return {
        render() {
          return h('svg', { 
            xmlns: 'http://www.w3.org/2000/svg', 
            viewBox: '0 0 20 20', 
            fill: 'currentColor' 
          }, [
            h('path', { 
              'fill-rule': 'evenodd',
              'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
              'clip-rule': 'evenodd'
            })
          ]);
        }
      };
    }
    
    if (isLowStock.value) {
      return {
        render() {
          return h('svg', { 
            xmlns: 'http://www.w3.org/2000/svg', 
            viewBox: '0 0 20 20', 
            fill: 'currentColor' 
          }, [
            h('path', { 
              'fill-rule': 'evenodd',
              'd': 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
              'clip-rule': 'evenodd'
            })
          ]);
        }
      };
    }
    
    return {
      render() {
        return h('svg', { 
          xmlns: 'http://www.w3.org/2000/svg', 
          viewBox: '0 0 20 20', 
          fill: 'currentColor' 
        }, [
          h('path', { 
            'fill-rule': 'evenodd',
            'd': 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
            'clip-rule': 'evenodd'
          })
        ]);
      }
    };
  });
  </script>