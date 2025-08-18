import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/myapp.js',


                'resources/css/homepage.css',
                'resources/css/homepage.js',

                'resources/css/detailsAppart.css',
                'resources/css/detailsAppart.js',

                'resources/css/paiement.css',
                'resources/css/paiement.js',

                'resources/css/homeUser.css',
                'resources/css/homeUser.js'
                
            ],
            refresh: true,
        }),
    ],
});
