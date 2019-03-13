import Vue from 'vue'
import VueRouter from 'vue-router'

import DashboardComponent from "./../pages/dashboard";
import DispositionsComponent from "./../pages/dispositions/index.vue";
import OrdersComponent from "./../pages/orders/index.vue";
import StocksComponent from "./../pages/dispositions/Stocks.vue";
import ReleaseComponent from "./../pages/releases/index.vue";

import OrdersAdminComponent from "./../pages/admin/orders/index.vue";
import {
    store
} from '../store/store'

Vue.use(VueRouter)

const routes = [{
        path: '/',
        name: 'dashboard',
        component: DashboardComponent,
    },
    {
        path: '/dispositions',
        name: 'dispositions',
        component: DispositionsComponent,
    },
    {
        path: '/orders',
        name: 'orders',
        component: OrdersComponent,
        beforeEnter: (to, from, next) => {
            if (store.state.user.role == 'Administrator') {
                return next({
                    name: 'dashboard'
                });
                
            } else {
                return next();
            }
        }
    },
    {
        path: '/stocks',
        name: 'stocks',
        component: StocksComponent,
    },
    {
        path: '/releases',
        name: 'releases',
        component: ReleaseComponent,
    },
    {
        path: '/orders-admin',
        name: 'orders-admin',
        component: OrdersAdminComponent,
        beforeEnter: (to, from, next) => {
            if (store.state.user.role == 'Administrator') {
                return next();
            } else {
                return next({
                    name: 'dashboard'
                });
            }
        }
    }
];

const router = new VueRouter({
    routes // short for `routes: routes`
});


export default router
