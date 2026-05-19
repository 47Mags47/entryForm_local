import { defineConfig, loadEnv } from "vite";
import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

const env = loadEnv("all", process.cwd());

export default defineConfig({
    resolve: {
        alias: {
            "@components": "/resources/views/components",
            "@layouts": "/resources/views/layouts",
            "@helpers": "/resources/js/helpers",
            "@includes": "/resources/views/includes",
            "@sass": "/resources/sass",
        },
    },
    plugins: [
        laravel({
            input: ["resources/sass/app.sass", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    server: {
        host: true,
        port: env.VITE_ASSET_PORT,
        strictPort: true,
        hmr: {
            host: env.VITE_ASSET_HOST,
            port: env.VITE_ASSET_PORT,
        },
    },
    css: {
        preprocessorOptions: {
            sass: {
                additionalData: `@use "@sass/abstracts" as *\n`,
            },
        },
    },
});
