import './bootstrap';
import '../css/app.css';

import { createPinia } from 'pinia'
import {createApp} from 'vue';
import piniaPersist from 'pinia-plugin-persist'

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

import App from './views/App.vue';
import router from './router.js'

const pinia = createPinia()
pinia.use(piniaPersist);

const app = createApp(App);
app.use(pinia);
app.use(router);
app.use(ElementPlus);
app.mount('#app');

