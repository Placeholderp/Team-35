<template>
    <div class="container mx-auto px-4 py-6">
      <!-- Page Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-600 mt-1">Manage your account information</p>
      </div>
      
      <!-- Profile Card -->
      <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
          <button
            @click="editMode = true"
            class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit Profile
          </button>
        </div>
        
        <div class="p-6">
          <div class="flex flex-col md:flex-row">
            <!-- Avatar Section -->
            <div class="md:w-1/4 flex flex-col items-center md:items-start">
              <div class="relative">
                <div v-if="!userData.avatar" class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-3xl">
                  {{ userInitials }}
                </div>
                <img 
                  v-else
                  :src="userData.avatar" 
                  alt="User avatar" 
                  class="h-24 w-24 rounded-full object-cover border-4 border-white shadow"
                />
                <!-- Admin Badge on Avatar -->
                <div 
                  v-if="userData.is_admin" 
                  class="absolute -top-2 -right-2 bg-indigo-100 text-indigo-800 text-xs rounded-full px-2 py-1 border border-white font-medium shadow-sm"
                >
                  Admin
                </div>
              </div>
              
              <!-- User Role -->
              <div class="mt-4 text-center md:text-left">
                <span 
                  v-if="userData.is_admin" 
                  class="px-2.5 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 font-medium"
                >
                  Administrator
                </span>
                <span 
                  v-else 
                  class="px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-medium"
                >
                  Standard User
                </span>
              </div>
            </div>
            
            <!-- User Info -->
            <div class="md:w-3/4 mt-6 md:mt-0 md:ml-6">
              <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Full name</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ userData.name }}</dd>
                </div>
                
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Email address</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ userData.email }}</dd>
                </div>
                
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Account created</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(userData.created_at) }}</dd>
                </div>
                
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(userData.updated_at) }}</dd>
                </div>
              </dl>
              
              <div class="mt-6">
                <button
                  @click="openChangePasswordModal"
                  class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                  Change Password
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Security Tips Card -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Security Tips</h3>
        </div>
        
        <div class="p-6">
          <div class="space-y-4">
            <div class="rounded-md bg-blue-50 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-3 flex-1">
                  <p class="text-sm text-blue-700">
                    We recommend using a strong, unique password for your account. Strong passwords include uppercase and lowercase letters, numbers, and special characters.
                  </p>
                </div>
              </div>
            </div>
            
            <div class="rounded-md bg-blue-50 p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="ml-3 flex-1">
                  <p class="text-sm text-blue-700">
                    Never share your password or account details with anyone. Our staff will never ask for your password.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Edit Profile Modal -->
      <div v-if="editMode" class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto" @click.stop>
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Edit Profile</h3>
            <button 
              @click="editMode = false"
              class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 p-1 rounded-full"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="updateProfile">
            <div class="p-6 space-y-4">
              <div v-if="error" class="rounded-md bg-red-50 p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ error }}</p>
                  </div>
                </div>
              </div>
              
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input 
                  type="text" 
                  id="name" 
                  v-model="editForm.name" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  required
                />
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input 
                  type="email" 
                  id="email" 
                  v-model="editForm.email" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  required
                />
              </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
              <button
                type="button"
                @click="editMode = false"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <span v-if="loading" class="inline-flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Saving...
                </span>
                <span v-else>Save Changes</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  
  // Store
  const store = useStore();
  const router = useRouter();
  
  // Reactive state
  const editMode = ref(false);
  const loading = ref(false);
  const error = ref('');
  const editForm = ref({
    name: '',
    email: ''
  });
  
  // Computed properties
  const userData = computed(() => store.state.user.data || {});
  
  // Get user initials for avatar
  const userInitials = computed(() => {
    if (!userData.value || !userData.value.name) return '';
    
    return userData.value.name
      .split(' ')
      .map(word => word.charAt(0).toUpperCase())
      .slice(0, 2)
      .join('');
  });
  
  // Initialize form data when user data changes
  watch(() => userData.value, (newValue) => {
    if (newValue) {
      editForm.value = {
        name: newValue.name || '',
        email: newValue.email || ''
      };
    }
  }, { immediate: true });
  
  // Methods
  function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    }).format(date);
  }
  
  function openChangePasswordModal() {
    // Find the AppLayout component and trigger the password modal
    const app = document.getElementById('app');
    if (app && app.__vue_app__) {
      // Use global events to communicate with parent 
      router.push({ 
        name: router.currentRoute.value.name,
        query: { changePassword: 'true' }
      });
    } else {
      // Fallback: use the event bus
      store.commit('showToast', {
        type: 'info',
        message: 'The password change feature is not available right now',
        title: 'Info'
      });
    }
  }
  
  function updateProfile() {
    // Reset error
    error.value = '';
    loading.value = true;
    
    // Create user data object with ID and preserving admin status
    const updatedUser = {
      ...editForm.value,
      id: userData.value.id,
      is_admin: userData.value.is_admin // Preserve admin status
    };
    
    // Update the user
    store.dispatch('updateUser', updatedUser)
      .then(response => {
        loading.value = false;
        editMode.value = false;
        
        // Update user in store
        store.commit('setUser', {
          ...userData.value,
          ...editForm.value
        });
        
        // Show success message
        store.commit('showToast', {
          type: 'success',
          message: 'Profile updated successfully',
          title: 'Success'
        });
      })
      .catch(err => {
        loading.value = false;
        
        if (err.response?.data?.message) {
          error.value = err.response.data.message;
        } else if (err.response?.status === 422) {
          // Validation errors
          const validationErrors = err.response.data.errors;
          const firstError = Object.values(validationErrors)[0]?.[0];
          error.value = firstError || 'Validation error';
        } else {
          error.value = 'Failed to update profile. Please try again.';
        }
      });
  }
  
  // Lifecycle hooks
  onMounted(() => {
    // If user data is not already loaded, fetch it
    if (!userData.value || !userData.value.id) {
      store.dispatch('getCurrentUser');
    }
    
    // Check if we need to open password modal from query params
    if (router.currentRoute.value.query.changePassword === 'true') {
      // ... handle this case if needed
    }
  });
  </script>