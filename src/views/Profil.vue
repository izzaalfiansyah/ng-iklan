<script setup="">
    import { onMounted, reactive } from "vue";
    import Admin from "@/components/Admin.vue";
    import ReqLabel from "@/components/ReqLabel.vue";
import Loading from "../components/Loading.vue";

    const state = reactive({
        req: {
            username: '',
            password: '',
            nama: '',
            role: '',
            telepon: '',
            email: '',
            foto: '',
        },
        is: {
            submit: false,
            loading: true,
        }
    })

    function get() {
        state.req = {};
        state.is.loading = true;
        http.get('/api/user/' + user.xid).then(res => {
            state.is.loading = false;
            state.req = res.data;
            state.req.foto = '';
            state.req.password = '';
        })
    }

    function update() {
        window.scrollTo(0, 0);
        state.is.submit = true;
        http.put('/api/user/' + state.req.id, state.req).then(res => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        })
    }

    function updateAuth() {
        window.scrollTo(0, 0);
        state.is.submit = true;
        http.put('/api/user/' + state.req.id + '?auth=1', {
            username: state.req.username,
            password: state.req.password,
        }).then(res => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        })
    }

    function handleFoto(e) {
        toBase64(e.target.files[0], res => {
            state.req.foto = res;
        })
    }

    onMounted(async () => {
        await get();
    })
</script>

<template>
    <Admin title="Profil">
        <Loading v-if="state.is.submit || state.is.loading"></Loading>
        <div class="row">
            <div class="col-lg-4 col-xlg-3 col-md-12">
                <div class="white-box">
                    <div class="user-bg" v-if="!state.is.loading">
                        <img width="100%" alt="user" :src="state.req.foto_url" />
                        <div class="overlay-box">
                            <div class="user-content">
                                <a :href="state.req.foto_url" target="_blank"><img :src="state.req.foto_url" style="object-fit: cover;" class="thumb-lg img-circle" alt="img" /></a>
                                <h4 class="text-white mt-2">{{ state.req.username }}</h4>
                                <h5 class="text-white mt-2">{{ state.req.email }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="user-btm-box mt-5">
                        <div class="text-center">
                            {{ state.req.telepon }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-xlg-9 col-md-12">
                <div class="card">
                    <form class="card-body" @submit.prevent="update">
                        <h4 class="card-title">Data</h4>

                        <hr>        

                        <div class="form-group">
                            <ReqLabel>Nama</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Nama" v-model="state.req.nama">
                        </div>

                        <div class="form-group">
                            <ReqLabel>Telepon</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Telepon" v-model="state.req.telepon">
                        </div>

                        <div class="form-group">
                            <ReqLabel>Email</ReqLabel>
                            <input type="email" class="form-control" required placeholder="Masukkan Email" v-model="state.req.email">
                        </div>

                        <div class="form-group">
                            <ReqLabel>Role</ReqLabel>
                            <select class="form-control" required v-model="state.req.role" disabled>
                                <option disabled value="">Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                                <option value="3">Pimpinan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" @change="handleFoto" class="form-control" accept="image/*" title="Pilih Foto">
                            <small class="form-control-feedback">Kosongkan jika tidak ingin mengganti</small>
                        </div>

                        <div class="mt-3" align="right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="card">
                    <form @submit.prevent="updateAuth" class="card-body">
                        <h4 class="card-title">Autentikasi</h4>

                        <hr>

                        <div class="form-group">
                            <ReqLabel>Username</ReqLabel>
                            <input type="text" class="form-control" required v-model="state.req.username" placeholder="Masukkan Username">
                        </div>

                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" v-model="state.req.password" class="form-control" placeholder="Masukkan Password">
                            <small class="form-control-feedback">Kosongkan jika tidak ingin mengganti</small>
                        </div>

                        <div class="mt-3" align="right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Admin>
</template>
