import { defineConfig } from "vite";
import SymfonyPlugin from "vite-plugin-symfony";
import Vue from '@vitejs/plugin-vue'
import DefineOptions from 'unplugin-vue-define-options/vite'

export default defineConfig({
    plugins: [
        Vue({include: ['./assets/Pages/**/*', './assets/Components/**/*']}),
        SymfonyPlugin(),
        DefineOptions()
    ],
    root: '.',
    base: '/build/',
    publicDir: false,
    build: {
        manifest: true,
        emptyOutDir: true,
        assetsDir: "",
        outDir: "./public/build",
        rollupOptions: {
            input: {
                app: "./assets/app.ts"
            },
        },
    },
    server: {
        port: 13714
    }
});
