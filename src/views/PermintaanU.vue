<script setup>
    import { onMounted, reactive } from 'vue';
    import Loading from '../components/Loading.vue';
    import Admin from '../components/Admin.vue';
    import ReqLabel from '../components/ReqLabel.vue';

    const user = window.user;

    const state = reactive({
        data: {
            items: [
                {
                    iklan: {},
                },
            ],
            iklan: [],
        },
        is: {
            loading: true,
            submit: false,
        },
        req: {
            iklan_id: '',
            link_video: '',
            status: '0',
            bukti_pembayaran: '',
            jumlah_tayang: 0,
            user_id: user.xid,
            dibuatkan: '',
        },
    });

    function get() {
        state.req = {
            iklan_id: '',
            link_video: '',
            status: '0',
            bukti_pembayaran: '',
            jumlah_tayang: 0,
            user_id: user.xid,
            dibuatkan: '',
        };
        state.is.loading = true;
        jQuery('.modal.fade').modal('hide');

        state.data.items = [];
        http.get('/api/iklan/request', {
            user_id: user.xid,
        }).then((res) => {
            state.is.loading = false;
            state.data.items = res.data;
        });
    }
    
    function getIklan() {
        state.data.iklan = [];
        http.get('/api/iklan').then(res => {
            state.data.iklan = res.data;
        })
    }

    function store() {
        state.is.submit = true;
        http.post('/api/iklan/request', state.req).then((res) => {
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
        http.put('/api/iklan/request/' + state.req.id, state.req).then((res) => {
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
        http.del('/api/iklan/request/' + state.req.id).then((res) => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                get();
            }
        });
    }

    function handleBuktiPembayaran(e) {
        toBase64(e.target.files[0], res => {
            state.req.bukti_pembayaran = res;
        });
    }

    onMounted(async () => {
        await get();
        await getIklan();
    });
</script>

<template>
    <Admin title="Permintaan">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h4 class="card-title">Data Permintaan Saya</h4>
                    </div>
                    <div class="col-6" align="right">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#create">
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
                                <th>Jenis</th>
                                <th>Link Video</th>
                                <th>Tarif</th>
                                <th>Durasi</th>
                                <th>Jumlah Tayang</th>
                                <th>Harga Pembuatan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="state.data.items.length" v-for="item in state.data.items">
                                <td>{{ item.iklan.jenis }}</td>
                                <td>
                                    <a target="_blank" :href="item.link_video">{{ item.link_video }}</a>
                                </td>
                                <td>{{ item.iklan.tarif_detail }}</td>
                                <td>{{ item.iklan.durasi_detail }}</td>
                                <td>{{ item.jumlah_tayang }}</td>
                                <td>{{ item.dibuatkan == '1' ? item.iklan.harga_pembuatan_detail : 'Rp.0' }}</td>
                                <td>{{ item.total }}</td>
                                <td>
                                    <span :class="'badge bg-' + (item.status == '1' ? 'success' : 'warning')">{{ item.status_detail }}</span>
                                </td>
                                <td>
                                    <a class="badge bg-primary" target="_blank" :href="item.bukti_pembayaran_url" v-if="item.bukti_pembayaran_url">Lihat</a>
                                    <span v-else="">Tidak tersedia</span>
                                </td>
                                <td align="center">
                                    <button class="btn btn-sm me-1 btn-info" type="button" data-bs-toggle="modal" data-bs-target="#pembayaran" @click="
                                        state.req = item
                                        state.req.bukti_pembayaran = '';
                                    ">
                                        <i class="fa fa-edit"></i> Pembayaran
                                    </button>
                                    <button class="btn btn-sm me-1 btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#edit" @click="
                                        state.req = item
                                        state.req.bukti_pembayaran = '';
                                    ">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete" @click="state.req = item">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="10" align="center">data tidak tersedia</td>
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
                        <div class="modal-title">Tambah Permintaan</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <div class="form-group">
                            <ReqLabel>Jenis Iklan</ReqLabel>
                            <select class="form-control" v-model="state.req.iklan_id">
                                <option value="" disabled>Pilih Jenis Iklan</option>
                                <option v-for="item in state.data.iklan" :value="item.id">
                                    {{ item.jenis }}
                                    ({{ item.tarif_detail }} - {{ item.durasi_detail }})
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Link Video</ReqLabel>
                            <input type="url" class="form-control" v-model="state.req.link_video" placeholder="Masukkan Link Video" required>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Dibuatkan</ReqLabel>
                            <select v-model="state.req.dibuatkan" class="form-control" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Jumlah Tayang</ReqLabel>
                            <input type="number" class="form-control" v-model="state.req.jumlah_tayang" placeholder="Masukkan Jumlah Tayang" required>
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
    
    <div class="modal fade" id="edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="update">
                    <div class="modal-header">
                        <div class="modal-title">Edit Permintaan</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <div class="form-group">
                            <ReqLabel>Jenis Iklan</ReqLabel>
                            <select class="form-control" v-model="state.req.iklan_id">
                                <option value="" disabled>Pilih Jenis Iklan</option>
                                <option v-for="item in state.data.iklan" :value="item.id">
                                    {{ item.jenis }}
                                    ({{ item.tarif_detail }} - {{ item.durasi_detail }})
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Link Video</ReqLabel>
                            <input type="url" class="form-control" v-model="state.req.link_video" placeholder="Masukkan Link Video" required>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Dibuatkan</ReqLabel>
                            <select v-model="state.req.dibuatkan" class="form-control" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <ReqLabel>Jumlah Tayang</ReqLabel>
                            <input type="number" class="form-control" v-model="state.req.jumlah_tayang" placeholder="Masukkan Jumlah Tayang" required>
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

    <div class="modal fade" id="pembayaran">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="update">
                    <div class="modal-header">
                        <div class="modal-title">Edit Pembayaran</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>
                        <div class="form-group">
                            <ReqLabel>Pilih File</ReqLabel>
                            <input type="file" accept="image/*" class="form-control" required @change="handleBuktiPembayaran">
                            <small class="form-control-feedback">Kirim screenshot gambar bukti pembayaran</small>
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
                        <div class="modal-title">Hapus Permintaan</div>
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
