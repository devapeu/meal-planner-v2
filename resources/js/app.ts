import { createApp, h, type DefineComponent } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import AppLayout from './Layouts/AppLayout.vue'

import '../css/app.css'
import '../css/app.sass'

createInertiaApp({
    resolve: name => {
        const pages
            = import.meta.glob<{ default: DefineComponent }>('./Pages/**/*.vue', { eager: true })
        const page = pages[`./Pages/${name}.vue`]
        page.default.layout ??= AppLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .mount(el)
    },
})
