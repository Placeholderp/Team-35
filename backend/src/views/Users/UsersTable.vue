<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table Toolbar -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <!-- Left side controls -->
        <div class="flex items-center space-x-3">
          <div class="flex items-center">
            <span class="text-gray-600 mr-2">Show</span>
            <select
              @change="getUsers(null)"
              v-model="perPage"
              class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            >
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span class="ml-2 text-sm text-gray-600">entries</span>
          </div>
          <span class="text-sm text-gray-600">{{ users.total }} total users</span>
        </div>
        
        <!-- Right side controls -->
        <div class="relative flex-1 md:max-w-xs">
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

    <!-- Users Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <TableHeaderCell
              field="id"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('id')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              ID
            </TableHeaderCell>
            <TableHeaderCell
              field="name"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('name')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Name
            </TableHeaderCell>
            <TableHeaderCell
              field="email"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('email')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Email
            </TableHeaderCell>
            <TableHeaderCell
              field="created_at"
              :sort-field="sortField"
              :sort-direction="sortDirection"
              @click="sortUsers('created_at')"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Created
            </TableHeaderCell>
            <!-- Updated: use text-left instead of text-right -->
            <TableHeaderCell
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Actions
            </TableHeaderCell>
          </tr>
        </thead>

        <tbody v-if="users.loading || !users.data?.length" class="bg-white divide-y divide-gray-200">
          <tr>
            <td colspan="5" class="px-6 py-10 text-center">
              <Spinner v-if="users.loading" class="mx-auto" />
              <p v-else class="text-gray-500 text-sm">
                No users found
              </p>
            </td>
          </tr>
        </tbody>
        <tbody v-else class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(user, index) of users.data"
            :key="index"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              #{{ user.id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-lg">
                    {{ getInitials(user) }}
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>
            <!-- Updated: remove 'text-right' and 'justify-end' classes -->
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <div class="flex space-x-2">
                <button
                  @click="editUser(user)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                >
                  <svg
                    class="mr-1.5 h-4 w-4"
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
                <button
                  @click="deleteUser(user)"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                >
                  <svg
                    class="mr-1.5 h-4 w-4"
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

    <!-- Pagination -->
    <div
      v-if="!users.loading"
      class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
    >
      <div class="flex-1 flex justify-between sm:hidden">
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
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="[!users.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ users.from || 0 }}</span>
            to <span class="font-medium">{{ users.to || 0 }}</span>
            of <span class="font-medium">{{ users.total }}</span> results
          </p>
        </div>
        <div>
          <nav
            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
          >
            <template v-for="(link, i) of users.links" :key="i">
              <a
                href="#"
                @click.prevent="getForPage($event, link)"
                :class="[ 
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  !link.url ? 'bg-gray-100 text-gray-700 cursor-not-allowed' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : '',
                  i === 0 ? 'rounded-l-md' : '',
                  i === users.links.length - 1 ? 'rounded-r-md' : '',
                ]"
              >
                <!-- Previous page arrow icon -->
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
                
                <!-- Next page arrow icon -->
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
                
                <!-- Regular page numbers -->
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
</template>

<script setup>
import { computed, onMounted, ref, defineEmits, watch } from 'vue'
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
import { USERS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import { debounce } from 'lodash'

const perPage = ref(USERS_PER_PAGE);
const search = ref("");
const users = computed(() => store.state.users);
const sortField = ref("created_at");
const sortDirection = ref("desc");

const emit = defineEmits(["clickEdit"]);

// Use debounce to prevent too many API calls while typing
const onSearchInputDelayed = debounce(() => {
  getUsers(null)
}, 500)

onMounted(() => {
  getUsers();
});

// Watch for changes in dropdown filters
watch(perPage, () => {
  getUsers(null)
})

function getForPage(ev, link) {
  if (!link.url || link.active) return;
  getUsers(link.url);
}

function getUsers(url = null) {
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
  getUsers();
}

function formatDate(dateString) {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function deleteUser(user) {
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
