import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import svgr from 'vite-plugin-svgr';
// https://vite.dev/config/
export default defineConfig({
  plugins: [react(),svgr(),],
  server: {
    port: 5173,
    // Proxy API calls to PHP backend (local PHP server on port 8001)
    proxy: {
      '/api': {
        target: 'http://localhost:8001',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '/pages/api'),
      },
      '/Pages': {
        target: 'http://localhost:8001',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/Pages/, '/pages'),
      },
    },
  },
});
