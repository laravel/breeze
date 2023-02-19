import React from 'react';
import { createContext } from 'react';

export const TranslationsContext = createContext();

export const TranslationsProvider = ({ translations = {}, children }) => {
    if (!translations) {
        console.warn('Please provide translations to the TranslationsProvider.');
    }

    // Translate the given message
    const translate = (key, replace = {}) => {
        let translation = translations[key] || key;

        Object.keys(replace).forEach((r) => {
            translation = translation.replace(`:${r}`, replace[r]);
        });

        return translation;
    };

    // Shorter alias
    const __ = translate;

    return <TranslationsContext.Provider value={{ translate, __ }}>{children}</TranslationsContext.Provider>;
};
