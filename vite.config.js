import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import fs from 'fs'
import path from 'path'

// Plugin que força o Vite a detectar mudanças via polling manual
function forceWatchPlugin() {
    let server = null
    const watchedFiles = new Map()

    function scanDir(dir) {
        if (!fs.existsSync(dir)) return
        try {
            const entries = fs.readdirSync(dir, { withFileTypes: true })
            for (const entry of entries) {
                const fullPath = path.join(dir, entry.name)
                if (entry.isDirectory()) {
                    if (entry.name !== 'node_modules' && !entry.name.startsWith('.')) {
                        scanDir(fullPath)
                    }
                } else if (/\.(vue|js|ts|css)$/.test(entry.name)) {
                    try {
                        watchedFiles.set(fullPath, fs.statSync(fullPath).mtimeMs)
                    } catch { }
                }
            }
        } catch { }
    }

    return {
        name: 'force-watch',
        configureServer(_server) {
            server = _server

            // Escaneia os diretórios de origem
            scanDir(path.resolve('resources/js'))
            scanDir(path.resolve('resources/css'))

            console.log(`[Vite] Observando ${watchedFiles.size} arquivos via polling...`)

            // Polling a cada 200ms
            setInterval(() => {
                for (const [filePath, oldMtime] of watchedFiles) {
                    try {
                        const stat = fs.statSync(filePath)
                        if (stat.mtimeMs > oldMtime) {
                            watchedFiles.set(filePath, stat.mtimeMs)
                            const relativePath = path.relative('resources', filePath).replace(/\\/g, '/')
                            console.log(`[Vite] Alterado: ${relativePath}`)

                            // Forca reload completo via WebSocket
                            if (server && server.ws) {
                                server.ws.send({
                                    type: 'full-reload',
                                    path: '*',
                                })
                            }
                            return // Processa uma mudanca por vez
                        }
                    } catch {
                        watchedFiles.delete(filePath)
                    }
                }
            }, 200)
        },
    }
}

export default defineConfig({
    server: {
        port: 5175,
        host: '0.0.0.0',
        fs: {
            allow: ['..'],
        },
        watch: null, // Desabilita watch padrao, usamos o plugin
        hmr: {
            protocol: 'ws',
            host: 'localhost',
            port: 5175,
        },
        // Proxy: redireciona requisicoes de API para o PHP
        proxy: {
            // APIs do backend
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
            },
            // Rotas SPA que nao sao arquivos estaticos - redireciona para o PHP
            // para que o fallback SPA funcione
            '^/(login|register|dashboard|usuarios|produtos|relatorios|veiculos|motoristas|mecanicos|viagens|manutencoes|termos-de-uso|politica-de-privacidade)(/.*)?$': {
                target: 'http://localhost:8000',
                changeOrigin: true,
            },
        },
    },
    define: {
        'import.meta.env.VITE_BASE_URL': JSON.stringify(process.env.BASE_URL || 'http://localhost:8000'),
        'import.meta.env.VITE_PATH_IMG': JSON.stringify(process.env.PATH_IMG || '/imgs'),
    },
    plugins: [
        vue(),
        tailwindcss(),
        forceWatchPlugin(),
    ],
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
