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
    // Disable sourcemaps in production for smaller bundle
    sourcemap: false,
    // Use esbuild for fast minification
    minify: 'esbuild',
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        style: path.resolve(__dirname, 'src/css/main.css'),
      },
      output: {
        // Hash filenames for cache busting
        entryFileNames: '[name]-[hash].js',
        chunkFileNames: '[name]-[hash].js',
        assetFileNames: '[name]-[hash].[ext]',
      },
    },
    // CSS code splitting
    cssCodeSplit: true,
    // Target modern browsers for smaller output
    target: ['es2020', 'edge88', 'firefox78', 'chrome87', 'safari14'],
  },
  server: {
    origin: 'http://localhost:5173',
  },
});
