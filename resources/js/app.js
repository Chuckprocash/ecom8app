import '../css/app.css';
import './bootstrap';

import { createInertiaApp , router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import 'flowbite';
import { initFlowbite } from 'flowbite';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ElementPlus, { size: 'small', zIndex: 3000 })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Reinitialize Flowbite after navigation
router.on('navigate', () => {
    initFlowbite();
});
