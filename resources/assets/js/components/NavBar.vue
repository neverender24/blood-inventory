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
                <ul class="navbar-nav navbar-nav-left header-links d-none d-md-flex">
                    <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            Schedule
            <span class="badge badge-primary ml-1">New</span>
          </a>
                    </li>-->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                              <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                              <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                            </svg></i> 
                            <router-link
                                to="/reports"
                            >
                            Reports</router-link>
                        </a>
                    </li>
                    <!-- <li class="nav-item active">
          <router-link to="/login" class="nav-link">
            <i class="mdi mdi-bookmark-plus-outline"></i>Score
          </router-link>
                    </li>-->
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link count-indicator dropdown-toggle"
                            id="notificationDropdown"
                            href="#"
                            data-toggle="dropdown"
                        >
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                </svg>
                            </i>
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
                                        <i class="mx-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                              <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                            </svg>
                                        </i>
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
                                        <i>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                              <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                            </svg>
                                        </i>
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
                            <a
                                class="dropdown-item mt-2"
                                @click="register()"
                            >Register User</a>
                            <a
                                class="dropdown-item"
                                @click="change()"
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
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </span>
                </button>
            </div>
        </nav>

        <register 
            v-if="registerModal"
            @closeModal="registerModal=false"
        ></register>

        <change-password
            v-if="changeModal"
            @closeModal="changeModal=false"
        ></change-password>
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
        return {
            bloodStations: [],
            registerModal: false,
            changeModal: false,
        };
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
        },
        change() {
            this.changeModal = true
        },
        register() {
            this.registerModal = true
        }
    }
};
</script>
