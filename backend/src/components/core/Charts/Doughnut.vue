
<script>
import { defineComponent, h } from 'vue'

import { Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

export default defineComponent({
  name: 'DoughnutChart',
  components: {
    Doughnut
  },
  props: {
    chartId: {
      type: String,
      default: 'doughnut-chart'
    },
    width: {
      type: Number,
      default: 400
    },
    height: {
      type: Number,
      default: 400
    },
    cssClasses: {
      default: '',
      type: String
    },
    styles: {
      type: Object,
      default: () => {}
    },
    plugins: {
      type: Array,
      default: () => []
    },
    data: {
      type: Object,
      required: true
    }
  },
  emits: ['segment-click'],
  setup(props, { emit }) {
    // Enhanced chart options with hover effects and segment click event
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%', // Makes doughnut hole larger for better look
      plugins: {
        legend: {
          display: false, // We'll use custom legend outside
        },
        tooltip: {
          backgroundColor: 'rgba(255, 255, 255, 0.9)',
          titleColor: '#334155', // slate-700
          bodyColor: '#334155', // slate-700
          borderColor: '#e2e8f0', // slate-200
          borderWidth: 1,
          padding: 12,
          boxPadding: 6,
          usePointStyle: true,
          bodyFont: {
            size: 13,
          },
          titleFont: {
            size: 14,
            weight: 'bold',
          },
        },
      },
      onClick: (e, elements) => {
        if (elements && elements.length > 0) {
          const index = elements[0].index;
          emit('segment-click', index);
        }
      }
    }

    return () =>
      h(Doughnut, {
        chartData: props.data,
        chartOptions,
        chartId: props.chartId,
        width: props.width,
        height: props.height,
        cssClasses: props.cssClasses,
        styles: props.styles,
        plugins: props.plugins
      })
  }
})
</script>

