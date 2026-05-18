import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    server: {
        port: 5175,
        // HMR via WebSocket para recarregamento em tempo real
        watch: {
            usePolling: true,
            interval: 100,
        },
    },
    // Expõe variáveis do .env com prefixo VITE_ para o frontend
    define: {
        'import.meta.env.VITE_BASE_URL': JSON.stringify(process.env.BASE_URL || 'http://localhost:8000'),
        'import.meta.env.VITE_PATH_IMG': JSON.stringify(process.env.PATH_IMG || '/imgs'),
    },
    plugins: [
        vue(),
        tailwindcss(),
    ],
    // Root na raiz do projeto para watch funcionar corretamente
    root: '.',
    base: '/',
    publicDir: false,

    css: {
        devSourcemap: true,
    },
    build: {
        outDir: 'public',
        emptyOutDir: false,
        manifest: true,
        rollupOptions: {
            input: 'resources/js/app.js',
            output: {
                entryFileNames: 'js/app.js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/app.css'
                    }
                    if (assetInfo.name.endsWith('.woff2') || assetInfo.name.endsWith('.woff') || assetInfo.name.endsWith('.ttf')) {
                        return 'fonts/[name][extname]'
                    }
                    return 'assets/[name][extname]'
                },
            },
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            '@pages': '/resources/js/pages',
            '@layouts': '/resources/js/layouts',
            '@components': '/resources/js/components',
            '@user': '/resources/js/pages/user',
            '@product': '/resources/js/pages/product',
            '@report': '/resources/js/pages/report',
            '@vehicle': '/resources/js/pages/vehicle',
            '@driver': '/resources/js/pages/driver',
            '@mechanic': '/resources/js/pages/mechanic',
            '@trip': '/resources/js/pages/trip',
            '@maintenance': '/resources/js/pages/maintenance',
        },
    },
})
