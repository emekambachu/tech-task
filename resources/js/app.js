import './bootstrap';
import '@/css/app.css';
import '@/css/custom.css';

import { createApp, provide } from 'vue';
import App from './App.vue';
import router from './router/index';
import { defineAsyncComponent } from 'vue';
import store from "./store";

const app = createApp(App)
    .use(router)
    .use(store);

app.mount('#app');
