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
            <DialogPanel class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[600px] sm:w-full">
              <div class="relative">
                <Spinner 
                  v-if="loading"
                  class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50 bg-opacity-70" 
                />
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                  <div>
                    <DialogTitle as="h3" class="text-lg font-medium text-gray-900">
                      {{ user.id ? `Edit User: ${user.name}` : 'Create New User' }}
                    </DialogTitle>
                    <!-- Display Role Badge -->
                    <div v-if="user.id" class="mt-1">
                      <span 
                        v-if="user.is_admin" 
                        class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 font-medium"
                      >
                        Admin
                      </span>
                      <span 
                        v-else 
                        class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 font-medium"
                      >
                        User
                      </span>
                    </div>
                  </div>
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
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                      <input
                        id="name"
                        type="text"
                        v-model="user.name"
                        placeholder="Enter full name"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      />
                    </div>
                    
                    <div>
                      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                      <input
                        id="email"
                        type="email"
                        v-model="user.email"
                        placeholder="Enter email address"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      />
                    </div>
                    
                    <div>
                      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password {{ user.id ? '(Leave blank to keep current)' : '' }}
                      </label>
                      <input
                        id="password"
                        type="password"
                        v-model="user.password"
                        :placeholder="user.id ? 'Enter new password' : 'Enter password'"
                        :required="!user.id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      />
                    </div>
                    
                    <!-- Admin Status Display Section -->
                    <div class="mt-4">
                      <div class="flex items-center justify-between">
                        <div>
                          <label class="block text-sm font-medium text-gray-700">Admin Status</label>
                          <div class="flex flex-col space-y-2 mt-2">
                            <p class="text-sm text-gray-700">
                              Current status: 
                              <span class="font-medium" :class="isAdmin ? 'text-indigo-600' : 'text-gray-700'">
                                {{ isAdmin ? 'Admin' : 'User' }}
                              </span>
                            </p>
                            
                            <!-- Toggle Switch for Admin Rights - Only visible for admin users -->
                            <div v-if="isCurrentUserAdmin" class="flex items-center">
                              <span class="text-sm text-gray-500 mr-3">User</span>
                              <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                <input 
                                  id="is_admin" 
                                  type="checkbox" 
                                  v-model="isAdmin"
                                  class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                />
                                <label 
                                  for="is_admin" 
                                  class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                ></label>
                              </div>
                              <span class="text-sm text-gray-500 ml-3">Admin</span>
                            </div>
                            <p v-else class="text-sm text-gray-500 italic">
                              Only administrators can change user permissions
                            </p>
                          </div>
                        </div>
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
                      :disabled="loading"
                    >
                      {{ user.id ? 'Update User' : 'Create User' }}
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
import { ref, computed, watch, onMounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import Spinner from "../../components/core/Spinner.vue"
import store from "../../store/index.js"

const props = defineProps({
  modelValue: Boolean,
  user: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const user = ref({
  id: props.user.id || null,
  name: props.user.name || '',
  email: props.user.email || '',
  password: '',
  is_admin: props.user.is_admin || false
})

watch(
  () => props.user,
  (newUser) => {
    // Initialize the form with values from the provided user
    user.value = {
      id: newUser.id || null,
      name: newUser.name || '',
      email: newUser.email || '',
      password: '',
      is_admin: newUser.is_admin || false
    }
  },
  { deep: true, immediate: true }
)

const loading = ref(false)
const errorMessage = ref('')

// Determine admin status based on backend response
const isAdmin = ref(user.value.is_admin || false)

const isCurrentUserAdmin = computed(() => {
  try {
    return store.state.user && 
           store.state.user.data && 
           store.state.user.data.is_admin === true;
  } catch (e) {
    console.error("Error checking admin status:", e);
    return false;
  }
})
// Fetch user role from database when email changes
watch(() => user.value.email, async (newEmail) => {
  if (!newEmail || newEmail.trim() === '') return;
  
  try {
    // Check if we're editing an existing user (in which case we already know their admin status)
    if (user.value.id) {
      isAdmin.value = user.value.is_admin;
      return;
    }
    
    // For new users, check if email exists in database
    loading.value = true;
    errorMessage.value = '';
    
    // Use the existing users list to check for email
    const existingUsers = store.state.users.data;
    const existingUser = existingUsers.find(u => u.email.toLowerCase() === newEmail.toLowerCase());
    
    if (existingUser) {
      isAdmin.value = existingUser.is_admin;
      errorMessage.value = 'User with this email already exists in the system.';
    } else {
      // Reset admin status for new users if email doesn't exist
      isAdmin.value = false;
    }
  } catch (error) {
    console.error('Error checking user role:', error);
  } finally {
    loading.value = false;
  }
}, { immediate: true })

function closeModal() {
  show.value = false
  emit('close')
  errorMessage.value = ''
}

async function onSubmit() {
  loading.value = true
  errorMessage.value = ''
  
  // Create a copy of the user data for submission
  const userData = { ...user.value };
  
  // Only allow admin status changes if the current user is an admin
  if (isCurrentUserAdmin.value) {
    userData.is_admin = isAdmin.value;
  } else {
    // If not an admin, use the original value without allowing changes
    userData.is_admin = props.user.is_admin || false;
  }
  
  // If updating an existing user and password is empty, remove it from the data to be sent
  if (userData.id && !userData.password) {
    delete userData.password;
  }
  
  if (userData.id) {
    // Update user
    store.dispatch('updateUser', userData)
      .then(response => {
        loading.value = false
        if (response.status === 200) {
          const roleChanged = props.user.is_admin !== userData.is_admin;
          let message = 'User updated successfully';
          if (roleChanged && isCurrentUserAdmin.value) {
            message += userData.is_admin ? ' and promoted to Admin' : ' and changed to regular User';
          }
          store.commit('showToast', message)
          store.dispatch('getUsers')
          closeModal()
        }
      })
      .catch(err => {
        loading.value = false
        errorMessage.value = err.response?.data?.message || 'Failed to update user'
        console.error("Update error:", err)
      })
  } else {
    // Create new user
    store.dispatch('createUser', userData)
      .then(response => {
        loading.value = false
        if (response.status === 201) {
          let message = 'User created successfully';
          if (userData.is_admin && isCurrentUserAdmin.value) {
            message += ' with Admin privileges';
          }
          store.commit('showToast', message)
          store.dispatch('getUsers')
          closeModal()
        }
      })
      .catch(err => {
        loading.value = false
        errorMessage.value = err.response?.data?.message || 'Failed to create user'
        console.error("Creation error:", err)
      })
  }
}
</script>

<style scoped>
/* Toggle switch styles */
.toggle-checkbox:checked {
  right: 0;
  border-color: #4F46E5;
}
.toggle-checkbox:checked + .toggle-label {
  background-color: #4F46E5;
}
.toggle-label {
  transition: background-color 0.2s ease;
  position: relative;
}
</style>