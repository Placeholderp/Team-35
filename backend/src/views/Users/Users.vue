<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Page Header with Stats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Users</h1>
        <p class="text-gray-600 mt-1">Manage system users and permissions</p>
      </div>
      
      <!-- Quick Stats Cards -->
      <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
        <div class="bg-white rounded-lg shadow px-4 py-3 border-l-4 border-blue-500">
          <div class="text-sm text-gray-500">Total Users</div>
          <div class="text-2xl font-semibold">{{ users.total || 0 }}</div>
        </div>
        <button 
          @click="showAddNewModal()" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg shadow flex items-center transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add New User
        </button>
      </div>
    </div>
    
    <!-- Users Table Component -->
    <UsersTable @clickEdit="editUser" />
    
    <!-- User Modal for creating/editing users -->
    <UserModal v-model="showUserModal" :user="userModel" @close="onModalClose" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import UserModal from "./UserModal.vue";
import UsersTable from "./UsersTable.vue";
import store from "../../store";

// Define a default user 
const DEFAULT_USER = {
  id: '',
  name: '',
  email: '',
  created_at: ''
};

const userModel = ref({ ...DEFAULT_USER });
const showUserModal = ref(false);
const users = computed(() => store.state.users);

function showAddNewModal() {
  userModel.value = { ...DEFAULT_USER };
  showUserModal.value = true;
}

function editUser(user) {
  userModel.value = user;
  showUserModal.value = true;
}

function onModalClose() {
  userModel.value = { ...DEFAULT_USER };
}

onMounted(() => {
  // Ensure users are loaded
  if (!users.value.data || !users.value.data.length) {
    store.dispatch('getUsers');
  }
});
</script>