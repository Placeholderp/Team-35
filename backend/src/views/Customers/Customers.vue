<template>
  <div class="container mx-auto px-4 py-6 flex flex-col space-y-6">
    <!-- Header with metrics -->
    <div class="flex flex-col space-y-4">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Customer Management</h1>
          <p class="text-gray-600 mt-1">Manage your customer relationships and data</p>
        </div>
        
      </div>
      
      <!-- Metrics cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow">
          <div class="p-4">
            <div class="flex items-center space-x-4">
              <div class="bg-blue-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                <p class="text-2xl font-bold">{{ customers.total || 0 }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow">
          <div class="p-4">
            <div class="flex items-center space-x-4">
              <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Active</p>
                <p class="text-2xl font-bold">{{ activeCustomers }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow">
          <div class="p-4">
            <div class="flex items-center space-x-4">
              <div class="bg-purple-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">New This Month</p>
                <p class="text-2xl font-bold">{{ newCustomersThisMonth }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Customers Table Component -->
    <CustomersTable @clickEdit="editCustomer"/>
    
    <!-- Integration Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow border-none">
      <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-semibold text-gray-900">Advanced Analytics Integration</h3>
            <p class="text-gray-600 mt-1">Connect your customer data with business intelligence tools</p>
          </div>
          <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow-sm transition-colors">
            Configure Integrations
          </button>
        </div>
      </div>
    </div>

    <!-- Customer Modal -->
    <TransitionRoot appear :show="showCustomerModal" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 p-6 border-b">
                  {{ customerModel.id ? 'Edit Customer' : 'Add New Customer' }}
                </DialogTitle>
                
                <div class="p-6">
                  <!-- Customer form fields -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                      <input 
                        type="text" 
                        v-model="customerModel.first_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                      <input 
                        type="text" 
                        v-model="customerModel.last_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                      <input 
                        type="email" 
                        v-model="customerModel.email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    
                  </div>
                  
                  <div class="flex items-center mb-4">
                    <input 
                      type="checkbox" 
                      id="status" 
                      v-model="customerModel.status"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="status" class="ml-2 block text-sm text-gray-900">Active</label>
                  </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3">
                  <button 
                    type="button" 
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="closeModal"
                  >
                    Cancel
                  </button>
                  <button 
                    type="button" 
                    class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="saveCustomer"
                    :disabled="saving"
                  >
                    <span v-if="saving">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Saving...
                    </span>
                    <span v-else>
                      {{ customerModel.id ? 'Update Customer' : 'Create Customer' }}
                    </span>
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useStore } from 'vuex';
import axiosClient from "../../axios.js"; // Make sure the path is correct
import CustomersTable from "./CustomersTable.vue";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const store = useStore();

// Default empty customer model
const DEFAULT_CUSTOMER = {
  first_name: '',
  last_name: '',
  email: '',
  status: true
};

// Component state
const customers = computed(() => store.state.customers);
const loading = ref(false);
const saving = ref(false);
const customerModel = ref({...DEFAULT_CUSTOMER});
const showCustomerModal = ref(false);

// Calculate customer statistics
const activeCustomers = computed(() => {
  if (!customers.value.data) return 0;
  return customers.value.data.filter(customer => customer.status).length;
});

// Additional metrics for dashboard
const newCustomersThisMonth = computed(() => {
  if (!customers.value.data) return 0;
  const now = new Date();
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
  return customers.value.data.filter(customer => {
    const createdDate = new Date(customer.created_at);
    return createdDate >= startOfMonth;
  }).length;
});

// Show modal for new customer
function showAddNewModal() {
  customerModel.value = {...DEFAULT_CUSTOMER};
  showCustomerModal.value = true;
}

// Edit existing customer
function editCustomer(c) {
  loading.value = true;
  
  // Load detailed customer data by ID
  store.dispatch('getCustomer', c.id)
    .then(response => {
      // Only set basic properties to avoid unintended side effects
      const data = response.data;
      
      customerModel.value = {
        id: data.id,
        first_name: data.first_name || '',
        last_name: data.last_name || '',
        email: data.email || '',
        status: Boolean(data.status)
      };
      
      showCustomerModal.value = true;
      loading.value = false;
    })
    .catch(error => {
      console.error('Error loading customer data:', error);
      store.commit('showToast', 'Error loading customer data', 'error');
      loading.value = false;
    });
}

// Close modal and reset form
function closeModal() {
  showCustomerModal.value = false;
  
  // Reset after animation completes
  setTimeout(() => {
    customerModel.value = {...DEFAULT_CUSTOMER};
  }, 300);
}

// Save customer (create or update)
function saveCustomer() {
  // Validate required fields
  if (!customerModel.value.first_name || !customerModel.value.last_name || !customerModel.value.email) {
    store.commit('showToast', 'Please fill in all required fields', 'error');
    return;
  }
  
  saving.value = true;
  
  // Prepare data for API - just include required fields to prevent validation errors
  const customerData = {
    first_name: customerModel.value.first_name,
    last_name: customerModel.value.last_name,
    email: customerModel.value.email,
    status: Boolean(customerModel.value.status)
  };
  
  // Add ID for updates
  if (customerModel.value.id) {
    customerData.id = customerModel.value.id;
  }
  
  if (customerModel.value.id) {
    // Update existing customer
    store.dispatch('updateCustomer', customerData)
      .then(() => {
        store.commit('showToast', 'Customer updated successfully');
        store.dispatch('getCustomers');
        closeModal();
        saving.value = false;
      })
      .catch(error => {
        console.error('Error updating customer:', error);
        const errorMsg = getErrorMessage(error);
        store.commit('showToast', errorMsg, 'error');
        saving.value = false;
      });
  } else {
    // Create new customer
    store.dispatch('createCustomer', customerData)
      .then(() => {
        store.commit('showToast', 'Customer created successfully');
        store.dispatch('getCustomers');
        closeModal();
        saving.value = false;
      })
      .catch(error => {
        console.error('Error creating customer:', error);
        const errorMsg = getErrorMessage(error);
        store.commit('showToast', errorMsg, 'error');
        saving.value = false;
      });
  }
}

// Navigate to customer detail
function viewCustomer(customer) {
  router.push({ name: 'app.customers.view', params: { id: customer.id } });
}

// Helper to extract error messages from API responses
function getErrorMessage(error) {
  if (error.response?.data?.message) {
    return error.response.data.message;
  }
  
  if (error.response?.data?.errors) {
    return Object.values(error.response.data.errors).flat().join(', ');
  }
  
  return 'An error occurred while processing your request';
}

// Initialize component
onMounted(() => {
  loading.value = true;
  
  // Fetch customer data if needed
  if (!customers.value.data || !customers.value.data.length) {
    store.dispatch('getCustomers')
      .finally(() => {
        loading.value = false;
      });
  } else {
    loading.value = false;
  }
});
</script>