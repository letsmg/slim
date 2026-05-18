import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import fs from 'fs'

export default defineConfig({
    plugins: [
        vue(),
        tailwindcss(),
        {
            name: 'hot-file',
            configureServer(server) {
                const createHotFile = () => {
                    const address = server.httpServer?.address()
                    if (address && typeof address === 'object') {
                        const hotContent = `http://localhost:${address.port}`
                        fs.writeFileSync('public/hot', hotContent)
                        console.log(`[hot] Arquivo public/hot criado: ${hotContent}`)
                    }
                }

                // Cria o arquivo public/hot quando o servidor dev inicia
                server.httpServer?.on('listening', createHotFile)

                // Garante que o arquivo exista mesmo apos restart
                createHotFile()
            },
        },
    ],
    server: {
        port: 5173,
        host: 'localhost',
        strictPort: true,
        // Polling para Windows 11 detectar mudancas nos arquivos
        watch: {
            usePolling: true,
            interval: 100,
        },
        hmr: {
            host: 'localhost',
        },
    },
    root: 'resources',
    base: '/',
    publicDir: false,

    css: {
        devSourcemap: true,
    },
    build: {
        outDir: '../public',
        emptyOutDir: false,
        manifest: true,
        rollupOptions: {
            input: '/js/app.js',
            output: {
                entryFileNames: 'js/app.[hash].js',
                chunkFileNames: 'js/[name].[hash].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/app.[hash].css'
                    }
                    if (assetInfo.name.endsWith('.woff2') || assetInfo.name.endsWith('.woff') || assetInfo.name.endsWith('.ttf')) {
                        return 'fonts/[name][extname]'
                    }
                    return 'assets/[name].[hash][extname]'
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
