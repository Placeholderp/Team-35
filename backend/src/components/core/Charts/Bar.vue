<script>
import { defineComponent, h } from 'vue'
import { Bar } from 'vue-chartjs'
import { 
  Chart as ChartJS, 
  Title, 
  Tooltip, 
  Legend, 
  BarElement, 
  CategoryScale, 
  LinearScale 
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default defineComponent({
  name: 'BarChart',
  components: { Bar },
  props: {
    chartId: {
      type: String,
      default: 'bar-chart'
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
      type: Object,
      default: () => {}
    },
    data: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    // Enhanced chart options with better styling
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          display: false, // We'll use a custom legend outside the chart
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
      scales: {
        x: {
          grid: {
            display: false,
          },
          ticks: {
            color: '#64748b', // slate-500
            font: {
              size: 12,
            },
          },
        },
        y: {
          beginAtZero: true,
          grid: {
            color: '#f1f5f9', // slate-100
          },
          ticks: {
            color: '#64748b', // slate-500
            font: {
              size: 12,
            },
            maxTicksLimit: 5,
            padding: 10,
          },
        },
      },
    }

    // Process data to add spacing between bars
    const processedData = {
      ...props.data,
      datasets: props.data.datasets?.map(dataset => ({
        ...dataset,
        barPercentage: 0.7,
        categoryPercentage: 0.8,
        borderRadius: 3,
      })) || []
    }

    return () =>
      h(Bar, {
        chartData: processedData,
        chartOptions,
        chartId: props.chartId,
        width: props.width,
        height: props.height,
        cssClasses: props.cssClasses,
        styles: props.styles,
        plugins: props.plugins,
      })
  }
})
</script>