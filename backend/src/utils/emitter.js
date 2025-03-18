// In your InventoryAdjustmentModal.vue script
import emitter from '../../utils/emitter';

// In the setup function or at the component level:
onMounted(() => {
  loadProducts();
  
  // Listen for the changePassword event via the global emitter
  emitter.on('changePassword', handleChangePassword);
});

// Don't forget to clean up when the component is unmounted
onUnmounted(() => {
  emitter.off('changePassword', handleChangePassword);
});

// Handler function
const handleChangePassword = (payload) => {
  // Handle the changePassword event
  console.log('Password change event received', payload);
  // Your implementation here
};