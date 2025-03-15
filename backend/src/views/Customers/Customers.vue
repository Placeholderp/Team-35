<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Page Header with Stats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Customers</h1>
        <p class="text-gray-600 mt-1">Manage your customer database</p>
      </div>
      
      <!-- Quick Stats Cards -->
      <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-blue-500">
          <div class="text-sm text-gray-500">Total Customers</div>
          <div class="text-2xl font-semibold">{{ customers.total || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-emerald-500">
          <div class="text-sm text-gray-500">Active</div>
          <div class="text-2xl font-semibold">{{ activeCustomers }}</div>
        </div>
        <button 
          @click="showAddNewModal" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg shadow flex items-center transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add Customer
        </button>
      </div>
    </div>
    
    <!-- Customers Table Component -->
    <CustomersTable @clickEdit="editCustomer"/>
    
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
                  <!-- Customer form would go here -->
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
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                      <input 
                        type="text" 
                        v-model="customerModel.phone"
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
                  >
                    {{ customerModel.id ? 'Update Customer' : 'Create Customer' }}
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
import { computed, onMounted, ref } from "vue";
import { useStore } from 'vuex'; // Import useStore
import CustomersTable from "./CustomersTable.vue";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const store = useStore(); // Get store instance

const DEFAULT_CUSTOMER = {
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  status: true
}

const customers = computed(() => store.state.customers);

// Calculate customer statistics
const activeCustomers = computed(() => {
  if (!customers.value.data) return 0;
  return customers.value.data.filter(customer => customer.status).length;
});

const customerModel = ref({...DEFAULT_CUSTOMER});
const showCustomerModal = ref(false);

function showAddNewModal() {
  customerModel.value = {...DEFAULT_CUSTOMER};
  showCustomerModal.value = true;
}

function editCustomer(c) {
  store.dispatch('getCustomer', c.id)
    .then(({data}) => {
      customerModel.value = data;
      showCustomerModal.value = true;
    })
}

function closeModal() {
  showCustomerModal.value = false;
  // Reset after animation completes
  setTimeout(() => {
    customerModel.value = {...DEFAULT_CUSTOMER};
  }, 300);
}

function saveCustomer() {
  if (customerModel.value.id) {
    // Update existing customer
    store.dispatch('updateCustomer', customerModel.value)
      .then(() => {
        store.commit('showToast', 'Customer updated successfully');
        store.dispatch('getCustomers');
        closeModal();
      })
      .catch(error => {
        console.error('Error updating customer:', error);
        store.commit('showToast', 'Error updating customer', 'error');
      });
  } else {
    // Create new customer
    store.dispatch('createCustomer', customerModel.value)
      .then(() => {
        store.commit('showToast', 'Customer created successfully');
        store.dispatch('getCustomers');
        closeModal();
      })
      .catch(error => {
        console.error('Error creating customer:', error);
        store.commit('showToast', 'Error creating customer', 'error');
      });
  }
}

function viewCustomer(customer) {
  router.push({ name: 'app.customers.view', params: { id: customer.id } });
}

onMounted(() => {
  // Ensure customers are loaded
  if (!customers.value.data || !customers.value.data.length) {
    store.dispatch('getCustomers');
  }
});
</script>