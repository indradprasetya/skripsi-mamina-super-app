import { defineConfig } from "vite";
import { VitePWA } from "vite-plugin-pwa";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false, // 👈 Nonaktifkan absolute path processing
                },
            },
        }),
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: "autoUpdate",
            manifest: {
                name: "Mamina Super App",
                short_name: "Mamina",
                start_url: "/",
                display: "standalone",
                background_color: "#ffffff",
                theme_color: "#14b8a6",
                icons: [
                    {
                        src: "/icon-192x192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "/icon-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
            workbox: {
                runtimeCaching: [
                    // Cache halaman home
                    {
                        urlPattern:
                            /^https:\/\/skripsi-mamina-super-app\.test\/$/,
                        handler: "NetworkFirst",
                        options: {
                            cacheName: "home-page",
                            expiration: {
                                maxEntries: 1,
                                maxAgeSeconds: 24 * 60 * 60,
                            },
                            networkTimeoutSeconds: 3,
                        },
                    },
                    // Cache halaman /growth
                    {
                        urlPattern:
                            /^https:\/\/skripsi-mamina-super-app\.test\/growth$/,
                        handler: "NetworkFirst",
                        options: {
                            cacheName: "growth-page",
                            expiration: {
                                maxEntries: 1,
                                maxAgeSeconds: 24 * 60 * 60,
                            },
                            networkTimeoutSeconds: 3,
                        },
                    },
                    // Cache API antropometri
                    {
                        urlPattern:
                            /^https:\/\/skripsi-mamina-super-app\.test\/api\/antropometri\/.*$/,
                        handler: "NetworkFirst",
                        options: {
                            cacheName: "antropometri-api",
                            expiration: {
                                maxEntries: 10,
                                maxAgeSeconds: 24 * 60 * 60,
                            },
                            networkTimeoutSeconds: 3,
                        },
                    },
                ],
            },
        }),
    ],
});
