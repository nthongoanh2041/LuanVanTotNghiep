import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { wayfinder } from '@laravel/vite-plugin-wayfinder'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // 👇 Quan trọng: dùng bản Vue có compiler
            vue: 'vue/dist/vue.esm-bundler.js',

            // 👇 Thêm alias @ cho đường dẫn resources/js
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
})
