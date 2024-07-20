import './bootstrap';
import '../css/app.css';

import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
    setup({ el, App, props }) {
        new App({ target: el, props, hydrate: true });
    },
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
});
