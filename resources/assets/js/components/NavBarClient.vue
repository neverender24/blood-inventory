<template>
    <div>
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div
                class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center"
            >
                <a class="navbar-brand brand-logo" href="index.html">
                    <!-- <img src="images/logo.svg" alt="logo"> -->
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <!-- <img src="images/logo-mini.svg" alt="logo"> -->
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link count-indicator dropdown-toggle"
                            id="notificationDropdown"
                            href="#"
                            data-toggle="dropdown"
                        >
                            <i class="fa fa-bell"></i>
                            <span
                                class="count"
                            >{{ notifications.near_expire.length + notifications.pending_orders }}</span>
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown"
                        >
                            <a class="dropdown-item">
                                <p
                                    class="mb-0 font-weight-normal float-left"
                                >You have {{ notifications.near_expire.length + notifications.pending_orders }} new notifications</p>
                                <span class="badge badge-pill badge-warning float-right">View all</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <router-link
                                to="/dispositions"
                                class="dropdown-item preview-item"
                                v-if="notifications.near_expire.length!=0"
                            >
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="fa fa-exclamation-circle mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6
                                        class="preview-subject font-weight-medium text-dark"
                                    >Near Expiry</h6>
                                    <p
                                        class="font-weight-light small-text"
                                    >You have {{ notifications.near_expire.length }} near expirations</p>
                                </div>
                            </router-link>
                            <div class="dropdown-divider"></div>
                            <router-link
                                to="/orders-admin"
                                class="dropdown-item preview-item"
                                v-if="notifications.pending_orders!=0"
                            >
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="fa fa-exclamation-circle mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6
                                        class="preview-subject font-weight-medium text-dark"
                                    >Pending Orders</h6>
                                    <p
                                        class="font-weight-light small-text"
                                    >You have {{ notifications.pending_orders }} in queue</p>
                                </div>
                            </router-link>
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none d-xl-inline-block">
                        <a
                            class="nav-link dropdown-toggle"
                            id="UserDropdown"
                            href="#"
                            data-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="profile-text">Hello, {{ user.name }}</span>
                            <img
                                class="img-xs rounded-circle"
                                src="images/faces/face1.jpg"
                                alt="Profile image"
                            />
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="UserDropdown"
                        >
                            <a class="dropdown-item p-0">
                                <div class="d-flex border-bottom">
                                    <div
                                        class="py-3 px-4 d-flex align-items-center justify-content-center"
                                    >
                                        <i class="fa fa-bookmark mr-0 text-gray"></i>
                                    </div>
                                    <div
                                        class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right"
                                    >
                                        <i class="fa fa-user mr-0 text-gray"></i>
                                    </div>
                                    <div
                                        class="py-3 px-4 d-flex align-items-center justify-content-center"
                                    >
                                        <i class="fa fa-address-book mr-0 text-gray"></i>
                                    </div>
                                </div>
                            </a>
                            <a
                                class="dropdown-item"
                                data-toggle="modal"
                                data-target="#changePasswordModal"
                            >Change Password</a>
                            <a href="#" v-on:click="logout()" class="dropdown-item">Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button
                    class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                    type="button"
                    data-toggle="offcanvas"
                >
                    <span class="fa fa-bars"></span>
                </button>
            </div>
        </nav>
        <register></register>
        <change-password></change-password>
    </div>
</template>

<script>
import Register from "../pages/users/register";
import ChangePassword from "../pages/users/changePassword";
import { mapState } from "vuex";

export default {
    props: ["notifications"],
    components: {
        Register,
        ChangePassword
    },
    data() {
        return {};
    },
    computed: {
        ...mapState(["user"])
    },
    methods: {
        logout() {
            axios
                .post("logout")
                .then(response => {})
                .catch(error => {
                    location.reload();
                });
        }
    }
};
</script>
