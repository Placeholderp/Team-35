import { createApp } from 'vue'

import App from './App.vue'
import store from './store'
import router from './router'
import './index.css'
import currencyUSD from './filters/currency.js'
import 'primeicons/primeicons.css'
import { theme } from './assets/theme.js'
import NotificationSystem from './components/core/Notification/NotificationSystem.vue'

// Create the app first
const app = createApp(App)

// Register components
app.component('NotificationSystem', NotificationSystem)

// Add global properties
app.config.globalProperties.$theme = theme
app.config.globalProperties.$filters = {
  currencyUSD
}

// Use plugins
app.use(store)
app.use(router)

// Mount the app last
app.mount('#app')