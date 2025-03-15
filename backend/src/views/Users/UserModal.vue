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
                  <DialogTitle as="h3" class="text-lg font-medium text-gray-900">
                    {{ user.id ? `Edit User: ${user.name}` : 'Create New User' }}
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
import { ref, computed, watch } from 'vue'
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
  password: ''
})

watch(
  () => props.user,
  (newUser) => {
    user.value = {
      id: newUser.id || null,
      name: newUser.name || '',
      email: newUser.email || '',
      password: ''
    }
  },
  { deep: true, immediate: true }
)

const loading = ref(false)
const errorMessage = ref('')

function closeModal() {
  show.value = false
  emit('close')
  errorMessage.value = ''
}

function onSubmit() {
  loading.value = true
  errorMessage.value = ''
  
  if (user.value.id) {
    // Update user
    store.dispatch('updateUser', user.value)
      .then(response => {
        loading.value = false
        if (response.status === 200) {
          store.commit('showToast', 'User updated successfully')
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
    store.dispatch('createUser', user.value)
      .then(response => {
        loading.value = false
        if (response.status === 201) {
          store.commit('showToast', 'User created successfully')
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