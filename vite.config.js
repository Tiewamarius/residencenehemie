import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/homepage.css',
                'resources/css/homepage.js',
                'resources/css/detailsAppart.css',
                'resources/css/detailsAppart.js'
            ],
            refresh: true,
        }),
    ],
});
