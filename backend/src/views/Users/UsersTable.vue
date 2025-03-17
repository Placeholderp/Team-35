<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table Toolbar with Filter Controls -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col space-y-4">
        <!-- Top row for filter controls -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Left side controls (aligned to left) -->
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
          </div>
          
          <!-- Right side controls (search) -->
          <div class="relative">
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
        
        <!-- Stats row -->
        <div class="text-sm text-gray-600">
          {{ filteredUsers.length }} {{ roleFilter !== 'all' ? (roleFilter === 'admin' ? 'admin' : 'regular user') : 'total user' }}{{ filteredUsers.length !== 1 ? 's' : '' }}
        </div>
      </div>
    </div>

    <!-- Users Table - No overflows -->
    <div class="w-full">
      <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <!-- Add responsive classes to hide less important columns on small screens -->
            <TableHeaderCell
              field="id"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('id')"
              class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              ID
            </TableHeaderCell>
            <TableHeaderCell
              field="name"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('name')"
              class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Name
            </TableHeaderCell>
            <TableHeaderCell
              field="email"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('email')"
              class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Email
            </TableHeaderCell>
            <TableHeaderCell
              field="role"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('is_admin')"
              class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Role
            </TableHeaderCell>
            <TableHeaderCell
              field="created_at"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('created_at')"
              class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Created
            </TableHeaderCell>
            <TableHeaderCell
              class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </TableHeaderCell>
          </tr>
        </thead>

        <tbody v-if="loading || !filteredUsers.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="6" class="px-4 py-10 text-center">
              <Spinner v-if="loading" class="mx-auto" />
              <p v-else class="text-gray-500 text-sm">
                No users found{{ roleFilter !== 'all' ? ' with the selected filter' : '' }}
              </p>
            </td>
          </tr>
        </tbody>
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(user, index) of filteredUsers"
            :key="index"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- Also hide the same columns in the table body -->
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
                  <div class="text-sm font-medium text-gray-900 break-all">{{ user.name }}</div>
                  <!-- Show email on mobile only (since we hide the email column) -->
                  <div class="md:hidden text-xs text-gray-500 break-all mt-1">{{ user.email }}</div>
                  <!-- Show role badge on mobile only (since we hide the role column) -->
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
            </td>
            <td class="hidden lg:table-cell px-4 py-4 text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>
            <td class="px-4 py-4 text-sm">
              <div class="flex flex-wrap gap-2">
                <button
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
                
                <!-- Current User Account Badge - Hardcoded emails for testing -->
                <span 
                  v-if="user.email === loggedInUserEmail || user.email === 'admin@example.com'"
                  class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Current
                </span>
                
                <!-- Delete Button (Hidden for current user) -->
                <button
                  v-else
                  @click="deleteUser(user)"
                  class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                  :disabled="user.is_admin && allAdmins.length <= 1"
                  :class="{ 'opacity-50 cursor-not-allowed': user.is_admin && allAdmins.length <= 1 }"
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

    <!-- Simplified Pagination -->
    <div
      v-if="!loading"
      class="bg-white px-4 py-3 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200"
    >
      <!-- Simple pagination stats -->
      <div class="text-sm text-gray-700 w-full sm:w-auto text-center sm:text-left mb-3 sm:mb-0">
        Showing <span class="font-medium">{{ filteredUsers.length ? 1 : 0 }}</span>
        to <span class="font-medium">{{ filteredUsers.length }}</span>
        of <span class="font-medium">{{ filteredUsers.length }}</span> results
      </div>
      
      <!-- Simplified pagination for all screens -->
      <div class="flex justify-center sm:justify-end w-full sm:w-auto">
        <nav class="relative z-0 inline-flex shadow-sm -space-x-px">
          <button 
            @click="getUsers(users.prev_page_url)"
            :disabled="!users.prev_page_url"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="[!users.prev_page_url ? 'opacity-50 cursor-not-allowed' : '']"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Current page indicator (simplified) -->
          <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium">
            Page {{ users.current_page || 1 }}
          </span>
          
          <button
            @click="getUsers(users.next_page_url)"
            :disabled="!users.next_page_url"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="[!users.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, defineEmits, watch } from 'vue'
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
import { USERS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import { debounce } from 'lodash'

const props = defineProps({
  roleFilter: {
    type: String,
    default: 'all'
  }
});

const perPage = ref(USERS_PER_PAGE);
const search = ref("");
const users = computed(() => store.state.users);
const loading = computed(() => users.value.loading);
const sortField = ref("created_at");
const sortDirection = ref("desc");

// Replace this with your actual email
const loggedInUserEmail = "your@email.com"; 

const emit = defineEmits(["clickEdit"]);

// All admins in the system (used to prevent deleting last admin)
const allAdmins = computed(() => {
  if (!users.value.data) return [];
  return users.value.data.filter(user => user.is_admin);
});

// Filtered users based on roleFilter prop
const filteredUsers = computed(() => {
  if (!users.value.data) return [];
  
  let filtered = [...users.value.data];
  
  // Apply role filter
  if (props.roleFilter === 'admin') {
    filtered = filtered.filter(user => user.is_admin);
  } else if (props.roleFilter === 'user') {
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

// Use debounce to prevent too many API calls while typing
const onSearchInputDelayed = debounce(() => {
  if (props.roleFilter === 'all') {
    getUsers(null);
  }
}, 500);

onMounted(() => {
  getUsers();
});

// Watch for changes in dropdown filters
watch(perPage, () => {
  getUsers(null);
});

// Watch for changes in role filter
watch(() => props.roleFilter, () => {
  // When filter changes, we might need to refetch users
  if (props.roleFilter === 'all' && (!users.value.data || !users.value.data.length)) {
    getUsers();
  }
});

function getForPage(ev, link) {
  if (!link.url || link.active) return;
  getUsers(link.url);
}

function getUsers(url = null) {
  if (props.roleFilter !== 'all') {
    // If we're filtering, don't need to refetch from server
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
  
  if (props.roleFilter === 'all') {
    getUsers();
  }
}

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function deleteUser(user) {
  // Prevent deleting the current user
  if (user.email === loggedInUserEmail || user.email === 'admin@example.com') {
    store.commit('showToast', {
      type: 'error',
      message: 'You cannot delete your own account',
      title: 'Error'
    });
    return;
  }

  // Still maintains the check for last admin user for security
  if (user.is_admin && allAdmins.length <= 1) {
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
      getUsers();
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

function editUser(user) {
  emit("clickEdit", user);
}

function getInitials(user) {
  if (!user || !user.name) return '';
  
  return user.name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
}
</script>

<style>
/* You can remove this style block since we don't need overflow-x-auto anymore */
</style>