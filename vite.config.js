import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.tsx', 'resources/js/app.js', 'resources/css/app.css'],
            refresh: [
                ...refreshPaths,
                'app/**'
            ],
        }),
        react(),
    ],
});
