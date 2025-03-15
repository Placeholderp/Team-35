import axios from "axios";
import store from "./store";
import router from "./router/index.js";
import { cleanId } from "./utils/ProductUtils";

const axiosClient = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`
});

// Authorization header interceptor
axiosClient.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${store.state.user.token}`;
  return config;
});

// Product ID cleaning interceptor
axiosClient.interceptors.request.use(config => {
  if (config.url) {
    // Clean product IDs in URLs
    const productsPattern = /\/products\/([^\/]+)/;
    const match = config.url.match(productsPattern);
    
    if (match && match[1]) {
      const originalId = match[1];
      // Ensure we're using our utility function to clean the ID
      const cleanedId = cleanId(originalId);
      
      // Always replace the ID in the URL
      if (originalId !== cleanedId) {
        config.url = config.url.replace(`/products/${originalId}`, `/products/${cleanedId}`);
      }
    }
  }
  
  // Clean product IDs in request body or FormData if present
  if (config.data && (config.url?.includes('/products/') || config.url === '/products')) {
    if (config.data instanceof FormData) {
      // Handle FormData objects
      if (config.data.has('id')) {
        const id = config.data.get('id');
        const cleanedId = cleanId(id);
        
        // Only update if cleaning actually changed something
        if (id !== cleanedId) {
          config.data.set('id', cleanedId);
        }
      }
    } else if (typeof config.data === 'object') {
      try {
        // Handle stringified JSON
        if (typeof config.data === 'string') {
          const dataObj = JSON.parse(config.data);
          if (dataObj.id) {
            const cleanedId = cleanId(dataObj.id);
            if (dataObj.id !== cleanedId) {
              dataObj.id = cleanedId;
              config.data = JSON.stringify(dataObj);
            }
          }
        } 
        // Handle direct objects
        else if (config.data.id) {
          const cleanedId = cleanId(config.data.id);
          if (config.data.id !== cleanedId) {
            config.data.id = cleanedId;
          }
        }
      } catch (e) {
        // Silent error handling for malformed JSON
      }
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