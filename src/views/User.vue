<script setup>
    import { onMounted, reactive } from "vue";
    import Loading from "../components/Loading.vue";
    import ReqLabel from "../components/ReqLabel.vue";
    import Admin from "../components/Admin.vue";

    const state = reactive({
        data: {
            items: [],
        },
        is: {
            loading: true,
            submit: false,
        },
        req: {
            username: '',
            password: '',
            role: '',
            nama: '',
            telepon: '',
            email: '',
            foto: '',
        },
        req_auth: {
            username: '',
            password: '',
        },
    })

    function nullable() {
        state.req = {
            role: '',
        };
    }

    function get() {
        nullable();
        state.is.loading = true;
        jQuery('.modal.fade').modal('hide');

        http.get('/api/user').then(res => {
            state.is.loading = false;
            state.data.items = res.data;
        });
    }

    function store() {
        state.is.submit = true;
        http.post('/api/user', state.req).then(res => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        })
    }

    function update() {
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
        state.is.submit = true;
        http.put('/api/user/' + state.req_auth.id + '?auth=1', state.req_auth).then(res => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                state.req_auth = {};
                get();
            }
        })
    }

    function destroy() {
        state.is.submit = true;
        http.del('/api/user/' + state.req.id).then(res => {
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
    <Admin title="User">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h4 class="card-title">Data User</h4>
                    </div>
                    <div class="col-6" align="right">
                        <button class="btn btn-primary text-white" type="button" data-bs-toggle="modal" data-bs-target="#create" @click="nullable">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <Loading v-if="state.is.loading"></Loading>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="state.data.items.length" v-for="(item, i) in state.data.items">
                                <td>
                                    <a :href="item.foto_url" target="_blank">
                                        <img :src="item.foto_url" alt="" class="user-foto">
                                    </a>
                                </td>
                                <td>{{ item.nama }}</td>
                                <td>{{ item.telepon }}</td>
                                <td>{{ item.email }}</td>
                                <td>{{ item.role_detail }}</td>
                                <td align="center">
                                    <button class="btn me-1 btn-info btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#edit-auth" @click="
                                        state.req_auth.id = item.id;
                                        state.req_auth.username = item.username;
                                    ">
                                        <i class="fa fa-edit"></i> Autentikasi
                                    </button>
                                    <button class="btn me-1 btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#edit" @click="
                                        state.req = item;
                                        state.req.foto = '';
                                    ">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete" @click="state.req = item">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="6" align="center">data tidak tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Admin>

    <div class="modal fade" id="create">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="store">
                    <div class="modal-header">
                        <div class="modal-title">Tambah User</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>

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
                            <select class="form-control" required v-model="state.req.role">
                                <option disabled value="">Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                                <option value="3">Pimpinan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" @change="handleFoto" class="form-control" accept="image/*" title="Pilih Foto">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Username</ReqLabel>
                                    <input type="text" class="form-control" required placeholder="Masukkan Username" v-model="state.req.username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Password</ReqLabel>
                                    <input type="password" class="form-control" required placeholder="Masukkan Password" v-model="state.req.password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="update">
                    <div class="modal-header">
                        <div class="modal-title">Edit User</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>

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
                            <select class="form-control" required v-model="state.req.role">
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
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-auth">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="updateAuth">
                    <div class="modal-header">
                        <div class="modal-header">Edit Autentikasi</div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <ReqLabel>Username</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Username" v-model="state.req_auth.username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="Masukkan Password" v-model="state.req_auth.password">
                            <small class="form-control-feedback">Kosongkan jika tidak ingin mengganti</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="destroy">
                    <div class="modal-header">
                        <div class="modal-title">Hapus User</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <p>Anda yakin akan menghapus data?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger text-white">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
    .user-foto {
        height: 33px;
        width: 33px;
        object-fit: cover;
        border-radius: 100%;
    }
</style>