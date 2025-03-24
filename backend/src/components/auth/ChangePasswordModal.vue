<template>
    <TransitionRoot as="template" :show="show">
      <Dialog as="div" class="relative z-10" @close="closeModal">
        <TransitionChild 
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"/>
        </TransitionChild>
  
        <div class="fixed z-10 inset-0 overflow-y-auto">
          <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
            <TransitionChild 
              as="template"
              enter="ease-out duration-300"
              enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              enter-to="opacity-100 translate-y-0 sm:scale-100"
              leave="ease-in duration-200"
              leave-from="opacity-100 translate-y-0 sm:scale-100"
              leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
              <DialogPanel class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[500px] sm:w-full">
                <div class="relative">
                  <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center z-50">
                    <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </div>
                  
                  <!-- Modal Header -->
                  <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <DialogTitle as="h3" class="text-lg font-medium text-gray-900">
                      Change Password
                    </DialogTitle>
                    <button 
                      @click="closeModal"
                      class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 p-1 rounded-full"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                    </button>
                  </div>
                  
                  <!-- Modal Body -->
                  <form @submit.prevent="onSubmit">
                    <div class="p-6 space-y-4">
                      <!-- Current Password -->
                      <div>
                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input
                          id="currentPassword"
                          type="password"
                          v-model="passwordData.current_password"
                          placeholder="Enter your current password"
                          required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                      </div>
                      
                      <!-- New Password -->
                      <div>
                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                          id="newPassword"
                          type="password"
                          v-model="passwordData.new_password"
                          placeholder="Enter new password"
                          required
                          @input="checkPasswordStrength"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        
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
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input
                          id="confirmPassword"
                          type="password"
                          v-model="passwordData.new_password_confirmation"
                          placeholder="Confirm new password"
                          required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <div v-if="passwordMatch === false" class="text-red-500 text-xs mt-1">
                          Passwords do not match
                        </div>
                      </div>
                      
                      <div v-if="errorMessage" class="text-red-600 p-2 bg-red-50 rounded-md text-sm">
                        {{ errorMessage }}
                      </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                      <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="loading || !isFormValid"
                      >
                        Change Password
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
  import store from "../../store";
  
  const props = defineProps({
    modelValue: Boolean
  });
  
  const emit = defineEmits(['update:modelValue', 'close', 'password-changed']);
  
  const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
  });
  
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
  
  const loading = ref(false);
  const errorMessage = ref('');
  
  function closeModal() {
    show.value = false;
    emit('close');
    resetForm();
  }
  
  function resetForm() {
    passwordData.value = {
      current_password: '',
      new_password: '',
      new_password_confirmation: ''
    };
    errorMessage.value = '';
    passwordStrength.value = 0;
    passwordLength.value = false;
    passwordUppercase.value = false;
    passwordLowercase.value = false;
    passwordNumber.value = false;
    passwordSpecial.value = false;
  }
  
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
        emit('password-changed');
        closeModal();
      })
      .catch(err => {
        loading.value = false;
        errorMessage.value = err.response?.data?.message || 'Failed to change password';
        console.error("Password change error:", err);
      });
  }
  </script>