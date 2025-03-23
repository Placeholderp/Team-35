import { createApp } from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import './orderStatusFix.js';
import './index.css'
import currencyUSD from './filters/currency.js'
import 'primeicons/primeicons.css'
import { theme } from './assets/theme.js'
import NotificationSystem from './components/core/Notification/NotificationSystem.vue'
import axiosClient from './axios'  // Import custom axios client instead of standard axios

// More robust CSRF token handling
let token = document.head.querySelector('meta[name="csrf-token"]');

// Check for the token in cookies as a fallback
if (!token || !token.content) {
  // Try to get it from cookie
  const xsrfCookie = document.cookie.match(new RegExp('(^|;\\s*)(XSRF-TOKEN)=([^;]*)'));
  const tokenValue = xsrfCookie ? decodeURIComponent(xsrfCookie[3]) : '';
  
  if (tokenValue) {
    axiosClient.defaults.headers.common['X-XSRF-TOKEN'] = tokenValue;
  }
} else {
  axiosClient.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Create the app first
const app = createApp(App)

// Register components
app.component('NotificationSystem', NotificationSystem)

// Add global properties
app.config.globalProperties.$theme = theme
app.config.globalProperties.$filters = {
  currencyUSD
}
app.config.globalProperties.$axios = axiosClient  // Make custom axios client available globally

// Use plugins
app.use(store)
app.use(router)

// Mount the app last
app.mount('#app')