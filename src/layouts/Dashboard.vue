<script setup>
    import { RouterView, useRouter } from "vue-router";

    import '../assets/css/style.min.css';
    import '../assets/plugins/bower_components/jquery/dist/jquery.min.js';
    import '../assets/bootstrap/dist/js/bootstrap.js';
    import '../assets/js/app-style-switcher.js';
    import '../assets/js/waves.js';
    import '../assets/js/sidebarmenu.js';
    import '../assets/js/custom.js';

    import Sidebar from '@/components/Sidebar.vue'
    import Navbar from '@/components/Navbar.vue'
    import Footer from '@/components/Footer.vue'
    
    const router = useRouter();
    
    if (!user.xid) {
        router.replace('/login');
    }
</script>

<template>
    <!-- <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div> -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <Navbar></Navbar>
        <Sidebar></Sidebar>
        <div class="page-wrapper" style="min-height: 250px">
            <RouterView></RouterView>
            <Footer></Footer>
        </div>
    </div>
</template>
