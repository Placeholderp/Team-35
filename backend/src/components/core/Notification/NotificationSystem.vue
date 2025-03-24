<template>
  <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-50">
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
      <TransitionGroup 
        name="notification"
        tag="div"
        class="max-w-sm w-full"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
          :class="{
            'border-l-4 border-green-500': notification.type === 'success',
            'border-l-4 border-red-500': notification.type === 'error',
            'border-l-4 border-blue-500': notification.type === 'info',
            'border-l-4 border-yellow-500': notification.type === 'warning',
          }"
        >
          <div class="p-4">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <!-- Success icon -->
                <svg v-if="notification.type === 'success'" class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <!-- Error icon -->
                <svg v-if="notification.type === 'error'" class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <!-- Info icon -->
                <svg v-if="notification.type === 'info'" class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <!-- Warning icon -->
                <svg v-if="notification.type === 'warning'" class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ notification.message }}</p>
              </div>
              
              <div class="ml-4 flex-shrink-0 flex">
                <button
                  @click="removeNotification(notification.id)"
                  class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <span class="sr-only">Close</span>
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Progress bar for auto-dismissal -->
          <div 
            v-if="notification.autoClose"
            class="bg-gray-100 h-1"
          >
            <div 
              class="h-full transition-all duration-75"
              :class="{
                'bg-green-500': notification.type === 'success',
                'bg-red-500': notification.type === 'error',
                'bg-blue-500': notification.type === 'info',
                'bg-yellow-500': notification.type === 'warning',
              }"
              :style="{ width: `${getProgressPercentage(notification)}%` }"
            ></div>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { TransitionGroup } from 'vue';

const props = defineProps({
  timeout: {
    type: Number,
    default: 5000
  }
});

const notifications = ref([]);
let nextId = 1;
let timerId = null;

function getProgressPercentage(notification) {
  if (!notification.autoClose) return 100;
  
  const elapsed = Date.now() - notification.createdAt;
  const timeLeft = notification.duration - elapsed;
  
  return Math.max(0, (timeLeft / notification.duration) * 100);
}

function addNotification(notification) {
  const id = nextId++;
  const createdAt = Date.now();
  const autoClose = notification.autoClose !== false;
  const duration = notification.duration || props.timeout;
  
  notifications.value.push({
    id,
    createdAt,
    autoClose,
    duration,
    ...notification
  });
  
  if (autoClose) {
    setTimeout(() => {
      removeNotification(id);
    }, duration);
  }
  
  return id;
}

function removeNotification(id) {
  const index = notifications.value.findIndex(n => n.id === id);
  if (index !== -1) {
    notifications.value.splice(index, 1);
  }
}

function success(message, title = 'Success', options = {}) {
  return addNotification({
    type: 'success',
    message,
    title,
    ...options
  });
}

function error(message, title = 'Error', options = {}) {
  return addNotification({
    type: 'error',
    message,
    title,
    ...options
  });
}

function info(message, title = 'Information', options = {}) {
  return addNotification({
    type: 'info',
    message,
    title,
    ...options
  });
}

function warning(message, title = 'Warning', options = {}) {
  return addNotification({
    type: 'warning',
    message,
    title,
    ...options
  });
}

function updateProgress() {
  timerId = requestAnimationFrame(updateProgress);
}

onMounted(() => {
  timerId = requestAnimationFrame(updateProgress);
});

onUnmounted(() => {
  if (timerId) {
    cancelAnimationFrame(timerId);
  }
});

// Expose the notification methods
const notificationMethods = {
  success,
  error,
  info,
  warning,
  removeNotification
};

// Export methods for composition API usage
defineExpose(notificationMethods);

// Register global methods
if (typeof window !== 'undefined') {
  window.$notification = notificationMethods;
}
</script>

<style scoped>
.notification-enter-active, 
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.notification-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}
</style>