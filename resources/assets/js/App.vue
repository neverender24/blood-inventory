<template>
    <div class="container-scroller">
            <nav-bar></nav-bar>
            <div class="container-fluid page-body-wrapper">
                <side-bar></side-bar>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>

    import NavBar from './components/NavBar.vue'
    import SideBar from './components/SideBar.vue'
    
    export default {
        components:{
            NavBar, 
            SideBar,
        }

    }
</script>
