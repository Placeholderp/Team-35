<template>
  <aside 
  class="min-h-screen flex flex-col bg-gray-800 text-white transition-all duration-300 ease-in-out"
  :class="[
    isCollapsed ? 'w-16' : 'w-64'
  ]"
>
 <!-- Logo Section (fixed at top) -->
<div class="flex-shrink-0 flex items-center justify-center h-16 border-b border-gray-700">
  <img 
    v-if="isCollapsed" 
    src="C:\xampp\htdocs\team-35l\backend\src\assets\logo.png" 
    alt="Logo" 
    class="h-10 w-10 object-cover brightness-0 invert" 
  />
  <img 
    v-else 
    src="C:\xampp\htdocs\team-35l\backend\src\assets\logo.png" 
    alt="Logo" 
    class="h-10 w-32 object-contain brightness-0 invert" 
  />
</div>
    
    <!-- Navigation (scrollable) -->
    <nav class="flex-1 overflow-y-auto scrollbar-thin py-4">
      <div class="px-2 space-y-1">
        <!-- Dashboard -->
        <SidebarNavItem 
          :to="{ name: 'app.dashboard' }" 
          :is-collapsed="isCollapsed"
          icon="HomeIcon"
          label="Dashboard"
        />
        
        <!-- Products -->
        <SidebarNavItem 
          :to="{ name: 'app.products' }" 
          :is-collapsed="isCollapsed"
          icon="ShoppingBagIcon"
          label="Products"
        />
        
        <!-- Orders -->
        <SidebarNavItem 
          :to="{ name: 'app.orders' }" 
          :is-collapsed="isCollapsed"
          icon="ClipboardListIcon"
          label="Orders"
        />
        
        <!-- Inventory Section -->
        <div class="pt-5">
          <div v-if="!isCollapsed" class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Inventory
          </div>
          <div v-else class="border-t border-gray-700 my-2"></div>
          
          <!-- Inventory Dashboard -->
          <SidebarNavItem 
            :to="{ name: 'app.inventory' }" 
            :is-collapsed="isCollapsed"
            icon="ArchiveIcon"
            label="Inventory"
          />
          
          
          
          
          
        </div>
        
        <!-- Users Section -->
        <div class="pt-5">
          <div v-if="!isCollapsed" class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Users Management
          </div>
          <div v-else class="border-t border-gray-700 my-2"></div>
          
          <!-- Admin Users -->
          <SidebarNavItem 
            :to="{ name: 'app.users' }" 
            :is-collapsed="isCollapsed"
            icon="UserIcon"
            label="Admin Users"
          />
          
          <!-- Customers -->
          <SidebarNavItem 
            :to="{ name: 'app.customers' }" 
            :is-collapsed="isCollapsed"
            icon="UserGroupIcon"
            label="Customers"
          />
        </div>
        
        <!-- Reports Section -->
        <div class="pt-5">
          <div v-if="!isCollapsed" class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Reports
          </div>
          <div v-else class="border-t border-gray-700 my-2"></div>
          
          <!-- Customers Report -->
          <SidebarNavItem 
            :to="{ name: 'reports.customers', params: { date: 'all' } }" 
            :is-collapsed="isCollapsed"
            icon="UserGroupIcon"
            label="Customer Reports"
          />
          
          <!-- Orders Report -->
          <SidebarNavItem 
            :to="{ name: 'reports.orders', params: { date: 'all' } }" 
            :is-collapsed="isCollapsed"
            icon="ClipboardListIcon"
            label="Order Reports"
          />
        </div>
      </div>
    </nav>
  </aside>
</template>

<script>
import { 
  HomeIcon, 
  UserGroupIcon, 
  UserIcon, 
  ChartBarIcon,
  ClipboardListIcon,
  ShoppingBagIcon,
  ChartPieIcon,
  ArchiveIcon,
  CubeIcon,
  SwitchVerticalIcon
} from '@heroicons/vue/outline';

// Component imports
import SidebarNavItem from './SidebarNavItem.vue';

export default {
  components: {
    SidebarNavItem,
    HomeIcon, 
    UserGroupIcon, 
    UserIcon, 
    ChartBarIcon,
    ClipboardListIcon,
    ShoppingBagIcon,
    ChartPieIcon,
    ArchiveIcon,
    CubeIcon,
    SwitchVerticalIcon
  },
  props: {
    isCollapsed: {
      type: Boolean,
      default: false
    }
  },
  emits: ['toggle-collapse']
}
</script>