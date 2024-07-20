import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import minimist from 'minimist';
import * as path from 'node:path';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const isProduction = env.APP_ENV === 'production';

    const argv = minimist(process.argv.slice(2));
    const isSsr = argv.ssr || false;

    return {
        plugins: [
            laravel({
                input: ["resources/js/app.ts"],
                ssr: 'resources/js/ssr.ts',
                refresh: true,
            }),
            svelte({
                compilerOptions: {
                    hydratable: true,
                },
            }),
        ],
        resolve: {
            alias: {
                './route-factory.ts': isProduction || isSsr ? './route-factory.prod.ts' : './route-factory.dev.ts',
                '@': path.resolve('resources/js'),
            },
        },
    };
});
