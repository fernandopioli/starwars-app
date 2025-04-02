import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig(({ command }) => {
    const config: any = {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.tsx'],
                refresh: true,
            }),
            react(),
        ],
        resolve: {
            alias: {
                '@/css': '/resources/css',
                '@': '/resources/js',
            },
        },
    };
    
    if (command !== 'build') {
        config.server = {
            host: '0.0.0.0',
            strictPort: true,
            port: 5173,
            hmr: {
                host: 'localhost',
                protocol: 'ws'
            },
            watch: {
                usePolling: true,
            },
            cors: {
                origin: '*'
            }
        };
    }
    
    return config;
});