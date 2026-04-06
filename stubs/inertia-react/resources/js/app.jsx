import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    withApp(app) {
        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
