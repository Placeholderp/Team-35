<template>
  <div id="app-layout" v-if="currentUser.id" class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar with transition -->
    <Sidebar 
      :is-collapsed="!sidebarOpened" 
      class="transition-all duration-300 ease-in-out"
      @toggle-collapse="toggleSidebar"
    />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Navbar 
        @toggle-sidebar="toggleSidebar" 
        @change-password="showPasswordModal = true"
        :user="currentUser" 
      />
      
      <!-- Main Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="container mx-auto">
          <router-view 
            v-slot="{ Component }" 
            :key="$route.fullPath"
            @change-password="showPasswordModal = true"
          >
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </main>
      
      <!-- Password Change Modal -->
      <ChangePasswordModal
        v-model="showPasswordModal"
        @password-changed="onPasswordChanged"
        @close="showPasswordModal = false"
      />
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
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Spinner from "./core/Spinner.vue";
import Toast from "./core/Toast.vue";
import store from "../store";
import ChangePasswordModal from './auth/ChangePasswordModal.vue'; 

// Props
const props = defineProps({
  title: String
});

// Router
const route = useRoute();

// Reactive state
const sidebarOpened = ref(true);
const showPasswordModal = ref(false);

// Computed properties
const currentUser = computed(() => store.state.user.data);

// Methods
function toggleSidebar() {
  sidebarOpened.value = !sidebarOpened.value;
}

function onPasswordChanged() {
  store.commit('showToast', {
    type: 'success',
    message: 'Password changed successfully',
    title: 'Success'
  });
}

// Watch for route query params to handle password modal
watch(() => route.query.changePassword, (value) => {
  if (value === 'true') {
    showPasswordModal.value = true;
  }
}, { immediate: true });

// Expose method to open password modal
defineExpose({
  openPasswordModal: () => {
    showPasswordModal.value = true;
  }
});

// Lifecycle hooks
onMounted(() => {
  // Initialize data
  store.dispatch('getCurrentUser');
  store.dispatch('getCountries');
  
  // Check if we need to open password modal from query
  if (route.query.changePassword === 'true') {
    showPasswordModal.value = true;
  }
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