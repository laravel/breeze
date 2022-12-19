import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import createServer from '@inertiajs/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import route, {Config, RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";

const appName = 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        // @ts-ignore
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ app, props, plugin }) {
            let Ziggy: Config = {
                // @ts-ignore
                ...page.props.ziggy,
                // @ts-ignore
                location: new URL(page.props.ziggy.location),
            };
            const ssrApp = createSSRApp({render: () => h(app, props)})
                .use(plugin)
                .use(ZiggyVue, Ziggy);

            // @ts-ignore
            ssrApp.config.globalProperties.$route = (
                name?: undefined,
                params?: RouteParamsWithQueryOverload | RouteParam,
                absolute?: boolean,
            ) => route(name, params, absolute, Ziggy)

            return ssrApp
        },
    })
);
