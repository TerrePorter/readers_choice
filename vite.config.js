import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        hmr: {
            protocol: 'ws',
            https: false,
            host: "192.168.10.10",
        },
        host: "192.168.10.10",
        https: false,
        watch: {
            usePolling: false,
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            publicDirectory: "public",
            refresh: false,
        }),
    ],
});
