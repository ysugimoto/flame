import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    manifest: true,
    outDir: "../public",
    rollupOptions: {
      input: [
        "src/main.tsx",
        "src/main.css",
      ],
    },
  },
  plugins: [react()],
})
