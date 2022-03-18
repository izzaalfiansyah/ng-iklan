<script setup="">
    import { reactive } from 'vue';
    import { RouterLink, useRouter } from 'vue-router';
    import Loading from "@/components/Loading.vue";
    import ReqLabel from "@/components/ReqLabel.vue";

    const state = reactive({
        req: {
            username: '',
            password: '',
            nama: '',
            role: '2',
            telepon: '',
            email: '',
        },
        is: {
            submit: false,
        }
    });

    function register() {
        http.post('/api/user', state.req).then(res => {
            if (res.error) {
                notif(res.message, 'warning', true);
            } else {
                localStorage.setItem('xid', res.data.id);
                localStorage.setItem('xrole', res.data.role);

                notif('data berhasil terdaftar', 'success').then(() => {
                    window.location = window.location.origin + window.location.pathname;
                });
            }
        });
    }
</script>

<template>
    <div class="col-lg-6 col-md-8">
        <div class="card mt-5">
            <form @submit.prevent="register" class="card-body">
                <h4 class="card-title">Register</h4>

                <hr />

                <Loading v-if="state.is.submit"></Loading>

                <div class="form-group">
                    <ReqLabel>Nama</ReqLabel>
                    <input type="text" class="form-control" required placeholder="Masukkan Nama" v-model="state.req.nama" />
                </div>

                <div class="form-group">
                    <ReqLabel>Telepon</ReqLabel>
                    <input type="text" class="form-control" required placeholder="Masukkan Telepon" v-model="state.req.telepon" />
                </div>

                <div class="form-group">
                    <ReqLabel>Email</ReqLabel>
                    <input type="email" class="form-control" required placeholder="Masukkan Email" v-model="state.req.email" />
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <ReqLabel>Username</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Username" v-model="state.req.username" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <ReqLabel>Password</ReqLabel>
                            <input type="password" class="form-control" required placeholder="Masukkan Password" v-model="state.req.password" />
                        </div>
                    </div>
                </div>

                <p align="center">Sudah punya akun? login <RouterLink to="/login">disini</RouterLink></p>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>
