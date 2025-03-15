import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => false
        }
      }
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
      'vue': 'vue/dist/vue.esm-bundler.js',
      // Explicitly resolve lodash-es
      'lodash-es': path.resolve(__dirname, 'node_modules/lodash-es')
    }
  },
  // Add this to help with module resolution
  optimizeDeps: {
    include: ['lodash-es']
  }
})