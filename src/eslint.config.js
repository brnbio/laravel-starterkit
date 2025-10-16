import { defineConfigWithVueTs, vueTsConfigs } from "@vue/eslint-config-typescript";
import vue from "eslint-plugin-vue";

export default defineConfigWithVueTs(
        vue.configs["flat/recommended"],
        vueTsConfigs.recommended,
        {
            ignores: [
                "node_modules",
                "public",
                "resources/js/components/ui/*",
                "tailwind.config.js",
                "tests",
                "vendor",
            ],
        },
        {
            rules: {
                "@typescript-eslint/no-explicit-any": "off",
                "vue/block-lang": [ "error", { "script": { "lang": "ts" } } ],
                "vue/block-order": [ "error", { order: [ "script", "template", "style" ] } ],
                "vue/block-tag-newline": [ "error", { "singleline": "always", "multiline": "always", "maxEmptyLines": 1 } ],
                "vue/html-indent": [ "error", 4, { baseIndent: 1 } ],
                "vue/script-indent": [ "error", 4, { baseIndent: 0 } ],
                "vue/max-attributes-per-line": [ "error", { singleline: 8, multiline: 1 } ],
                "vue/multi-word-component-names": "off",
                "vue/multiline-html-element-content-newline": "off",
                "vue/no-v-html": "off",
                "vue/padding-line-between-blocks": [ "error", "always" ],
                "vue/singleline-html-element-content-newline": [ "error", { ignores: [] } ],
            },
        },
);