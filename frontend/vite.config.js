import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        configure: (proxy) => {
          proxy.on('proxyReq', (proxyReq, req) => {
            const clientIp =
              req.headers['x-forwarded-for'] ||
              req.socket.remoteAddress ||
              '127.0.0.1'
            proxyReq.setHeader('X-Forwarded-For', clientIp)
          })
        },
      },
    },
  },
})
