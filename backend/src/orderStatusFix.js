import axiosClient from './axios';
import { normalizeStatus } from '../src/utils/orderstatusUtils';

// Add a global interceptor to normalize all status parameters
axiosClient.interceptors.request.use(config => {
  if (config.params && config.params.status !== undefined) {
    const originalStatus = config.params.status;
    
    // Skip if status is empty
    if (!originalStatus) {
      delete config.params.status;
      return config;
    }
    
    // Normalize the status
    config.params.status = normalizeStatus(originalStatus);
    
    // Log the change if there was one
    if (config.params.status !== originalStatus) {
      console.log(`[Interceptor] Normalized status: "${originalStatus}" → "${config.params.status}"`);
    }
  }
  return config;
});

console.log('Order status interceptor installed');