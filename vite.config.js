import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
        server: {
            host: env.VITE_HOST || '127.0.0.1',
            port: env.VITE_PORT || 5173,
            strictPort: true,
            hmr: {
                host: env.VITE_HOST || '127.0.0.1',
            },
        },
    };
});