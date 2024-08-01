import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true, // 自动刷新
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // Vue 插件会重新编写资产 URL，以便在单文件组件中引用时，指向 Laravel web 服务器。
                    // 将其设置为 `null`，则 Laravel 插件会将资产 URL 重新编写为指向 Vite 服务器。
                    base: null,

                    // Vue 插件将解析绝对 URL 并将其视为磁盘上文件的绝对路径。
                    // 将其设置为 `false`，将保留绝对 URL 不变，以便可以像预期那样引用公共目录中的资源。
                    includeAbsolute: false,
                },
            },
        }),
    ],
    base: '/',
    server: {
        host: '0.0.0.0',
    },
});
