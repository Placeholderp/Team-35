<template>
  <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8">
      <!-- Brand/Logo could go here -->
      <div>
        <img 
          class="mx-auto h-12 w-auto" 
          src="C:\xampp\htdocs\team-35\backend\src\assets\logo.png"
          alt="Your Company" 
        />
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          {{ title }}
        </h2>
      </div>
      
      <!-- Content Slot -->
      <slot></slot>
      
      <!-- Footer -->
      <div class="mt-8 text-center text-sm text-gray-500">
        <p>© {{ currentYear }} Your Company. All rights reserved.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

// Props
const props = defineProps({
  title: {
    type: String,
    required: true
  }
});

// Computed properties
const currentYear = computed(() => new Date().getFullYear());
</script>

<style scoped>
/* Any component-specific styles */
</style>