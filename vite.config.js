import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    server: {
        port: 5175,
        // Permite que o Vite sirva arquivos de fora do root (necessário para links simbólicos)
        fs: {
            allow: ['..'],
        },
        // HMR via WebSocket para recarregamento em tempo real
        watch: {
            usePolling: true, // Força polling no Windows para detectar alterações
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
    root: 'resources',
    base: '/',
    // publicDir removido para não conflitar com outDir - os assets estáticos
    // (imagens, etc) são servidos pelo PHP via link simbólico public/imgs -> storage/imgs

    css: {
        devSourcemap: true,
    },
    build: {
        outDir: '../public',
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
            '@': '/js',
            '@pages': '/js/pages',
            '@layouts': '/js/layouts',
            '@components': '/js/components',
            '@user': '/js/pages/user',
            '@product': '/js/pages/product',
            '@report': '/js/pages/report',
            '@vehicle': '/js/pages/vehicle',
            '@driver': '/js/pages/driver',
            '@mechanic': '/js/pages/mechanic',
            '@trip': '/js/pages/trip',
            '@maintenance': '/js/pages/maintenance',
        },
    },
})
