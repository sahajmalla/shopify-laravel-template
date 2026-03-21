import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import AppLayout from '@/layouts/AppLayout.vue';

if (typeof document !== 'undefined') {
    document.addEventListener('shopify:navigate', (event) => {
        const href = event?.target?.getAttribute?.('href');
        if (href) {
            router.visit(href);
        }
    });
}

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('../pages/**/*.vue');
        const [feature, page] = String(name).split('/');

        if (!feature || !page) {
            throw new Error(`Inertia page name must be "Feature/Page". Got "${name}".`);
        }

        const path = `../pages/${feature}/${page}.vue`;
        if (!pages[path]) {
            throw new Error(`Inertia page not found: ${path}`);
        }

        return resolvePageComponent(path, pages).then((module) => {
            module.default.layout = module.default.layout || AppLayout;
            return module;
        });
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin);
        vueApp.component('Head', Head);
        vueApp.component('Link', Link);
        vueApp.mount(el);
    },
});
