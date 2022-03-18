<script setup>
    const user = window.user;

    const items = [
        {
            path: '/',
            title: 'Home',
            icon: 'home',
        },
        {
            path: '/user',
            title: 'User',
            icon: 'users',
            role: '1',
        },
        {
            path: '/kategori-iklan',
            title: 'Kategori Iklan',
            icon: 'table',
            role: '1',
        },
        {
            path: '/jenis-iklan',
            title: 'Jenis Iklan',
            icon: 'file',
            role: '1',
        },
        {
            path: '/permintaan',
            title: 'Permintaan',
            icon: 'edit',
            role: '1',
        },
        {
            path: '/permintaan-u',
            title: 'Permintaan',
            icon: 'edit',
            role: '2',
        },
        {
            path: '/profil',
            title: 'Profil',
            icon: 'user',
        },
    ];

    function logout() {
        localStorage.removeItem('xid');
        localStorage.removeItem('xlevel');

        window.location = window.location.origin + window.location.pathname;
    }
</script>

<template>
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <template v-for="item in items">
                        <li class="sidebar-item pt-2" v-if="item.role ? (item.role == user.xrole ? true : false) : true">
                            <RouterLink class="sidebar-link waves-effect waves-dark sidebar-link" :to="item.path" aria-expanded="false">
                                <i :class="'fa fa-' + item.icon" aria-hidden="true"></i>
                                <span class="hide-menu">{{ item.title }}</span>
                            </RouterLink>
                        </li>
                    </template>
                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#logout" data-bs-toggle="modal" data-bs-target="#logout">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <span class="hide-menu">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="modal fade" id="logout">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="logout">
                    <div class="modal-header">
                        <div class="modal-title">Logout</div>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin akan logout?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger text-white">Logout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
    .sidebar-item .router-link-active {
        font-weight: 500 !important;
        border-left: 5px solid #41b3f9 !important;
        background: rgb(240, 240, 240) !important;
    }
</style>
