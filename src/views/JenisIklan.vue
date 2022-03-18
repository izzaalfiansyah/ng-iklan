<script setup>
    import { onMounted, reactive } from 'vue';
    import Loading from '../components/Loading.vue';
    import ReqLabel from '../components/ReqLabel.vue';
    import Admin from '../components/Admin.vue';

    const state = reactive({
        data: {
            items: [
                {
                    kategori: {},
                },
            ],
            kategori: [],
        },
        is: {
            loading: true,
            submit: false,
        },
        req: {
            jenis: '',
            sub_jenis: '',
            kategori_id: '',
            durasi: '',
            tarif: '',
            keterangan: '',
        },
    });

    function get() {
        state.req = {
            jenis: '',
            sub_jenis: '',
            kategori_id: '',
            durasi: '',
            tarif: '',
            keterangan: '',
        };
        state.is.loading = true;
        jQuery('.modal.fade').modal('hide');
        state.data.items = [];

        http.get('/api/iklan').then((res) => {
            state.is.loading = false;
            state.data.items = res.data;
        });
    }

    function getKategori() {
        http.get('/api/iklan/kategori').then((res) => {
            state.data.kategori = res.data;
        });
    }

    function store() {
        state.is.submit = true;
        http.post('/api/iklan', state.req).then((res) => {
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
        http.put('/api/iklan/' + state.req.id, state.req).then((res) => {
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
        http.del('/api/iklan/' + state.req.id).then((res) => {
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
        await getKategori();
        await get();
    });
</script>

<template>
    <Admin title="Jenis Iklan">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h4 class="card-title">Data Jenis Iklan</h4>
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
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Durasi</th>
                                <th>Tarif</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="state.data.items.length" v-for="item in state.data.items">
                                <tr>
                                    <td>
                                        {{ item.jenis }}
                                        <br v-if="item.sub_jenis" />
                                        <small v-if="item.sub_jenis">{{ item.sub_jenis }}</small>
                                    </td>
                                    <td>{{ item.kategori.nama }}</td>
                                    <td>{{ item.durasi_detail }}</td>
                                    <td>{{ item.tarif_detail }}</td>
                                    <td align="center">
                                        <button class="btn me-1 btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#edit" @click="state.req = item">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete" @click="state.req = item">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan:</td>
                                    <td colspan="4">{{ item.keterangan }}</td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="5" align="center">data tidak tersedia</td>
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
                        <div class="modal-title">Tambah Jenis Iklan</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>

                        <div class="form-group">
                            <ReqLabel>Jenis</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Jenis" v-model="state.req.jenis" />
                        </div>

                        <div class="form-group">
                            <label for="">Detail Jenis</label>
                            <input type="text" class="form-control" placeholder="Masukkan Detail Dari Jenis Iklan" v-model="state.req.sub_jenis" />
                        </div>

                        <div class="form-group">
                            <ReqLabel>Kategori</ReqLabel>
                            <select required class="form-control" v-model="state.req.kategori_id">
                                <option value="" disabled>Pilih Kategori</option>
                                <option v-for="item in state.data.kategori" :value="item.id">{{ item.nama }}</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Durasi (dalam detik)</ReqLabel>
                                    <input type="number" required v-model="state.req.durasi" placeholder="Masukkan Durasi" class="form-control" min="0" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Tarif</ReqLabel>
                                    <input type="number" required v-model="state.req.tarif" placeholder="Masukkan Tarif" class="form-control" min="0" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea v-model="state.req.keterangan" rows="3" placeholder="Masukkan Keterangan" class="form-control"></textarea>
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
                        <div class="modal-title">Edit Jenis Iklan</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>

                        <div class="form-group">
                            <ReqLabel>Jenis</ReqLabel>
                            <input type="text" class="form-control" required placeholder="Masukkan Jenis" v-model="state.req.jenis" />
                        </div>

                        <div class="form-group">
                            <label for="">Detail Jenis</label>
                            <input type="text" class="form-control" placeholder="Masukkan Detail Dari Jenis Iklan" v-model="state.req.sub_jenis" />
                        </div>

                        <div class="form-group">
                            <ReqLabel>Kategori</ReqLabel>
                            <select required class="form-control" v-model="state.req.kategori_id">
                                <option value="" disabled>Pilih Kategori</option>
                                <option v-for="item in state.data.kategori" :value="item.id">{{ item.nama }}</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Durasi (dalam detik)</ReqLabel>
                                    <input type="number" required v-model="state.req.durasi" placeholder="Masukkan Durasi" class="form-control" min="0" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ReqLabel>Tarif</ReqLabel>
                                    <input type="number" required v-model="state.req.tarif" placeholder="Masukkan Tarif" class="form-control" min="0" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea v-model="state.req.keterangan" rows="3" placeholder="Masukkan Keterangan" class="form-control"></textarea>
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
                        <div class="modal-title">Hapus Jenis Iklan</div>
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
