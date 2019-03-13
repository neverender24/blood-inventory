<template>
    <div>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="profile-image">
                  <img src="images/faces/face1.jpg" alt="profile image">
                </div>
                <div class="text-wrapper">
                  <p class="profile-name">{{ user.name }}</p>
                  <div>
                    <small class="designation text-muted">{{ user.position }}</small>
                    <span class="status-indicator online"></span>
                  </div>
                </div>
              </div>
              <!-- <button class="btn btn-success btn-block" data-toggle="modal" data-target="#orderModal">New Oder
                <i class="mdi mdi-plus"></i>
              </button> -->
            </div>
          </li>
          <li class="nav-item">
            <router-link to="/" class="nav-link">
              <i class="menu-icon mdi mdi-television"></i>
              <span class="menu-title">Dashboard</span>
            </router-link>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="orders" class="nav-link">
              <i class="menu-icon fa fa-tint"></i>
              <span class="menu-title">Orders</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="stocks" class="nav-link">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-title">Stocks</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="releases" class="nav-link">
              <i class="menu-icon fa fa-list"></i>
              <span class="menu-title">Releases</span>
            </router-link>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="menu-icon mdi mdi-restart"></i>
              <span class="menu-title">More Options</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/blank-page.html"><i class="menu-icon fa fa-users"></i> Users </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="pages/samples/login.html"><i class="menu-icon fa fa-hospital-o"></i> Blood Stations </a>
                </li>
              </ul>
            </div>
          </li> -->
        </ul>
      </nav>
  
    </div>
</template>

<style>
a:link{
    color: #000
}
</style>

<script>
import { mapState } from 'vuex'
export default {
    computed: {
        ...mapState([
            'user',
        ])
    },
}; 
</script>

 