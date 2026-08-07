import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { fixOverflow } from './helpers';
import { AuthenticatedLayout } from '@layouts';

createInertiaApp({
    progress: {
        delay: 250,
        color: "#29d",
        includeCSS: false,
        showSpinner: false,
    },
    resolve: async (name) => {
        const pages = import.meta.glob("../views/**/*.vue", { eager: false });

        const page = await pages[`../views/${name}.vue`]();

        const exceptions = ['pages/session/login', 'pages/security/forgot-password', 'pages/workers/create']
        if (!exceptions.includes(name)) {
            page.default.layout ??= AuthenticatedLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});
