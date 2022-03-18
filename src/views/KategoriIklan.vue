<script setup>
    import { onMounted, reactive } from 'vue';
    import Loading from '../components/Loading.vue';
    import ReqLabel from '../components/ReqLabel.vue';
    import Admin from '../components/Admin.vue';

    const state = reactive({
        data: {
            items: [],
        },
        is: {
            loading: true,
            submit: false,
        },
        req: {
            nama: '',
        },
    });

    function get() {
        state.req = {
            nama: '',
        };
        state.is.loading = true;
        jQuery('.modal.fade').modal('hide');

        http.get('/api/iklan/kategori').then((res) => {
            state.is.loading = false;
            state.data.items = res.data;
        });
    }

    function store() {
        state.is.submit = true;
        http.post('/api/iklan/kategori', state.req).then((res) => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        });
    }

    function update() {
        state.is.submit = true;
        http.put('/api/iklan/kategori/' + state.req.id, state.req).then((res) => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        });
    }

    function destroy() {
        state.is.submit = true;
        http.del('/api/iklan/kategori/' + state.req.id).then((res) => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        });
    }

    onMounted(async () => {
        await get();
    });
</script>

<template>
    <Admin title="Kategori Iklan">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h4 class="card-title">Data Kategori Iklan</h4>
                    </div>
                    <div class="col-6" align="right">
                        <button class="btn btn-primary text-white" type="button" data-bs-toggle="modal" data-bs-target="#create">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                </div>

                <hr />

                <div class="table-responsive">
                    <Loading v-if="state.is.loading"></Loading>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="state.data.items.length" v-for="item in state.data.items">
                                <td>{{ item.nama }}</td>
                                <td align="center">
                                    <button class="btn me-1 btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#edit" @click="state.req = item">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete" @click="state.req = item">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="3" align="center">data tidak tersedia</td>
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
                        <div class="modal-title">Tambah Kategori</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <div class="form-group">
                            <ReqLabel>Nama Kategori</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Nama Kategori" v-model="state.req.nama" />
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
                        <div class="modal-title">Edit Kategori</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <div class="form-group">
                            <ReqLabel>Nama Kategori</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Nama Kategori" v-model="state.req.nama" />
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

    <div class="modal fade" id="delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="destroy">
                    <div class="modal-header">
                        <div class="modal-title">Hapus Kategori</div>
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
