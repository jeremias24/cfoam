import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import dotenv from "dotenv";

dotenv.config({ path: "../.env" });

// https://vitejs.dev/config/
export default defineConfig({
  envDir: "../.env",
  server: {
    hmr: {
      host: "localhost",
      clientPort: 3000,
      // port: 3001,
    },
    strictPort: true,
    host: "0.0.0.0",
    port: 3000,
    watch: {
      usePolling: true,
    },
  },
  plugins: [vue()],
  resolve: {
    alias: {
      "vue-i18n": "vue-i18n/dist/vue-i18n.cjs.js",
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  base: "",
  build: {
    chunkSizeWarningLimit: 3000,
  },
});