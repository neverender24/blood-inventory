require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import App from './App.vue'
import router from "./router"
import Toasted from 'vue-toasted';
import Snotify from 'vue-snotify'; 
import Vuelidate from 'vuelidate';
import 'es6-promise/auto';
import { store } from './store/store'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
Vue.use(Vuelidate)
Vue.use(Snotify)
Vue.use(Toasted)


const app = new Vue({
    store: store,
    el: "#app",
    template: "<app></app>",
    components: {
        App,
    },
    mounted(){
        this.$store.dispatch('loadUser')
    },
    router
}); 
