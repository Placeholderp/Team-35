<template>
  <span 
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
    :class="statusStyle.bgClass"
  >
    <span 
      class="h-2 w-2 mr-1.5 rounded-full" 
      :class="statusStyle.dotClass"
    ></span>
    {{ statusLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue';
import { getStatusStyle, formatStatusLabel, normalizeStatus } from '../../utils/orderstatusUtils';

const props = defineProps({
  order: {
    type: Object,
    required: true
  },
  status: {
    type: String,
    default: null
  }
});

const orderStatus = computed(() => {
  // Use either directly provided status or from order object
  return props.status || (props.order ? props.order.status : '');
});

const normalizedStatus = computed(() => normalizeStatus(orderStatus.value));

const statusLabel = computed(() => formatStatusLabel(normalizedStatus.value));

const statusStyle = computed(() => getStatusStyle(normalizedStatus.value));
</script>