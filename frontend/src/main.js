import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import  VueCookies  from 'vue-cookies'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import {fas} from "@fortawesome/free-solid-svg-icons"
import {far} from "@fortawesome/free-regular-svg-icons"
import {fab} from "@fortawesome/free-brands-svg-icons"

const pinia = createPinia()
const app = createApp(App)

library.add(far, fas, fab)

app.use(pinia)
app.use(router)
app.use(VueCookies)
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount('#app')