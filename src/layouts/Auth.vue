<script setup="">
    import { RouterView } from "vue-router";

    import '../assets/css/style.min.css';
</script>

<template>
    <div class="bg-primary" style="min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <RouterView></RouterView>
            </div>
        </div>
    </div>
</template>