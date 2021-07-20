require('./bootstrap');

import React from 'react';
import { render } from 'react-dom';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';

// By default, a fresh Laravel Breeze application uses the Application Name configured as the title tag.
// Let's grab this before Inertia gets a chance to overwrite it, and store it's value for later use.
// When this isn't available, we'll instead use the static value of 'Laravel'.
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// Next, we'll initialize our Inertia.js client-side application, for which the
// documentation can be found at https://inertiajs.com/client-side-setup
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        return render(<App {...props} />, el)
    },
})

// Finally, we'll initialize Inertia's NProgress-based progress bar plugin, for which
// the documentation can be found at https://inertiajs.com/progress-indicators
InertiaProgress.init({ color: '#4B5563' });
