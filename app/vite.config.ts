import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  server: {
    host: true, // Permite que o servidor escute conexões externas
    watch: {
      usePolling: true, // Resolve problemas de hot-reloading em sistemas como Docker
    },
  },
})
