<template>
  <transition
    enter-active-class="transform transition duration-300 ease-out"
    enter-from-class="translate-y-2 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transform transition duration-200 ease-in"
    leave-from-class="translate-y-0 opacity-100"
    leave-to-class="translate-y-2 opacity-0"
  >
    <div
      v-show="toast.show && isActiveInstance"
      :class="[
        'fixed left-1/2 -translate-x-1/2 py-3 px-4 rounded-lg shadow-lg max-w-md w-[calc(100%-2rem)] z-50',
        positionClass,
        typeClasses[toast.type || 'success']
      ]"
      role="alert"
      aria-live="assertive"
    >
      <div class="flex items-start">
        <!-- Icon based on type -->
        <div class="flex-shrink-0" v-if="toast.type !== 'custom'">
          <component :is="typeIcons[toast.type || 'success']" class="h-5 w-5" aria-hidden="true" />
        </div>
        
        <!-- Content -->
        <div class="ml-3 w-0 flex-1">
          <div v-if="toast.title" class="font-semibold text-sm">{{ toast.title }}</div>
          <div class="text-sm">{{ toast.message }}</div>
        </div>
        
        <!-- Close button -->
        <div class="ml-4 flex-shrink-0 flex">
          <button
            @click="close"
            class="inline-flex rounded-md text-current focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500"
            aria-label="Close notification"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Progress bar -->
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/10 overflow-hidden rounded-b-lg">
        <div
          class="h-full bg-white/30 transition-all duration-75"
          :style="{'width': `${percent}%`}"
        ></div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { h, computed, ref, watch, onMounted, onUnmounted } from "vue";
import store from "../../store/index.js";

// Track toast instances to ensure only one is active
const toastInstanceId = Symbol('toast-instance');
let activeToastInstance = null;
const isActiveInstance = ref(false);

// Import icons (if using @heroicons/vue or similar)
// If not using a icon library, you can use the built-in SVG template
const CheckCircleIcon = {
  render() {
    return h('svg', {
      xmlns: 'http://www.w3.org/2000/svg',
      class: 'h-5 w-5',
      viewBox: '0 0 20 20',
      fill: 'currentColor'
    }, [
      h('path', {
        'fill-rule': 'evenodd',
        d: 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
        'clip-rule': 'evenodd'
      })
    ])
  }
};

const ExclamationCircleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
  </svg>`
};

const InformationCircleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
  </svg>`
};

const XCircleIcon = {
  template: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
  </svg>`
};

// Props for Toast position and customization
// Also update the position prop to remove the 'bottom' option:
const props = defineProps({
  position: {
    type: String,
    default: 'top',
    validator: (value) => ['top'].includes(value)
  },
  // Add a priority prop to determine which toast instance takes precedence
  priority: {
    type: Number,
    default: 0
  }
});

// Timer references for cleanup
let interval = null;
let timeout = null;

// Reactive state
const percent = ref(0);

// Computed properties
const toast = computed(() => store.state.toast);

const positionClass = computed(() => {
  // Only use top positioning
  return 'top-16';
});

const typeClasses = computed(() => ({
  success: 'bg-emerald-500 text-white',
  error: 'bg-red-500 text-white',
  warning: 'bg-amber-500 text-white',
  info: 'bg-blue-500 text-white',
  custom: toast.value.customClass || 'bg-gray-700 text-white'
}));

const typeIcons = computed(() => ({
  success: CheckCircleIcon,
  error: XCircleIcon,
  warning: ExclamationCircleIcon,
  info: InformationCircleIcon
}));

// Watch for changes in toast state
watch(() => store.state.toast, (newToast) => {
  if (newToast.show) {
    // Check if this instance should be active
    if (!activeToastInstance || activeToastInstance === toastInstanceId) {
      activeToastInstance = toastInstanceId;
      isActiveInstance.value = true;
      resetTimers();
      setupTimers();
    }
  }
}, { deep: true });

// Methods
function resetTimers() {
  if (interval) {
    clearInterval(interval);
    interval = null;
  }
  if (timeout) {
    clearTimeout(timeout);
    timeout = null;
  }
  percent.value = 0;
}

function setupTimers() {
  const delay = toast.value.delay || 5000;
  
  // Auto-close timeout
  timeout = setTimeout(() => {
    close();
    timeout = null;
  }, delay);
  
  // Progress bar interval
  const startTime = Date.now();
  const endTime = startTime + delay;
  
  interval = setInterval(() => {
    const now = Date.now();
    percent.value = ((now - startTime) * 100) / (endTime - startTime);
    
    if (percent.value >= 100) {
      clearInterval(interval);
      interval = null;
    }
  }, 30);
}

function close() {
  resetTimers();
  store.commit('hideToast');
  
  // Release the active instance reference
  if (activeToastInstance === toastInstanceId) {
    activeToastInstance = null;
    isActiveInstance.value = false;
  }
}

// Lifecycle hooks
onMounted(() => {
  // On mount, check if this should be the active instance
  // Give priority to the toast instance with the highest priority prop
  if (!activeToastInstance) {
    activeToastInstance = toastInstanceId;
    isActiveInstance.value = true;
  }
  
  if (toast.value && toast.value.show && isActiveInstance.value) {
    setupTimers();
  }
});

onUnmounted(() => {
  // If this was the active instance, release it
  if (activeToastInstance === toastInstanceId) {
    activeToastInstance = null;
  }
  resetTimers();
});
</script>