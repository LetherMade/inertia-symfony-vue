import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        vue({include: ['./assets/Pages/*']}),
        symfonyPlugin()
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
