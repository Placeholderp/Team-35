<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Page Header with Stats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Users</h1>
        <p class="text-gray-600 mt-1">Manage system users and permissions</p>
      </div>
      
      <!-- Role Filter Tabs - Made responsive -->
      <div class="mt-4 md:mt-0 bg-white rounded-lg shadow overflow-hidden w-full md:w-auto flex flex-col sm:flex-row">
        <div class="grid grid-cols-3 w-full sm:w-auto">
          <button 
            @click="activeFilter = 'all'" 
            class="px-3 py-2 font-medium text-sm"
            :class="activeFilter === 'all' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-50'"
          >
            All ({{ users.total || 0 }})
          </button>
          <button 
            @click="activeFilter = 'admin'" 
            class="px-3 py-2 font-medium text-sm"
            :class="activeFilter === 'admin' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-50'"
          >
            Admins ({{ adminCount }})
          </button>
          <button 
            @click="activeFilter = 'user'" 
            class="px-3 py-2 font-medium text-sm"
            :class="activeFilter === 'user' ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-50'"
          >
            Users ({{ regularCount }})
          </button>
        </div>
        <button 
          @click="showAddNewModal()" 
          class="px-4 py-2 font-medium text-sm bg-indigo-600 text-white hover:bg-indigo-700 flex items-center justify-center sm:w-auto"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add User
        </button>
      </div>
    </div>
    
    <!-- Table Toolbar with Search and Pagination Controls -->
    <div class="bg-white rounded-lg shadow-md mb-4">
      <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
          <!-- Left side controls -->
          <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center">
              <span class="text-gray-600 mr-2 text-sm">Show</span>
              <select
                @change="getUsers(null)"
                v-model="perPage"
                class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-1.5 px-2 pr-6 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
              >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <span class="ml-2 text-sm text-gray-600">entries</span>
            </div>
            <span class="text-sm text-gray-600">
              {{ filteredUsers.length }} {{ activeFilter !== 'all' ? (activeFilter === 'admin' ? 'admin' : 'regular user') : 'total user' }}{{ filteredUsers.length !== 1 ? 's' : '' }}
            </span>
          </div>
          
          <!-- Right side controls -->
          <div class="relative w-full md:max-w-xs">
            <input
              v-model="search"
              @input="onSearchInputDelayed"
              class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full text-sm"
              placeholder="Search users..."
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Users Table Component -->
    <div v-if="loading" class="bg-white rounded-lg shadow p-8 text-center">
      <div class="flex justify-center">
        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
      </div>
      <p class="mt-4 text-gray-600">Loading users...</p>
    </div>
    
    <div v-else-if="filteredUsers.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">No users found</h3>
      <p class="mt-1 text-sm text-gray-500">
        {{ activeFilter === 'all' ? 'There are no users in the system.' : `No ${activeFilter === 'admin' ? 'admin' : 'regular'} users found.` }}
        {{ search ? `No results match your search: "${search}"` : '' }}
      </p>
    </div>
    
    <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Users Table with responsive columns -->
      <div class="w-full">
        <table class="w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th @click="sortUsers('id')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                ID
                <span v-if="sortField === 'id'" class="ml-1">
                  {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="sortUsers('name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                Name
                <span v-if="sortField === 'name'" class="ml-1">
                  {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="sortUsers('email')" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                Email
                <span v-if="sortField === 'email'" class="ml-1">
                  {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="sortUsers('is_admin')" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                Role
                <span v-if="sortField === 'is_admin'" class="ml-1">
                  {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="sortUsers('created_at')" class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                Created
                <span v-if="sortField === 'created_at'" class="ml-1">
                  {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr 
              v-for="user in filteredUsers" 
              :key="user.id" 
              class="hover:bg-gray-50"
              :class="{'bg-green-50': isCurrentUser(user)}"
            >
              <td class="hidden sm:table-cell px-4 py-4 text-sm font-medium text-gray-900">
                #{{ user.id }}
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-lg">
                      {{ getInitials(user) }}
                    </div>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900 break-all flex items-center">
                      {{ user.name }}
                      <span v-if="isCurrentUser(user)" class="ml-2 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 font-medium">
                        Current User
                      </span>
                    </div>
                    <!-- Show email on mobile only -->
                    <div class="md:hidden text-xs text-gray-500 break-all mt-1">{{ user.email }}</div>
                    <!-- Show role badge on mobile only -->
                    <div class="sm:hidden mt-1">
                      <span 
                        v-if="user.is_admin" 
                        class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800 font-medium"
                      >
                        Admin
                      </span>
                      <span 
                        v-else 
                        class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800 font-medium"
                      >
                        User
                      </span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500 break-all">
                {{ user.email }}
              </td>
              <td class="hidden sm:table-cell px-4 py-4 text-sm">
                <span 
  v-if="user.is_admin === true" 
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
              </td>
              <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-4 py-4 text-sm">
                <div class="flex flex-wrap gap-2">
                  <!-- Current User Label for the actions column -->
                  <span 
                    v-if="isCurrentUser(user)" 
                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Current
                  </span>
                
                  <!-- My Profile button (for current user - navigates to profile page) -->
                  <button
    v-if="isCurrentUser(user)"
    @click="goToProfile()"
    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
  >
    <svg
      class="mr-1 h-3 w-3"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
      />
    </svg>
    My Profile
  </button>
                  
                  <!-- Edit button (for other users) -->
                  <button
                    v-if="!isCurrentUser(user)"
                    @click="editUser(user)"
                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                  >
                    <svg
                      class="mr-1 h-3 w-3"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      />
                    </svg>
                    Edit
                  </button>
                  
                  <!-- Delete button (hidden for current user) -->
                  <button
                    v-if="!isCurrentUser(user)"
                    @click="deleteUser(user)"
                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    :disabled="user.is_admin && adminCount <= 1"
                    :class="{ 'opacity-50 cursor-not-allowed': user.is_admin && adminCount <= 1 }"
                  >
                    <svg
                      class="mr-1 h-3 w-3"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                    Remove
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Simplified Responsive Pagination -->
      <div class="bg-white px-4 py-3 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200">
        <!-- Mobile pagination controls -->
        <div class="w-full sm:hidden mb-3">
          <div class="flex justify-between">
            <button 
              @click="getUsers(users.prev_page_url)"
              :disabled="!users.prev_page_url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="[!users.prev_page_url ? 'opacity-50 cursor-not-allowed' : '']"
            >
              Previous
            </button>
            <button
              @click="getUsers(users.next_page_url)"
              :disabled="!users.next_page_url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="[!users.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
            >
              Next
            </button>
          </div>
        </div>
        
        <!-- Desktop detailed pagination -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing <span class="font-medium">{{ users.from || 0 }}</span>
              to <span class="font-medium">{{ users.to || 0 }}</span>
              of <span class="font-medium">{{ users.total }}</span> results
            </p>
          </div>
          <div v-if="activeFilter === 'all'">
            <nav
              class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
              aria-label="Pagination"
            >
              <template v-for="(link, i) of users.links" :key="i">
                <a
                  href="#"
                  @click.prevent="getForPage($event, link)"
                  :class="[ 
                    'relative inline-flex items-center px-3 py-2 border text-sm font-medium',
                    !link.url ? 'bg-gray-100 text-gray-700 cursor-not-allowed' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : '',
                    i === 0 ? 'rounded-l-md' : '',
                    i === users.links.length - 1 ? 'rounded-r-md' : '',
                  ]"
                >
                  <template v-if="i === 0">
                    <svg
                      class="h-5 w-5"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <span class="sr-only">Previous</span>
                  </template>
                  <template v-else-if="i === users.links.length - 1">
                    <svg
                      class="h-5 w-5"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <span class="sr-only">Next</span>
                  </template>
                  <template v-else>
                    {{ link.label }}
                  </template>
                </a>
              </template>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <!-- User Modal for creating/editing users -->
    <UserModal v-model="showUserModal" :user="userModel" @close="onModalClose" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router"; // Import router
import { debounce } from 'lodash';
import UserModal from "./UserModal.vue";
import store from "../../store";
import { USERS_PER_PAGE } from "../../constants";

const router = useRouter(); // Initialize router

// Define a default user 
const DEFAULT_USER = {
  id: '',
  name: '',
  email: '',
  is_admin: false,
  created_at: ''
};

const userModel = ref({ ...DEFAULT_USER });
const showUserModal = ref(false);
const users = computed(() => store.state.users);
const loading = computed(() => users.value.loading);
const activeFilter = ref('all'); // Options: 'all', 'admin', 'user'
const perPage = ref(USERS_PER_PAGE);
const search = ref("");
const sortField = ref("created_at");
const sortDirection = ref("desc");

// Get the current authenticated user from the store
const currentUser = computed(() => {
  return store.state.user.data;
});

// Helper function to check if a user is the current logged-in user
function isCurrentUser(user) {
  if (!currentUser.value || !currentUser.value.id || !user || !user.id) return false;
  return parseInt(currentUser.value.id) === parseInt(user.id);
}

// Function to navigate to user profile page
function goToProfile() {
  router.push({ name: 'app.profile' }); 
}

// Computed properties for filtered users
const filteredUsers = computed(() => {
  if (!users.value.data) return [];
  
  let filtered = [...users.value.data];
  
  // Apply role filter
  if (activeFilter.value === 'admin') {
    filtered = filtered.filter(user => user.is_admin);
  } else if (activeFilter.value === 'user') {
    filtered = filtered.filter(user => !user.is_admin);
  }
  
  // Apply search filter (if present)
  if (search.value.trim()) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(user => 
      user.name.toLowerCase().includes(searchLower) || 
      user.email.toLowerCase().includes(searchLower)
    );
  }
  
  return filtered;
});

// Computed properties for counts
const adminCount = computed(() => {
  if (!users.value.data) return 0;
  return users.value.data.filter(user => user.is_admin).length;
});

const regularCount = computed(() => {
  if (!users.value.data) return 0;
  return users.value.data.filter(user => !user.is_admin).length;
});

// Use debounce to prevent too many API calls while typing
const onSearchInputDelayed = debounce(() => {
  if (activeFilter.value === 'all') {
    getUsers(null);
  }
}, 500);

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

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function getInitials(user) {
  if (!user || !user.name) return '';
  
  return user.name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
}

function getForPage(ev, link) {
  if (!link.url || link.active) return;
  getUsers(link.url);
}

function getUsers(url = null) {
  if (activeFilter.value !== 'all' && !url) {
    // If we're filtering, don't need to refetch from server
    // Unless we're navigating through pages
    return;
  }
  
  store.dispatch("getUsers", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortUsers(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }
  
  if (activeFilter.value === 'all') {
    getUsers();
  }
}

function deleteUser(user) {
  // Prevent deleting the current user
  if (isCurrentUser(user)) {
    store.commit('showToast', {
      type: 'error',
      message: 'You cannot delete your own account',
      title: 'Error'
    });
    return;
  }
  
  // Prevent deleting the last admin
  if (user.is_admin && adminCount.value <= 1) {
    store.commit('showToast', {
      type: 'error',
      message: 'Cannot delete the last admin user',
      title: 'Error'
    });
    return;
  }

  if (!user || !user.id) {
    console.error("Error: User ID is missing");
    return;
  }

  if (!confirm(`Are you sure you want to remove user "${user.name}"?`)) return;

  store.dispatch("deleteUser", user.id)
    .then(() => {
      store.commit('showToast', {
        type: 'success',
        message: `User "${user.name}" was removed successfully.`,
        title: 'Success'
      });
      store.dispatch("getUsers");
    })
    .catch((err) => {
      console.error('Error removing user:', err);
      store.commit('showToast', {
        type: 'error',
        message: 'Failed to remove the user. Please try again.',
        title: 'Error'
      });
    });
}

// Watch for changes in dropdown filters
watch(perPage, () => {
  getUsers(null);
});

// Watch for changes in role filter
watch(() => activeFilter.value, () => {
  // When filter changes, we might need to refetch users
  if (activeFilter.value === 'all' && (!users.value.data || !users.value.data.length)) {
    getUsers();
  }
});

onMounted(() => {
  // Ensure users are loaded
  if (!users.value.data || !users.value.data.length) {
    store.dispatch('getUsers');
  }
  
  // Make sure we have the current user data
  if (!currentUser.value || !currentUser.value.id) {
    store.dispatch('getCurrentUser');
  }
});
</script>