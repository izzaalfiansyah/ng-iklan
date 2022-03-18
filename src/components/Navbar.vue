<script setup="">
    import favicon from "@/assets/favicon.png";
    import { RouterLink } from "vue-router";
</script>

<template>
    <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header" data-logobg="skin6">
                <RouterLink class="navbar-brand text-dark text-center" to="/">
                    <!-- <b class="logo-icon">
                        NG
                    </b> -->
                    <span class="logo-text">
                        <img :src="favicon" alt="" style="height: 50px;">
                    </span>
                </RouterLink>
                <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            </div>
            <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                <ul class="navbar-nav d-none d-md-block d-lg-none">
                    <li class="nav-item">
                        <a class="nav-toggler nav-link waves-effect waves-light text-white" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <li class="in">
                        <form role="search" class="app-search d-none d-md-block me-3">
                            <input type="text" placeholder="Cari sesuatu..." class="form-control mt-0" />
                            <a href="" class="active">
                                <i class="fa fa-search"></i>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>
