import { createApp } from "vue";
import { createPinia } from "pinia";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import vRipple from "./directives/vRipple";

import App from "./App.vue";
import router from "./router";

import "./assets/css/main.scss";

const app = createApp(App);
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia);
app.use(ElementPlus);
app.use(router);
app.directive('ripple', vRipple);

app.mount("#app");
