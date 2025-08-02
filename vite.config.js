// import { defineConfig } from "vite";
// import laravel, { refreshPaths } from "laravel-vite-plugin";
// import vue from "@vitejs/plugin-vue";
// import path from "path";
//
// export default defineConfig({
//   plugins: [
//     laravel({
//       input: ["resources/js/app.js"],
//       refresh: [
//         ...refreshPaths,
//         "resources/views/**",
//         "routes/**",
//         "resources/markdown/**",
//       ],
//     }),
//     vue({
//       template: {
//         transformAssetUrls: {
//           // The Vue plugin will re-write asset URLs, when referenced
//           // in Single File Components, to point to the Laravel web
//           // server. Setting this to `null` allows the Laravel plugin
//           // to instead re-write asset URLs to point to the Vite
//           // server instead.
//           base: null,
//
//           // The Vue plugin will parse absolute URLs and treat them
//           // as absolute paths to files on disk. Setting this to
//           // `false` will leave absolute URLs un-touched so they can
//           // reference assets in the public directly as expected.
//           includeAbsolute: false,
//         },
//       },
//     }),
//   ],
//   resolve: { alias: { "@scss": path.resolve(__dirname, "./resources/scss") } },
// });


import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/js/app.js"],
      refresh: [
        ...refreshPaths,
        "resources/views/**",
        "routes/**",
        "resources/markdown/**",
      ],
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      "@scss": path.resolve(__dirname, "./resources/scss"),
      "@": path.resolve(__dirname, "./resources/js") // Add this line
    }
  },
  build: {
    chunkSizeWarningLimit: 1000, // Increase from default 500kb to 1000kb
    rollupOptions: {
      output: {
        manualChunks: {
          'vendor': [
            'vue',
            'vue-router',
            '@inertiajs/vue3',
            'axios'
          ],
          'editor': [
            'codemirror',
            '@codemirror/lang-javascript',
            '@codemirror/lang-markdown',
            '@codemirror/theme-one-dark'
          ],
          'flow': [
            '@vue-flow/core',
            '@vue-flow/background',
            '@vue-flow/controls',
            '@vue-flow/minimap'
          ]
        }
      }
    }
  },
});
