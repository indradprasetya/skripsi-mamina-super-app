import "./bootstrap";
import "../css/app.css";
import { createApp, h} from "vue";
import { createInertiaApp, Head, Link, usePage} from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import Layout from "./Layouts/MainLayout.vue";
import "flowbite";

createInertiaApp({
    title: (title) => `${title} - Mamina App`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || Layout;''
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue) //This is for routes, so i can use laravel route in vue
            .component("Head", Head)
            .component("Link", Link) // This is inertia link, so i can feels like SPA
            .component("Layout", Layout) // Register Layout component globally
            .mount(el);
    },
});
