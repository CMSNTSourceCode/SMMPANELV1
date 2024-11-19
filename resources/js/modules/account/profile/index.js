import { createApp } from 'vue/dist/vue.esm-bundler.js'
import Antd from 'ant-design-vue'

import History from './views/History.vue'
import Transaction from './views/Transaction.vue'

const app = createApp({})

// Plugins
app.use(Antd)

// Components
app.component('account-history', History)
app.component('account-transaction', Transaction)
// Mount
app.mount('#app')
