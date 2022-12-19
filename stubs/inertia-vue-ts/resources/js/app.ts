import './bootstrap.js';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import route from "ziggy-js";
import {Ziggy} from './ziggy';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // @ts-ignore
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({render: () => h(app, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        vueApp.config.globalProperties.$route = route;

        return vueApp.mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
