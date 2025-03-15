import axios from '../axios';
import { useStore } from 'vuex';

export function useOrderActions() {
  const store = useStore();

  const updateOrderStatus = async (orderId, status) => {
    try {
      const response = await axios.post(`/orders/change-status/${orderId}/${status}`);
      return response.data;
    } catch (error) {
      console.error('Error updating order status:', error);
      throw error;
    }
  };

  const printOrder = () => {
    window.print();
  };

  const emailInvoice = async (orderId) => {
    try {
      await axios.post(`/orders/${orderId}/email-invoice`);
      store.commit('showToast', {
        message: 'Invoice has been sent to the customer.',
        type: 'success'
      });
    } catch (error) {
      console.error('Error sending invoice:', error);
      store.commit('showToast', {
        message: 'Error sending invoice email.',
        type: 'error'
      });
      throw error;
    }
  };

  const formatPhoneNumber = (phone) => {
    if (!phone) return 'N/A';
    // Basic phone formatting - adjust as needed
    const cleaned = ('' + phone).replace(/\D/g, '');
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
      return `(${match[1]}) ${match[2]}-${match[3]}`;
    }
    return phone;
  };

  return {
    updateOrderStatus,
    printOrder,
    emailInvoice,
    formatPhoneNumber
  };
}