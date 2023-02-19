export default {
    install: (app, options = {}) => {
        if (!options.translations) {
            console.warn('Please provide translations to the plugin.');
        }

        const translations = options.translations || {};

        // Translate the given message
        const translate = (key, replace = {}) => {
            let translation = translations[key] || key;

            Object.keys(replace).forEach((r) => {
                translation = translation.replace(`:${r}`, replace[r]);
            });

            return translation;
        };

        // Define global properties
        app.config.globalProperties.$translate = translate;
        app.config.globalProperties.__ = translate;

        // Provide methods
        app.provide('translate', { translate });
        app.provide('__', { translate });
    },
};
