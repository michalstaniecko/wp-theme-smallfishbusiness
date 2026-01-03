import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
  build: {
    outDir: 'themes/smallfishbusiness/build',
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        style: path.resolve(__dirname, 'src/css/main.css'),
      },
    },
  },
  server: {
    origin: 'http://localhost:5173',
  },
});
