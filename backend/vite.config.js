// vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      // Force Vue to use the compiler-included build
      'vue': 'vue/dist/vue.esm-bundler.js'
    }
  }
})
