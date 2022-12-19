import {PageProps as ExtendedPropsInterface} from "@inertiajs/inertia";
import {User} from "@/types/User";
import route, {Config, RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";
import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import {Axios} from "axios";

declare module '@inertiajs/inertia' {
    export interface PageProps extends ExtendedPropsInterface {
        auth: {
            user: User
        }
    }
}


declare module 'vue' {
    interface ComponentCustomProperties {
        $route: typeof route,
    }
}


declare global {
    interface Window {
        axios: Axios
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}
