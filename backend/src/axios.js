import axios from "axios";
import store from "./store";
import router from "./router/index.js";
import { cleanId } from "./utils/ProductUtils";

const axiosClient = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
  withCredentials: true // Enable cookie-based authentication
});

// Authorization header interceptor 
axiosClient.interceptors.request.use(config => {
  // Only add Authorization header if token exists
  if (store.state.user.token) {
    config.headers.Authorization = `Bearer ${store.state.user.token}`;
  }
  return config;
});

// Product ID cleaning interceptor
axiosClient.interceptors.request.use(config => {
  // Skip if no URL is present
  if (!config.url) return config;

  // Clean product IDs in URLs
  const productsPattern = /\/products\/([^\/]+)/;
  const match = config.url.match(productsPattern);
  
  if (match && match[1]) {
    const originalId = match[1];
    // Ensure we're using our utility function to clean the ID
    const cleanedId = cleanId(originalId);
    
    // Replace the ID in the URL if it changed
    if (originalId !== cleanedId) {
      config.url = config.url.replace(`/products/${originalId}`, `/products/${cleanedId}`);
    }
  }
  
  // Only process data if we're working with products and data exists
  const isProductsEndpoint = config.url.includes('/products/') || config.url === '/products';
  if (!config.data || !isProductsEndpoint) return config;

  // Handle FormData objects
  if (config.data instanceof FormData) {
    if (config.data.has('id')) {
      const id = config.data.get('id');
      const cleanedId = cleanId(id);
      
      // Only update if cleaning actually changed something
      if (id !== cleanedId) {
        config.data.set('id', cleanedId);
      }
    }
    return config;
  }
  
  // Handle stringified JSON
  if (typeof config.data === 'string') {
    try {
      const dataObj = JSON.parse(config.data);
      if (dataObj.id) {
        const cleanedId = cleanId(dataObj.id);
        if (dataObj.id !== cleanedId) {
          dataObj.id = cleanedId;
          config.data = JSON.stringify(dataObj);
        }
      }
    } catch (e) {
      console.warn('Error parsing JSON data for ID cleaning:', e.message);
    }
    return config;
  }
  
  // Handle direct objects
  if (typeof config.data === 'object' && config.data !== null && config.data.id) {
    const cleanedId = cleanId(config.data.id);
    if (config.data.id !== cleanedId) {
      config.data.id = cleanedId;
    }
  }
  
  return config;
});

// Response error handling interceptor
axiosClient.interceptors.response.use(response => {
  return response;
}, error => {
  if (error.response && error.response.status === 401) {
    store.commit('setToken', null);
    router.push({name: 'login'});
  }
  throw error;
});

export default axiosClient;