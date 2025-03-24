<template>
  <div :class="`flex flex-col items-center justify-center ${customClass}`" role="status" aria-live="polite">
    <svg
      :class="spinnerSizeClass"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      ></circle>
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>
    <div v-if="text" class="mt-2 text-center text-sm font-medium" :class="textColorClass">
      {{ text }}
    </div>
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  text: {
    type: String,
    default: 'Please Wait...'
  },
  customClass: {
    type: String,
    default: 'py-8'
  },
  size: {
    type: [Number, String],
    default: 8,
    validator: (value) => {
      // Allow numeric values or tailwind size classes
      // Add support for text-based size names
      const textSizes = ['small', 'medium', 'large', 'xl', 'xxl'];
      
      return (!isNaN(Number(value)) && Number(value) > 0) || 
             ['4', '5', '6', '8', '10', '12', '16'].includes(String(value)) ||
             textSizes.includes(String(value).toLowerCase());
    }
  },
  color: {
    type: String,
    default: 'gray',
    validator: (value) => {
      return ['gray', 'blue', 'green', 'red', 'yellow', 'indigo', 'purple', 'pink'].includes(value);
    }
  },
  textColor: {
    type: String,
    default: '', // defaults to matching spinner color
  },
  fullPage: {
    type: Boolean,
    default: false
  }
});

// Map text-based sizes to numeric values
const getSizeValue = (size) => {
  const sizeMap = {
    'small': 4,
    'medium': 8,
    'large': 12,
    'xl': 16,
    'xxl': 20
  };
  
  if (sizeMap[String(size).toLowerCase()]) {
    return sizeMap[String(size).toLowerCase()];
  }
  
  return size;
};

// Computed properties for dynamic classes
const spinnerSizeClass = computed(() => {
  const sizeValue = getSizeValue(props.size);
  return `animate-spin h-${sizeValue} w-${sizeValue} ${colorClass.value}`;
});

const colorClass = computed(() => {
  const colorMap = {
    gray: 'text-gray-700',
    blue: 'text-blue-600',
    green: 'text-green-600',
    red: 'text-red-600',
    yellow: 'text-yellow-500',
    indigo: 'text-indigo-600',
    purple: 'text-purple-600',
    pink: 'text-pink-600'
  };
  
  return colorMap[props.color] || 'text-gray-700';
});

const textColorClass = computed(() => {
  if (props.textColor && props.textColor !== props.color) {
    const colorMap = {
      gray: 'text-gray-700',
      blue: 'text-blue-600',
      green: 'text-green-600',
      red: 'text-red-600',
      yellow: 'text-yellow-500',
      indigo: 'text-indigo-600',
      purple: 'text-purple-600',
      pink: 'text-pink-600'
    };
    return colorMap[props.textColor] || colorMap[props.color] || 'text-gray-700';
  }
  
  return colorClass.value;
});
</script>