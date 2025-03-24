// Import dependencies and modules
import './bootstrap.js'; // Import the bootstrap file (initializes application settings, etc.)
import Alpine from 'alpinejs'; // Import Alpine.js for reactive UI components
import collapse from '@alpinejs/collapse'; // Import the collapse plugin for Alpine
import { get, post } from "./http.js"; // Import HTTP helper functions for making GET/POST requests
import NotificationSystem from '@/components/core/Notification/NotificationSystem.vue'
Alpine.plugin(collapse);

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {

  // Define a "toast" component to manage notification messages
  Alpine.data("toast", () => ({
    visible: false,      
    delay: 5000,         
    percent: 0,           
    interval: null,       
    timeout: null,        
    message: null,        

    // Method to close the toast immediately
    close() {
      this.visible = false;
      clearInterval(this.interval);
    },
    // Method to show the toast with a specific message
    show(message) {
      this.visible = true;
      this.message = message;

      // Clear any existing interval or timeout before starting new ones
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      // Set a timeout to hide the toast after the specified delay
      this.timeout = setTimeout(() => {
        this.visible = false;
        this.timeout = null;
      }, this.delay);

      // Calculate and update the progress percentage over the duration of the delay
      const startDate = Date.now();
      const futureDate = Date.now() + this.delay;
      this.interval = setInterval(() => {
        const date = Date.now();
        this.percent = ((date - startDate) * 100) / (futureDate - startDate);
        if (this.percent >= 100) {
          clearInterval(this.interval);
          this.interval = null;
        }
      }, 30);
    },
  }));

  // Define a "productItem" component to manage individual product interactions in the cart
  Alpine.data("productItem", (product) => {
    return {
      product, // The product data passed into the component

      // Method to add the product to the cart with an optional quantity (default is 1)
      addToCart(quantity = 1) {
        post(this.product.addToCartUrl, { quantity })
          .then(result => {
            this.$dispatch('cart-change', { count: result.count });
            this.$dispatch("notify", {
              message: "The item was added into the cart",
            });
          })
          .catch(response => {
            console.log(response);
          });
      },

      // Method to remove the product from the cart
      removeItemFromCart() {
        post(this.product.removeUrl)
          .then(result => {
            this.$dispatch("notify", {
              message: "The item was removed from cart",
            });
            this.$dispatch('cart-change', { count: result.count });
            this.cartItems = this.cartItems.filter(p => p.id !== product.id);
          });
      },

      // Method to change the quantity of the product in the cart
      changeQuantity() {
        post(this.product.updateQuantityUrl, { quantity: product.quantity })
          .then(result => {
            this.$dispatch('cart-change', { count: result.count });
            this.$dispatch("notify", {
              message: "The item quantity was updated",
            });
          });
      },
    };
  });
});

// Start Alpine.js to initialize all defined components and functionality
Alpine.start();
