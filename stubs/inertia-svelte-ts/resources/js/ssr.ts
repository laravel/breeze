// @ts-ignore
import { createInertiaApp } from '@inertiajs/svelte';
// @ts-ignore
import createServer from '@inertiajs/svelte/server';

// @ts-ignore
createServer((page: any) =>
    createInertiaApp({
        page,
        resolve: (name: any) => {
        // @ts-ignore
            const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
            return pages[`./Pages/${name}.svelte`];
        },
        // @ts-ignore
        setup({ App, props }) {
            new App({ target: document.body, props, hydrate: true });
        },
    })
);
