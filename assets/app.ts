import "./app.css"

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/vue3'
import Layout from "./Components/Layout.vue";

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        const page = (pages[`./Pages/${name}.vue`] as any).default

        page.layout = Layout

        return page;
    },
    progress: {
        showSpinner: true,
        color: '#7fb8b4',
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .mount(el)
    },
    title: title => title ? `${title} - LetherMade` : 'LetherMade',
}).then()