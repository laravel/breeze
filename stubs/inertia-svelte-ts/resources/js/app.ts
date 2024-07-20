import './bootstrap';
import '../css/app.css';

// @ts-ignore
import { createInertiaApp } from "@inertiajs/svelte";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// @ts-ignore
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title: any) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name: any) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
    // @ts-ignore
    setup({ el, App, props }) {
        new App({ target: el, props, hydrate: true });
    },
    progress: {
        color: "#4B5563",
        showSpinner: true,
    },
});
