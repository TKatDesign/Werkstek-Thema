import path from 'path';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';

const THEME_DIR = __dirname;
const PUBLIC_DIR = path.resolve(THEME_DIR, '../../..');
const BASE = `/${path.relative(PUBLIC_DIR, THEME_DIR).replace(/\\/g, '/')}`;

export default defineConfig(({ command }) => ({
    root: THEME_DIR,
    base: command === 'build' ? `${BASE}/dist/` : '/',
    server: {
        cors: true,
        host: true,
        strictPort: true,
        fs: {
            allow: [THEME_DIR],
        },
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        tailwindcss(),
        ViteImageOptimizer(),
        {
            name: 'php-reload',
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.php')) {
                    server.ws.send({ type: 'full-reload' });
                }
            },
        },
    ],
    build: {
        manifest: true,
        assetsDir: '.',
        outDir: 'dist',
        emptyOutDir: true,
        sourcemap: false,
        assetsInlineLimit: 0,
        minify: true,
        rollupOptions: {
            input: [
                'resources/scripts/scripts.js',
                'resources/styles/styles.css',
            ],
            output: {
                entryFileNames: '[hash].js',
                assetFileNames: (assetInfo) => {
                    if (/\.(png|jpe?g|gif|svg|webp|avif)$/i.test(assetInfo.name)) {
                        return 'images/[hash][extname]';
                    }
                    if (/\.(woff2?|ttf|eot|otf)$/i.test(assetInfo.name)) {
                        return 'fonts/[hash][extname]';
                    }
                    if (/\.(css)$/i.test(assetInfo.name)) {
                        return '[hash][extname]';
                    }
                    return 'others/[hash][extname]';
                },
            },
        },
    },
}));
