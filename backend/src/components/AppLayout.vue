<template>
  <div v-if="currentUser.id" class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar with transition -->
    <Sidebar 
      :is-collapsed="!sidebarOpened" 
      class="transition-all duration-300 ease-in-out"
      @toggle-collapse="toggleSidebar"
    />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Navbar @toggle-sidebar="toggleSidebar" :user="currentUser" />
      
      <!-- Main Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="container mx-auto">
          <router-view v-slot="{ Component }" :key="$route.fullPath">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </main>
    </div>
  </div>
  
  <!-- Loading State -->
  <div v-else class="min-h-screen bg-gray-100 flex items-center justify-center">
    <Spinner size="large" />
  </div>
  
  <!-- Toast Notifications -->
  <Toast />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Spinner from "./core/Spinner.vue";
import Toast from "./core/Toast.vue";
import store from "../store";

// Props
const props = defineProps({
  title: String
});

// Reactive state
const sidebarOpened = ref(true);

// Computed properties
const currentUser = computed(() => store.state.user.data);

// Methods
function toggleSidebar() {
  sidebarOpened.value = !sidebarOpened.value;
}

// Lifecycle hooks
onMounted(() => {
  // Initialize data
  store.dispatch('getCurrentUser');
  store.dispatch('getCountries');
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>