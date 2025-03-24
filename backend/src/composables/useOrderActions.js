import { ref } from 'vue';
import axios from '../axios';
import { useStore } from 'vuex';

export function useOrderActions() {
  const store = useStore();
  const loading = ref(false);
  
  /**
   * Update an order's status
   * @param {Number|String} orderId Order ID
   * @param {String} status New status
   * @returns {Promise}
   */
  async function updateOrderStatus(orderId, status) {
    loading.value = true;
    
    try {
      // Using the exact format that matches your API routes
      // This matches: Route::post('orders/{order}/status/{status}', [OrderController::class, 'changeStatus']);
      const response = await axios.post(`/orders/${orderId}/status/${status}`);
      
      // Log the request details for debugging
      console.log(`Sending POST request to: /orders/${orderId}/status/${status}`);
      
      loading.value = false;
      return response;
    } catch (error) {
      console.error('Error updating order status:', error);
      loading.value = false;
      throw error;
    }
  }
  
  /**
   * Print the current order
   */
  function printOrder() {
    window.print();
  }
  
  /**
   * Send invoice email for an order
   * @param {Number|String} orderId Order ID
   * @returns {Promise}
   */
  async function emailInvoice(orderId) {
    loading.value = true;
    
    try {
      const response = await axios.post(`/orders/${orderId}/email-invoice`);
      loading.value = false;
      
      store.commit('showToast', {
        message: 'Invoice email sent successfully',
        type: 'success'
      });
      
      return response;
    } catch (error) {
      loading.value = false;
      
      store.commit('showToast', {
        message: 'Failed to send invoice email',
        type: 'error'
      });
      
      throw error;
    }
  }
  
  /**
   * Format a phone number
   * @param {String} phoneNumber Phone number
   * @returns {String} Formatted phone number
   */
  function formatPhoneNumber(phoneNumber) {
    if (!phoneNumber) return '';
    
    // Remove all non-numeric characters
    const cleaned = String(phoneNumber).replace(/\D/g, '');
    
    // Check if the number has the right length for US format
    if (cleaned.length === 10) {
      return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6, 10)}`;
    }
    
    // For international or other formats, just return the original
    return phoneNumber;
  }
  
  return {
    loading,
    updateOrderStatus,
    printOrder,
    emailInvoice,
    formatPhoneNumber
  };
}