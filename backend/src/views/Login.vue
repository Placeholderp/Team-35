<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 flex flex-col items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="flex justify-center mb-6">
        <img src="../assets/logo.png" alt="Company Logo" class="h-20 w-auto drop-shadow-sm" />
      </div>

      <!-- Title -->
      <h1 class="text-center text-3xl font-bold text-gray-900 mb-8">Welcome back, Admin!</h1>

      <!-- Login Form Card -->
      <div class="bg-white shadow-lg rounded-lg p-8 border border-gray-100">
        <!-- Header with Icon -->
        <div class="flex items-center mb-6">
          <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Admin Dashboard</h2>
            <p class="text-gray-500 text-sm">Sign in to continue</p>
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

        <form @submit.prevent="login">
          <!-- Email Input -->
          <div class="mb-5">
            <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="pi pi-user text-gray-400"></i>
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
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <div class="text-sm">
                <router-link :to="{ name: 'requestPassword' }" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-150">
                  Forgot password?
                </router-link>
              </div>
            </div>
            <div class="relative mt-1">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="pi pi-lock text-gray-400"></i>
              </div>
              <input
                :type="passwordShow ? 'text' : 'password'"
                id="password"
                name="password"
                autocomplete="current-password"
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

          <!-- Remember Me -->
          <div class="mb-6">
            <div class="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                v-model="user.remember"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                Keep me signed in
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-md text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
              :class="{ 'opacity-70 cursor-not-allowed': loading }"
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              {{ loading ? 'Signing in...' : 'Sign in' }}
            </button>
          </div>
        </form>

        <!-- Registration Link -->
        <div class="mt-5 text-center">
          <p class="text-sm text-gray-600">
            Need a new admin account?
            <router-link :to="{ name: 'admin.register' }" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-150">
              Register here
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
import { ref } from 'vue'
import { EyeIcon, EyeOffIcon } from '@heroicons/vue/solid'
import GuestLayout from "../components/GuestLayout.vue";
import store from "../store";
import router from "../router";
import { useRoute } from 'vue-router';

const route = useRoute();
const loading = ref(false);
const errorMsg = ref("");
const passwordShow = ref(false);
const user = ref({
  email: '',
  password: '',
  remember: false
});

// Check if coming from admin registration
if (route.query.newAdmin) {
  window.$notification.success(
    'Admin account created successfully. Please login with your credentials.',
    'Registration Successful'
  );
}

function togglePassword() {
  passwordShow.value = !passwordShow.value;
}

function login() {
  loading.value = true;
  store.dispatch('login', user.value)
    .then((data) => {
      loading.value = false;
      
      // Check if this is a first-time login requiring password change
      if (data.user && data.user.force_password_change) {
        // Show notification about required password change
        window.$notification.info(
          'Please change your password to continue',
          'Security Step Required'
        );
        
        // Redirect to password change page
        router.push({ name: 'forcePasswordChange' });
      } else {
        // Show success notification for normal login
        window.$notification.success(
          'Login successful',
          'Welcome back!'
        );
        
        // Redirect to dashboard or the original requested page
        const redirectPath = route.query.redirect || { name: 'app.dashboard' };
        router.push(redirectPath);
      }
    })
    .catch(({ response }) => {
      loading.value = false;
      errorMsg.value = response?.data?.message || "Invalid credentials. Please check your email and password.";
      
      // Show error notification
      window.$notification.error(
        response?.data?.message || "Invalid credentials. Please check your email and password.",
        'Authentication Failed'
      );
    });
}
</script>