import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        user: [],
        bloodTypes: [],
        bloodComponents: [],
        pendingOrders: '',
        loading: false
    },
    getters: {
        isAdmin: state => {
            var data
            axios.get("get-user").then(response => {
                this.user = response.data;
            });
            return data
        }
    },
    mutations: {
        updateUser(state, data) {
            state.user = data
        },
        updateBloodTypes(state, data) {
            state.bloodTypes = data
        },
        updateBloodComponents(state, data) {
            state.bloodComponents = data
        },
        updatePendingOrders(state, data) {
            state.pendingOrders = data
        },
        updateLoading(state, data) {
            state.loading = data
        }
    },
    actions: {
        loadUser({
            commit
        }) {
            axios.get("get-user").then(response => {
                commit('updateUser', response.data)
            });
        },
        loadBloodTypes({
            commit
        }) {
            axios.get("blood-types").then(response => {
                commit('updateBloodTypes', response.data)
            });

        },
        loadBloodComponents({
            commit
        }) {
            axios.get("blood-components").then(response => {
                commit('updateBloodComponents', response.data)
            });

        },
        countPendingOrders({
            commit
        }) {
            axios.get("pending-orders").then(response => {
                commit('updatePendingOrders', response.data)
            });

        },
        toggleLoading(
            context, bool
        ) {
            context.commit('updateLoading', bool)
        }
    },
})
