<script setup="">
    import { RouterView, RouterLink } from 'vue-router';
    import favicon from "@/assets/favicon.png";

    import '../assets/css/style.min.css';
</script>

<template>
    <div class="bg-primary" style="min-height: 100vh">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <RouterLink class="navbar-brand" to="/home">
                    <img :src="favicon" alt="" class="nav-img">
                </RouterLink>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <RouterLink class="nav-link" aria-current="page" to="/home">Home</RouterLink>
                        </li>
                        <li class="nav-item">
                            <RouterLink class="nav-link" aria-current="page" to="/login">Login</RouterLink>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-center">
                <RouterView></RouterView>
            </div>
        </div>
    </div>
</template>

<style>
    .nav-img {
        height: 30px;
    }

    .router-link-active {
        color: #707cd2 !important;
    }
</style>