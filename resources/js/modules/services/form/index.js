import { createApp } from 'vue/dist/vue.esm-bundler.js'

import Index from './views/Index.vue'

const app = createApp({})

// Plugins

// Components
app.component('baocms-form-buy', Index)
// Mount
app.mount('#app')
