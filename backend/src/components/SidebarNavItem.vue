<!-- SidebarNavItem.vue -->
<template>
  <router-link 
    v-if="routeExists"
    :to="to"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors"
    :class="isActive ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
  >
    <component 
      v-if="icon" 
      :is="resolvedIcon" 
      class="mr-3 flex-shrink-0 h-6 w-6"
      :class="isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-300'"
      aria-hidden="true"
    />
    <span v-if="!isCollapsed">{{ label }}</span>
  </router-link>
  <!-- Fallback for non-existent routes -->
  <div 
    v-else
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-400 cursor-not-allowed"
  >
    <component 
      v-if="icon" 
      :is="resolvedIcon" 
      class="mr-3 flex-shrink-0 h-6 w-6 text-gray-500"
      aria-hidden="true"
    />
    <span v-if="!isCollapsed">{{ label }}</span>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import * as OutlineIcons from '@heroicons/vue/outline'

export default {
  props: {
    to: {
      type: [String, Object],
      required: true
    },
    icon: {
      type: String,
      default: null
    },
    label: {
      type: String,
      required: true
    },
    isCollapsed: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const route = useRoute()
    const router = useRouter()

    // Safely resolve the icon component
    const resolvedIcon = computed(() => {
      return props.icon && OutlineIcons[props.icon] ? OutlineIcons[props.icon] : null
    })

    // Check if the route exists in the router
    const routeExists = computed(() => {
      try {
        if (typeof props.to === 'string') {
          return !!router.resolve(props.to)
        } else if (props.to && props.to.name) {
          // Check if route with this name exists
          const resolved = router.resolve(props.to)
          return !!resolved && resolved.name !== 'not-found'
        }
        return false
      } catch (error) {
        console.warn(`Route does not exist: ${JSON.stringify(props.to)}`)
        return false
      }
    })

    const isActive = computed(() => {
      if (!routeExists.value) return false

      if (typeof props.to === 'string') {
        return route.path.startsWith(props.to)
      }

      // Handle reports correctly - check if the route name matches or is a child of the reports route
      const toName = props.to.name
      if (toName && route.name) {
        // For exact match
        if (route.name === toName) return true
        
        // For reports section, match parent and children correctly
        if (toName === 'reports' && route.name.startsWith('reports.')) return true
        
        // For specific report types, match exactly
        if (toName.startsWith('reports.') && route.name === toName) return true
      }
      
      return false
    })

    return {
      isActive,
      routeExists,
      resolvedIcon
    }
  }
}
</script>