<template>
  <span 
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
    :class="statusClasses"
  >
    <span 
      class="h-2 w-2 mr-1.5 rounded-full" 
      :class="dotClasses"
    ></span>
    {{ formattedStatus }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  order: {
    type: Object,
    required: true
  }
});

const formattedStatus = computed(() => 
  capitalizeFirstLetter(props.order.status)
);

const statusClasses = computed(() => {
  const statusColorMap = {
    'paid': 'bg-green-100 text-green-800',
    'completed': 'bg-green-100 text-green-800',
    'shipped': 'bg-blue-100 text-blue-800',
    'cancelled': 'bg-red-100 text-red-800',
    'unpaid': 'bg-gray-100 text-gray-800',
    'default': 'bg-gray-100 text-gray-800'
  };

  return statusColorMap[props.order.status] || statusColorMap['default'];
});

const dotClasses = computed(() => {
  const statusColorMap = {
    'paid': 'bg-green-500',
    'completed': 'bg-green-500',
    'shipped': 'bg-blue-500',
    'cancelled': 'bg-red-500',
    'unpaid': 'bg-gray-500',
    'default': 'bg-gray-500'
  };

  return statusColorMap[props.order.status] || statusColorMap['default'];
});

function capitalizeFirstLetter(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>