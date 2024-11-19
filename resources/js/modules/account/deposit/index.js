import { createApp } from 'vue/dist/vue.esm-bundler.js'
import Antd from 'ant-design-vue'

import Index from './views/Index.vue'

const app = createApp({})

// Plugins
app.use(Antd)

// Components
app.component('deposit-index', Index)
// Mount
app.mount('#app')
