import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/admin/theme.css",
                "resources/css/filament/resident/theme.css",
            ],
            refresh: [
                ...refreshPaths,
                "app/Livewire/**",
                "app/Filament/**",
                "app/Providers/**",
            ],
        }),
    ],
    // Tambahkan konfigurasi ini untuk mengatasi error CJS
    optimizeDeps: {
        include: ["axios"],
    },
    build: {
        commonjsOptions: {
            include: [/axios/, /node_modules/],
        },
    },
    // Tambahkan ini jika masih ada error
    ssr: {
        noExternal: ["axios"],
    },
});
