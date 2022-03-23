import { createRouter, createWebHashHistory } from 'vue-router';

const router = createRouter({
    history: createWebHashHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            component: () => import('@/views/Index.vue'),
        },
        {
            path: '/kategori-iklan',
            component: () => import('@/views/KategoriIklan.vue'),
        },
        {
            path: '/jenis-iklan',
            component: () => import('@/views/JenisIklan.vue'),
        },
        {
            path: '/permintaan',
            component: () => import('@/views/Permintaan.vue'),
        },
        {
            path: '/permintaan-u',
            component: () => import('@/views/PermintaanU.vue'),
        },
        {
            path: '/user',
            component: () => import('@/views/User.vue'),
        },
        {
            path: '/profil',
            component: () => import('@/views/Profil.vue'),
        },
        {
            path: '/login',
            component: () => import('@/views/Login.vue'),
        },
        {
            path: '/register',
            component: () => import('@/views/Register.vue'),
        },
        {
            path: '/home',
            component: () => import('@/views/Home.vue'),
        }
    ],
});

export default router;
