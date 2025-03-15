<template>
  <nav class="bg-white border-b border-gray-200 z-30 flex h-16 items-center justify-between px-4 py-2 shadow-sm">
    <!-- Left Section: Toggle Sidebar + App Title -->
    <div class="flex items-center space-x-4">
      <!-- Sidebar Toggle Button -->
      <button 
        @click="$emit('toggle-sidebar')" 
        type="button" 
        class="p-2 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        aria-label="Toggle sidebar"
      >
        <MenuIcon class="h-6 w-6" />
      </button>
      
      <!-- App Title -->
      <span class="text-gray-900 font-semibold text-lg hidden md:block">
        Admin Panel
      </span>
    </div>
    
    <!-- Right Section: Notifications + Profile -->
    <div class="flex items-center space-x-4">
      <!-- Notifications -->
      <button class="p-2 rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <BellIcon class="h-6 w-6" />
        <span v-if="notificationCount > 0" class="absolute top-1 right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
          {{ notificationCount > 9 ? '9+' : notificationCount }}
        </span>
      </button>
      
      <!-- Profile Dropdown -->
      <div class="relative ml-3" ref="profileDropdown">
        <!-- Profile Button -->
        <button 
          @click="toggleDropdown" 
          class="flex items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          aria-expanded="false"
          aria-haspopup="true"
        >
          <span class="sr-only">Open user menu</span>
          <img 
            class="h-8 w-8 rounded-full border border-gray-300" 
            :src="user.avatar || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'" 
            :alt="user.name || 'User profile'" 
          />
          <span class="ml-2 text-gray-700 hidden md:block">{{ user.name || 'Admin User' }}</span>
          <ChevronDownIcon class="ml-1 h-4 w-4 text-gray-500" />
        </button>
        
        <!-- Dropdown Menu -->
        <transition
          enter-active-class="transition ease-out duration-100"
          enter-from-class="transform opacity-0 scale-95"
          enter-to-class="transform opacity-100 scale-100"
          leave-active-class="transition ease-in duration-75"
          leave-from-class="transform opacity-100 scale-100"
          leave-to-class="transform opacity-0 scale-95"
        >
          <div 
            v-if="isDropdownOpen" 
            class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="user-menu-button"
          >
            <router-link 
              to="/profile" 
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              @click="isDropdownOpen = false"
              role="menuitem"
            >
              Your Profile
            </router-link>
            <router-link 
              to="/settings" 
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              @click="isDropdownOpen = false"
              role="menuitem"
            >
              Settings
            </router-link>
            <div class="border-t border-gray-100 my-1"></div>
            <button 
              @click="handleLogout" 
              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              role="menuitem"
            >
              Logout
            </button>
          </div>
        </transition>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex'; // Add this import for Vuex store
import { MenuIcon, BellIcon, ChevronDownIcon } from '@heroicons/vue/solid';

// Router and Store
const router = useRouter();
const store = useStore(); // Get the store instance

// Props
const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  }
});

// Reactive state
const isDropdownOpen = ref(false);
const profileDropdown = ref(null);
const notificationCount = ref(3); // Example value

// Methods
function toggleDropdown() {
  isDropdownOpen.value = !isDropdownOpen.value;
}

function handleClickOutside(event) {
  if (
    profileDropdown.value && 
    !profileDropdown.value.contains(event.target) && 
    isDropdownOpen.value
  ) {
    isDropdownOpen.value = false;
  }
}

function handleLogout() {
  isDropdownOpen.value = false;
  
  // Use the store from useStore()
  store.dispatch('logout')
    .then(() => {
      // Show the success notification
      window.$notification.success(
        'You have been successfully logged out', 
        'Logged Out'
      );
      router.push('/login');
    })
    .catch(error => {
      console.error('Logout failed:', error);
      // Show error notification
      window.$notification.error(
        'There was a problem logging you out', 
        'Logout Failed'
      );
      // Still redirect to login
      router.push('/login');
    });
}

// Watch for route changes to close dropdown
watch(
  () => router.currentRoute.value,
  () => {
    isDropdownOpen.value = false;
  }
);

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>