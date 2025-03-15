<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Back button and page header -->
    <div class="flex items-center mb-6">
      <router-link :to="{ name: 'app.customers' }" class="flex items-center text-indigo-600 hover:text-indigo-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to Customers
      </router-link>
    </div>

    <div v-if="loading" class="flex justify-center my-12">
      <Spinner class="w-12 h-12 text-indigo-600" />
    </div>

    <div v-else-if="customer.id" class="animate-fade-in-down">
      <form @submit.prevent="onSubmit">
        <!-- Customer Header Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
          <div class="flex justify-between items-center p-6 bg-gray-50 border-b border-gray-200">
            <div>
              <h1 class="text-2xl font-bold text-gray-800">{{ title }}</h1>
              <p class="text-gray-600 mt-1" v-if="customer.created_at">Customer since {{ formatDate(customer.created_at) }}</p>
            </div>
            <div>
              <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full"
                    :class="customer.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                {{ customer.status ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Customer Information Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
          <div class="p-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Customer Information</h2>
          </div>
          
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input
                  type="text"
                  v-model="customer.first_name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input
                  type="text"
                  v-model="customer.last_name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  type="email"
                  v-model="customer.email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input
                  type="text"
                  v-model="customer.phone"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
              </div>
              <div class="flex items-center">
                <input
                  type="checkbox"
                  id="status"
                  v-model="customer.status"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="status" class="ml-2 block text-sm text-gray-900">Active</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Address Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <!-- Billing Address -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
              <h2 class="text-lg font-medium text-gray-900">Billing Address</h2>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                  <input
                    type="text"
                    v-model="customer.billingAddress.address1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                  <input
                    type="text"
                    v-model="customer.billingAddress.address2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                  <input
                    type="text"
                    v-model="customer.billingAddress.city"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                  <input
                    type="text"
                    v-model="customer.billingAddress.zipcode"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                  <select
                    v-model="customer.billingAddress.country_code"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option v-for="country in countries" :key="country.key" :value="country.key">{{ country.text }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                  <select
                    v-if="billingCountry && billingCountry.states"
                    v-model="customer.billingAddress.state"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option v-for="state in billingStateOptions" :key="state.key" :value="state.key">{{ state.text }}</option>
                  </select>
                  <input
                    v-else
                    type="text"
                    v-model="customer.billingAddress.state"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Shipping Address -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
              <h2 class="text-lg font-medium text-gray-900">Shipping Address</h2>
              <button 
                type="button"
                @click="copyBillingToShipping"
                class="text-sm text-indigo-600 hover:text-indigo-900"
              >
                Copy from Billing
              </button>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                  <input
                    type="text"
                    v-model="customer.shippingAddress.address1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                  <input
                    type="text"
                    v-model="customer.shippingAddress.address2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                  <input
                    type="text"
                    v-model="customer.shippingAddress.city"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                  <input
                    type="text"
                    v-model="customer.shippingAddress.zipcode"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                  <select
                    v-model="customer.shippingAddress.country_code"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option v-for="country in countries" :key="country.key" :value="country.key">{{ country.text }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                  <select
                    v-if="shippingCountry && shippingCountry.states"
                    v-model="customer.shippingAddress.state"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  >
                    <option v-for="state in shippingStateOptions" :key="state.key" :value="state.key">{{ state.text }}</option>
                  </select>
                  <input
                    v-else
                    type="text"
                    v-model="customer.shippingAddress.state"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
          <router-link
            :to="{ name: 'app.customers' }"
            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </router-link>
          <button
            type="submit"
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="loading"
          >
            <span v-if="loading">Saving...</span>
            <span v-else>Save Customer</span>
          </button>
        </div>
      </form>
    </div>
    
    <div v-else class="bg-white rounded-lg shadow-md p-6 text-center">
      <p class="text-gray-500">Customer not found or still loading...</p>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import store from "../../store";
import { useRoute, useRouter } from "vue-router";
import Spinner from "../../components/core/Spinner.vue";

const router = useRouter();
const route = useRoute();

const title = ref('');
const customer = ref({
  billingAddress: {},
  shippingAddress: {}
});
const loading = ref(false);
const saving = ref(false);

const countries = computed(() => store.state.countries.map(c => ({key: c.code, text: c.name})));
const billingCountry = computed(() => store.state.countries.find(c => c.code === customer.value.billingAddress.country_code));
const billingStateOptions = computed(() => {
  if (!billingCountry.value || !billingCountry.value.states) return [];
  return Object.entries(billingCountry.value.states).map(c => ({key: c[0], text: c[1]}));
});
const shippingCountry = computed(() => store.state.countries.find(c => c.code === customer.value.shippingAddress.country_code));
const shippingStateOptions = computed(() => {
  if (!shippingCountry.value || !shippingCountry.value.states) return [];
  return Object.entries(shippingCountry.value.states).map(c => ({key: c[0], text: c[1]}));
});

function onSubmit() {
  saving.value = true;
  
  // Ensure status is boolean
  customer.value.status = !!customer.value.status;
  
  if (customer.value.id) {
    store.dispatch('updateCustomer', customer.value)
      .then(response => {
        saving.value = false;
        if (response.status === 200) {
          store.commit('showToast', 'Customer updated successfully');
          store.dispatch('getCustomers');
          router.push({name: 'app.customers'});
        }
      })
      .catch(error => {
        saving.value = false;
        console.error('Error updating customer:', error);
        store.commit('showToast', 'Error updating customer', 'error');
      });
  } else {
    store.dispatch('createCustomer', customer.value)
      .then(response => {
        saving.value = false;
        if (response.status === 201) {
          store.commit('showToast', 'Customer created successfully');
          store.dispatch('getCustomers');
          router.push({name: 'app.customers'});
        }
      })
      .catch(error => {
        saving.value = false;
        console.error('Error creating customer:', error);
        store.commit('showToast', 'Error creating customer', 'error');
      });
  }
}

function copyBillingToShipping() {
  // Deep clone to ensure we don't have reference issues
  customer.value.shippingAddress = JSON.parse(JSON.stringify(customer.value.billingAddress));
}

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

// Initialize component
onMounted(() => {
  loading.value = true;
  
  // Fetch customer data if we have an ID
  if (route.params.id) {
    store.dispatch('getCustomer', route.params.id)
      .then(({data}) => {
        title.value = `Update customer: "${data.first_name} ${data.last_name}"`;
        customer.value = data;
        loading.value = false;
      })
      .catch(error => {
        console.error('Error fetching customer:', error);
        loading.value = false;
        store.commit('showToast', 'Error loading customer data', 'error');
      });
  } else {
    // Handle new customer
    title.value = 'Add New Customer';
    customer.value = {
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      status: true,
      billingAddress: {
        address1: '',
        address2: '',
        city: '',
        state: '',
        zipcode: '',
        country_code: ''
      },
      shippingAddress: {
        address1: '',
        address2: '',
        city: '',
        state: '',
        zipcode: '',
        country_code: ''
      }
    };
    loading.value = false;
  }
  
  // Ensure countries are loaded
  if (!store.state.countries.length) {
    store.dispatch('getCountries');
  }
});
</script>