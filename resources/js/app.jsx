import './bootstrap';
import '../css/app.css';

import React from 'react';
import { render } from 'react-dom';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// Page resolver for Laravel Mix
const pages = require.context('./Pages', true, /\.jsx$/);

const resolvePageComponent = (name) => {
    const page = pages(`./${name}.jsx`);
    return page.default || page;
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(name),
    setup({ el, App, props }) {
        return render(<App {...props} />, el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
