import "../css/app.css";
import "flatpickr/dist/flatpickr.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import ThemeProvider from "@components/layout/ThemeProvider.vue";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue");
        return pages[`./Pages/${name}.vue`]();
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () =>
                h(ThemeProvider, null, {
                    default: () => h(App, props),
                }),
        })
            .use(plugin)
            .mount(el);
    },
});
