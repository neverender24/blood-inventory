<template>
    <div class="container-scroller">
        <nav-bar :notifications="notifications" v-if="user.role == 'Administrator'"></nav-bar>
        <nav-bar-client :notifications="notifications" v-else></nav-bar-client>
        <div class="container-fluid page-body-wrapper">
            <side-bar v-if="user.role == 'Administrator'"></side-bar>
            <side-bar-client v-else></side-bar-client>
            <div class="main-panel">
                <div class="content-wrapper">
                    <router-view
                        @notify="setNotifications()"
                        :pending_orders="notifications.pending_orders"
                        :near_expire="notifications.near_expire"
                        :expired="notifications.expired"
                        :actual_expire="actual_expire"
                        :actual_near_expire="actual_near_expire"
                    ></router-view>
                </div>
            </div>
        </div>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
import NavBar from "./components/NavBar.vue";
import NavBarClient from "./components/NavBarClient.vue";
import SideBar from "./components/SideBar.vue";
import SideBarClient from "./components/SideBarClient.vue";
import { mapState } from "vuex";

export default {
    components: {
        NavBar,
        SideBar,
        SideBarClient,
        NavBarClient
    },

    data() {
        return {
            notifications: {
                near_expire: [],
                expired: [],
                pending_orders: 0
            },
            actual_expire: {},
            actual_near_expire: {}
        };
    },

    mounted() {},

    computed: {
        ...mapState(["user"])
    },

    methods: {
        setNotifications() {
            axios.post("near-expired-blood", this.user).then(response => {
                this.notifications.near_expire = response.data;
                this.actual_expire = _.groupBy(
                    response.data,
                    "blood_type.description"
                );
            });

            axios.post("expired-blood", this.user).then(response => {
                this.notifications.expired = response.data;
                this.actual_near_expire = _.groupBy(
                    response.data,
                    "blood_type.description"
                );
            });
        }
    }
};
</script>
