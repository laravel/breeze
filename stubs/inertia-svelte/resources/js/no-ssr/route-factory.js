export const route = (name, params, absolute) => {
    if (typeof window !== 'undefined') {
        // noinspection JSCheckFunctionSignatures
        return window.route(name, params, absolute);
    } else {
        console.error('route() is only available in the browser')
    }
};
