import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import fs from 'fs'
import path from 'path'

// Plugin customizado para forçar HMR via fs.watchFile no Windows
function hmrPollingPlugin() {
    let server = null
    const watchedFiles = new Map() // path -> mtime

    function watchDir(dir) {
        if (!fs.existsSync(dir)) return
        const entries = fs.readdirSync(dir, { withFileTypes: true })
        for (const entry of entries) {
            const fullPath = path.join(dir, entry.name)
            if (entry.isDirectory()) {
                if (entry.name !== 'node_modules' && entry.name !== 'public') {
                    watchDir(fullPath)
                }
            } else if (/\.(vue|js|ts|css)$/.test(entry.name)) {
                watchedFiles.set(fullPath, fs.statSync(fullPath).mtimeMs)
            }
        }
    }

    return {
        name: 'hmr-polling',
        configureServer(_server) {
            server = _server

            // Escaneia os diretórios iniciais
            watchDir(path.resolve('resources/js'))
            watchDir(path.resolve('resources/css'))

            console.log(`[HMR] Observando ${watchedFiles.size} arquivos...`)

            // Polling a cada 200ms
            setInterval(() => {
                for (const [filePath, oldMtime] of watchedFiles) {
                    try {
                        const stat = fs.statSync(filePath)
                        if (stat.mtimeMs > oldMtime) {
                            watchedFiles.set(filePath, stat.mtimeMs)
                            console.log(`[HMR] Alterado: ${path.relative('', filePath)}`)
                            if (server && server.ws) {
                                server.ws.send({
                                    type: 'full-reload',
                                    path: '*',
                                })
                            }
                            break // Uma alteração por vez é suficiente
                        }
                    } catch {
                        // Arquivo pode ter sido deletado
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
        fs: {
            allow: ['..'],
        },
        watch: null, // Desabilita o watch padrão do Vite
        hmr: {
            protocol: 'ws',
            host: 'localhost',
            port: 5175,
        },
    },
    define: {
        'import.meta.env.VITE_BASE_URL': JSON.stringify(process.env.BASE_URL || 'http://localhost:8000'),
        'import.meta.env.VITE_PATH_IMG': JSON.stringify(process.env.PATH_IMG || '/imgs'),
    },
    plugins: [
        vue(),
        tailwindcss(),
        hmrPollingPlugin(),
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
