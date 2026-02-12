import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#2563eb',
          secondary: '#1e40af',
          accent: '#3b82f6',
          error: '#dc2626',
          info: '#3b82f6',
          success: '#10b981',
          warning: '#f59e0b',
        },
      },
      dark: {
        colors: {
          primary: '#3b82f6',
          secondary: '#60a5fa',
          accent: '#2563eb',
        },
      },
    },
  },
  icons: {
    defaultSet: 'mdi',
  },
})

export default vuetify