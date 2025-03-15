<template>
    <svg :width="width" :height="height" viewBox="0 0 100 40" preserveAspectRatio="none">
      <path :d="generatePath" :stroke="color" stroke-width="2" fill="none" />
    </svg>
  </template>
  
  <script>
  export default {
    name: 'MiniLineChart',
    props: {
      data: {
        type: Array,
        required: true,
        default: () => [0, 0, 0, 0, 0]
      },
      color: {
        type: String,
        default: '#6366F1' // Indigo color by default
      },
      width: {
        type: [Number, String],
        default: 100
      },
      height: {
        type: [Number, String],
        default: 40
      }
    },
    computed: {
      generatePath() {
        if (!this.data || this.data.length === 0) {
          return 'M0,0';
        }
  
        // Normalize data to fit in the SVG height
        const max = Math.max(...this.data);
        const min = Math.min(...this.data);
        const range = max - min || 1; // Avoid division by zero
  
        // Calculate step size based on data length
        const step = 100 / (this.data.length - 1 || 1);
  
        // Generate the SVG path
        return this.data.map((value, index) => {
          // X coordinate
          const x = index * step;
          
          // Y coordinate (inverted as SVG has 0,0 at top-left)
          // Scale to use 80% of height (10% padding top and bottom)
          const normalizedValue = ((value - min) / range) || 0;
          const y = 40 - (normalizedValue * 32 + 4); // 32 = 80% of height, 4 = 10% padding
          
          return `${index === 0 ? 'M' : 'L'}${x},${y}`;
        }).join(' ');
      }
    }
  };
  </script>