<script setup>
    import Dashboard from './layouts/Dashboard.vue';
    import Auth from './layouts/Auth.vue';
    import { useRouter } from "vue-router";

    const router = useRouter();
    const user = window.user;

    if (!user.xid) {
        router.replace('/home');
    }
</script>

<template>
    <Dashboard v-if="user.xid"></Dashboard>
    <Auth v-else></Auth>
</template>
