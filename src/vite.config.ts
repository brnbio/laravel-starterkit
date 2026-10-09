import tailwindcss from "@tailwindcss/vite";
import { wayfinder } from "@laravel/vite-plugin-wayfinder";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import Icons from "unplugin-icons/vite";
import IconsResolver from "unplugin-icons/resolver";
import Components from "unplugin-vue-components/vite";
import { defineConfig } from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.ts"],
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            path: "resources/js/types/wayfinder",
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
        Components({
            dirs: [ "resources/js/components", "resources/js/layouts" ],
            dts: "resources/js/types/components.d.ts",
            resolvers: [
                IconsResolver({
                    prefix: "Icon",
                    enabledCollections: [ "material-symbols" ],
                }),
            ],
        }),
        Icons({
            compiler: "vue3",
            scale: 1.5,
        }),
    ],
    resolve: {
        alias: {
            "@": "/resources/js/",
        },
        extensions: [".ts", ".vue", ".js"],
    },
});
