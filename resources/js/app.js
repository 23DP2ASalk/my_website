import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import Dashboard from './components/Dashboard.vue'
import StatisticsIndex from './components/StatisticsIndex.vue'
import StatisticsCreate from './components/StatisticsCreate.vue'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: localStorage.getItem('theme') || 'light',
    themes: {
      light: {
        colors: {
          primary: '#2563eb',
          secondary: '#1e40af',
          accent: '#3b82f6',
        }
      },
      dark: {
        colors: {
          primary: '#2563eb',
          secondary: '#1e40af',
          accent: '#3b82f6',
        }
      }
    }
  }
})

const app = createApp({})

app.component('dashboard', Dashboard)
app.component('statistics-index', StatisticsIndex)
app.component('statistics-create', StatisticsCreate)

app.use(vuetify)
app.mount('#app')