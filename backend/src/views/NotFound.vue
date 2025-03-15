<template>
  <div class="bg-gray-50 min-h-screen flex items-center justify-center p-6 md:p-8">
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-sm p-8 md:p-12 text-center">
      <!-- Error icon -->
      <div class="mx-auto w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
        <ExclamationIcon class="h-10 w-10 text-indigo-600" />
      </div>
      
      <!-- Error code -->
      <h1 class="text-6xl md:text-8xl font-bold text-indigo-600 mb-3">404</h1>
      
      <!-- Error message -->
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Page Not Found</h2>
      
      <p class="text-gray-600 max-w-lg mx-auto mb-8">
        The page you are looking for might have been removed, had its name changed, 
        or is temporarily unavailable.
      </p>
      
      <!-- Action buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <router-link 
          :to="{ name: 'app.dashboard' }" 
          class="inline-flex items-center justify-center px-5 py-3 rounded-lg
                 bg-indigo-600 text-white font-medium
                 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                 transition-colors"
        >
          <HomeIcon class="h-5 w-5 mr-2" />
          Back to Dashboard
        </router-link>
        
        <button 
          @click="goBack" 
          class="inline-flex items-center justify-center px-5 py-3 rounded-lg
                 bg-gray-100 text-gray-700 font-medium
                 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2
                 transition-colors"
        >
          <ArrowLeftIcon class="h-5 w-5 mr-2" />
          Go Back
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { HomeIcon, ExclamationIcon, ArrowLeftIcon } from '@heroicons/vue/outline'
import { useRouter } from 'vue-router'

const router = useRouter()

function goBack() {
  router.go(-1)
}
</script>