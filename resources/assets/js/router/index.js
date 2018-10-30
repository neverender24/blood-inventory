import Vue from 'vue'
import VueRouter from 'vue-router'

import DashboardComponent from "./../pages/dashboard"

Vue.use(VueRouter)

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardComponent,
    }
];

const router = new VueRouter({
    routes // short for `routes: routes`
});

export default router