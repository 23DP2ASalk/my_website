import './bootstrap';
import { createApp } from 'vue';
import vuetify from './plugins/vuetify';

// Import components
import Dashboard from './components/Dashboard.vue';

const app = createApp({});

app.component('dashboard', Dashboard);

app.use(vuetify);

app.mount('#app');