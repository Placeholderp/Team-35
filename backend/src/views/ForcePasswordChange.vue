<template>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Change Your Password</h2>
            <p class="mt-2 text-sm text-gray-600">
              For security reasons, you must change your password before continuing.
            </p>
          </div>
          
          <form @submit.prevent="onSubmit" class="space-y-6">
            <!-- Current Password -->
            <div>
              <label for="currentPassword" class="block text-sm font-medium text-gray-700">Current Password</label>
              <div class="mt-1">
                <input
                  id="currentPassword"
                  type="password"
                  v-model="passwordData.current_password"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
            </div>
            
            <!-- New Password -->
            <div>
              <label for="newPassword" class="block text-sm font-medium text-gray-700">New Password</label>
              <div class="mt-1">
                <input
                  id="newPassword"
                  type="password"
                  v-model="passwordData.new_password"
                  @input="checkPasswordStrength"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
              
              <!-- Password Strength Meter -->
              <div class="mt-2">
                <div class="flex justify-between mb-1">
                  <span class="text-xs text-gray-500">Password Strength: {{ passwordStrengthText }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="h-2 rounded-full transition-all duration-300"
                    :class="passwordStrengthColor"
                    :style="{ width: `${passwordStrength}%` }"
                  ></div>
                </div>
                <ul class="mt-2 text-xs text-gray-500 space-y-1">
                  <li :class="{ 'text-green-500': passwordLength }">
                    <span v-if="passwordLength">✓</span> At least 8 characters
                  </li>
                  <li :class="{ 'text-green-500': passwordUppercase }">
                    <span v-if="passwordUppercase">✓</span> Contains uppercase letter
                  </li>
                  <li :class="{ 'text-green-500': passwordLowercase }">
                    <span v-if="passwordLowercase">✓</span> Contains lowercase letter
                  </li>
                  <li :class="{ 'text-green-500': passwordNumber }">
                    <span v-if="passwordNumber">✓</span> Contains number
                  </li>
                  <li :class="{ 'text-green-500': passwordSpecial }">
                    <span v-if="passwordSpecial">✓</span> Contains special character
                  </li>
                </ul>
              </div>
            </div>
            
            <!-- Confirm Password -->
            <div>
              <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
              <div class="mt-1">
                <input
                  id="confirmPassword"
                  type="password"
                  v-model="passwordData.new_password_confirmation"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
              </div>
              <div v-if="passwordMatch === false" class="text-red-600 text-xs mt-1">
                Passwords do not match
              </div>
            </div>
            
            <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-400 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">{{ errorMessage }}</p>
                </div>
              </div>
            </div>
            
            <div>
              <button
                type="submit"
                :disabled="loading || !isFormValid"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :class="{ 'opacity-75 cursor-not-allowed': loading || !isFormValid }"
              >
                <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Change Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useRouter } from 'vue-router';
  import store from '../store';
  
  const router = useRouter();
  const loading = ref(false);
  const errorMessage = ref('');
  
  const passwordData = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  });
  
  // Password strength variables
  const passwordStrength = ref(0);
  const passwordLength = ref(false);
  const passwordUppercase = ref(false);
  const passwordLowercase = ref(false);
  const passwordNumber = ref(false);
  const passwordSpecial = ref(false);
  
  const passwordStrengthText = computed(() => {
    if (passwordStrength.value === 0) return 'None';
    if (passwordStrength.value < 40) return 'Weak';
    if (passwordStrength.value < 70) return 'Medium';
    return 'Strong';
  });
  
  const passwordStrengthColor = computed(() => {
    if (passwordStrength.value === 0) return '';
    if (passwordStrength.value < 40) return 'bg-red-500';
    if (passwordStrength.value < 70) return 'bg-yellow-500';
    return 'bg-green-500';
  });
  
  const passwordMatch = computed(() => {
    if (!passwordData.value.new_password_confirmation) return null;
    return passwordData.value.new_password === passwordData.value.new_password_confirmation;
  });
  
  const isFormValid = computed(() => {
    return (
      passwordData.value.current_password &&
      passwordData.value.new_password &&
      passwordData.value.new_password_confirmation &&
      passwordMatch.value &&
      passwordStrength.value >= 40 // Require at least medium strength
    );
  });
  
  function checkPasswordStrength() {
    const password = passwordData.value.new_password;
    let strength = 0;
    
    // Check length
    passwordLength.value = password.length >= 8;
    if (passwordLength.value) strength += 20;
    
    // Check uppercase
    passwordUppercase.value = /[A-Z]/.test(password);
    if (passwordUppercase.value) strength += 20;
    
    // Check lowercase
    passwordLowercase.value = /[a-z]/.test(password);
    if (passwordLowercase.value) strength += 20;
    
    // Check numbers
    passwordNumber.value = /[0-9]/.test(password);
    if (passwordNumber.value) strength += 20;
    
    // Check special characters
    passwordSpecial.value = /[^A-Za-z0-9]/.test(password);
    if (passwordSpecial.value) strength += 20;
    
    passwordStrength.value = strength;
  }
  
  function onSubmit() {
    if (!isFormValid.value) return;
    
    loading.value = true;
    errorMessage.value = '';
    
    store.dispatch('changePassword', passwordData.value)
      .then(response => {
        loading.value = false;
        store.commit('showToast', {
          type: 'success',
          message: 'Password changed successfully',
          title: 'Success'
        });
        
        // Redirect to dashboard after successful password change
        router.push({ name: 'app.dashboard' });
      })
      .catch(err => {
        loading.value = false;
        errorMessage.value = err.response?.data?.message || 'Failed to change password';
        console.error("Password change error:", err);
      });
  }
  </script>