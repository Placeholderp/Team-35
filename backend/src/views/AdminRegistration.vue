<template>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 flex flex-col items-center justify-center py-12 px-4">
      <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="../assets/logo.png" alt="Company Logo" class="h-20 w-auto drop-shadow-sm" />
        </div>
  
        <!-- Title -->
        <h1 class="text-center text-3xl font-bold text-gray-900 mb-8">Admin Registration</h1>
  
        <!-- Registration Form Card -->
        <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-100">
          <!-- Header with Icon -->
          <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-900">Create Admin Account</h2>
              <p class="text-gray-500 text-sm">First-time registration</p>
            </div>
          </div>
  
          <!-- Error Message -->
          <div v-if="errorMsg" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4 text-sm">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">{{ errorMsg }}</div>
            </div>
          </div>
  
          <form @submit.prevent="register">
            <!-- Name Input -->
            <div class="mb-5">
              <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="pi pi-user text-gray-400"></i>
                </div>
                <input
                  id="full-name"
                  name="name"
                  type="text"
                  required
                  v-model="user.name"
                  class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200"
                  placeholder="John Doe"
                />
              </div>
            </div>
  
            <!-- Email Input -->
            <div class="mb-5">
              <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="pi pi-envelope text-gray-400"></i>
                </div>
                <input
                  id="email-address"
                  name="email"
                  type="email"
                  autocomplete="email"
                  required
                  v-model="user.email"
                  class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200"
                  placeholder="johndoe@example.com"
                />
              </div>
            </div>
  
            <!-- Password Input -->
            <div class="mb-5">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="pi pi-lock text-gray-400"></i>
                </div>
                <input
                  :type="passwordShow ? 'text' : 'password'"
                  id="password"
                  name="password"
                  autocomplete="new-password"
                  required
                  v-model="user.password"
                  class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200"
                  placeholder="••••••••"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <button
                    type="button"
                    @click="togglePassword"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-150"
                  >
                    <component :is="passwordShow ? EyeIcon : EyeOffIcon" class="h-5 w-5" />
                  </button>
                </div>
              </div>
            </div>
  
            <!-- Confirm Password Input -->
            <div class="mb-5">
              <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="pi pi-lock text-gray-400"></i>
                </div>
                <input
                  :type="confirmPasswordShow ? 'text' : 'password'"
                  id="confirm-password"
                  name="confirm-password"
                  autocomplete="new-password"
                  required
                  v-model="user.confirmPassword"
                  class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200"
                  placeholder="••••••••"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <button
                    type="button"
                    @click="toggleConfirmPassword"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-150"
                  >
                    <component :is="confirmPasswordShow ? EyeIcon : EyeOffIcon" class="h-5 w-5" />
                  </button>
                </div>
              </div>
              <div v-if="passwordMismatch" class="mt-1 text-sm text-red-600">
                Passwords don't match
              </div>
            </div>
  
            <!-- Registration Code Input -->
            <div class="mb-6">
              <label for="registration-code" class="block text-sm font-medium text-gray-700 mb-1">Registration Code</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="pi pi-key text-gray-400"></i>
                </div>
                <input
                  id="registration-code"
                  name="registrationCode"
                  type="text"
                  required
                  v-model="user.registrationCode"
                  class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200"
                  placeholder="Enter registration code"
                />
              </div>
              <div class="mt-1 text-xs text-gray-500">
                Contact your system administrator for the registration code
              </div>
            </div>
  
            <!-- Agreement Checkbox -->
            <div class="mb-6">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="terms"
                    name="terms"
                    type="checkbox"
                    v-model="user.agreeToTerms"
                    required
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a></label>
                </div>
              </div>
            </div>
  
            <!-- Submit Button -->
            <div>
              <button
                type="submit"
                :disabled="loading || !user.agreeToTerms || passwordMismatch"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-md text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                :class="{ 'opacity-70 cursor-not-allowed': loading || !user.agreeToTerms || passwordMismatch }"
              >
                <svg
                  v-if="loading"
                  class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg 
                  v-else
                  class="h-4 w-4 mr-2"
                  xmlns="http://www.w3.org/2000/svg" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ loading ? 'Creating account...' : 'Create Account' }}
              </button>
            </div>
          </form>
  
          <!-- Login Link -->
          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
              Already have an account?
              <router-link :to="{ name: 'login' }" class="font-medium text-indigo-600 hover:text-indigo-500">
                Sign in
              </router-link>
            </p>
          </div>
  
          <!-- Secure Connection -->
          <div class="flex justify-center items-center mt-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs text-gray-500">Secure, encrypted connection</span>
          </div>
        </div>
      </div>
  
      <!-- Copyright -->
      <div class="mt-8 text-center">
        <p class="text-xs text-gray-500">© 2025 Your Company. All rights reserved.</p>
      </div>
    </div>
  </template>
  
  <script setup>
import { ref, computed } from 'vue'
import { EyeIcon, EyeOffIcon } from '@heroicons/vue/solid'
import GuestLayout from "../components/GuestLayout.vue";
import store from "../store";
import router from "../router";

const loading = ref(false);
const errorMsg = ref("");
const passwordShow = ref(false);
const confirmPasswordShow = ref(false);
const user = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
  registrationCode: '',
  agreeToTerms: false
});

// Check if passwords match
const passwordMismatch = computed(() => {
  if (user.value.confirmPassword === '') return false;
  return user.value.password !== user.value.confirmPassword;
});

function togglePassword() {
  passwordShow.value = !passwordShow.value;
}

function toggleConfirmPassword() {
  confirmPasswordShow.value = !confirmPasswordShow.value;
}

function register() {
  if (passwordMismatch.value) {
    errorMsg.value = "Passwords don't match";
    return;
  }

  loading.value = true;
  
  // Create the data to send to the API
  const userData = {
    name: user.value.name,
    email: user.value.email,
    password: user.value.password,
    registration_code: user.value.registrationCode,
  };

  // Call the register action in your store
  store.dispatch('register', userData)
    .then(() => {
      loading.value = false;
      
      // Try to show success notification if notification system exists
      if (window.$notification && typeof window.$notification.success === 'function') {
        window.$notification.success(
          'Registration successful',
          'Your admin account has been created. Please change your password after first login.'
        );
      } else {
        // Fallback to simple alert if notification system is not available
        alert('Registration successful! Your admin account has been created. Please change your password after first login.');
      }
      
      // Redirect to login page
      router.push({ 
        name: 'login',
        query: { newAdmin: true }  // Flag to indicate a new admin was registered
      });
    })
    .catch((error) => {
      loading.value = false;
      // Safely extract error message
      const errorMessage = error.response?.data?.message || "Registration failed. Please try again.";
      errorMsg.value = errorMessage;
      
      // Try to show error notification if notification system exists
      if (window.$notification && typeof window.$notification.error === 'function') {
        window.$notification.error(
          errorMessage,
          'Registration Failed'
        );
      } else {
        // Log error to console for debugging
        console.error('Registration failed:', error);
      }
    });
}
</script>