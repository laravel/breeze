export const route = (name: any, params: any, absolute: any) => {
    if (typeof window !== 'undefined') {
        // noinspection JSCheckFunctionSignatures
        return window.route(name, params, absolute);
    } else {
        console.error('route() is only available in the browser');
    }
};
