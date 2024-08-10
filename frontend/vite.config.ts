import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import flame from "flame-vite-plugin";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    react(),
    flame({
      input: {
        "src/main.tsx": "main",
        "src/main.css": "css",
      },
      publicPath: "../public",
    }),
  ],
});
