import { defineConfig } from "vite";

export default defineConfig({
  server: {
    host: true,
    port: 4000,
  },
  build: {
    outDir: "../dist",
  },

  root: "src",
  envDir: "../config"
});
