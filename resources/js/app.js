import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

// Import all components
import Dashboard from './components/Dashboard.vue'
import StatisticsIndex from './components/StatisticsIndex.vue'
import StatisticsCreate from './components/StatisticsCreate.vue'
import StatisticsEdit from './components/StatisticsEdit.vue'
import Analytics from './components/Analytics.vue'
import Exports from './components/Exports.vue'
import AdminDashboard from './components/AdminDashboard.vue'
import AdminUsers from './components/AdminUsers.vue'

// Create Vuetify instance
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
          error: '#dc2626',
          info: '#0ea5e9',
          success: '#10b981',
          warning: '#f59e0b',
        }
      },
      dark: {
        colors: {
          primary: '#2563eb',
          secondary: '#1e40af',
          accent: '#3b82f6',
          error: '#dc2626',
          info: '#0ea5e9',
          success: '#10b981',
          warning: '#f59e0b',
        }
      }
    }
  }
})

// Create Vue app
const app = createApp({})

// Register all components
app.component('dashboard', Dashboard)
app.component('statistics-index', StatisticsIndex)
app.component('statistics-create', StatisticsCreate)
app.component('statistics-edit', StatisticsEdit)
app.component('analytics', Analytics)
app.component('exports', Exports)
app.component('admin-dashboard', AdminDashboard)
app.component('admin-users', AdminUsers)

// Use Vuetify
app.use(vuetify)

// Mount app
app.mount('#app')
