<script setup>
    import { onMounted, reactive } from 'vue';
    import Loading from '../components/Loading.vue';
    import Admin from '../components/Admin.vue';
    import ReqLabel from '../components/ReqLabel.vue';

    const state = reactive({
        data: {
            items: [
                {
                    iklan: {},
                    user: {},
                },
            ],
        },
        is: {
            loading: true,
            submit: false,
        },
        req: {
            iklan_id: '',
            link_video: '',
            status: '',
            bukti_pembayaran: '',
        },
    });

    function get() {
        state.req = {
            iklan_id: '',
            link_video: '',
            status: '',
            bukti_pembayaran: '',
        };
        state.is.loading = true;
        jQuery('.modal.fade').modal('hide');

        state.data.items = [];
        http.get('/api/iklan/request').then((res) => {
            state.is.loading = false;
            state.data.items = res.data;
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

    onMounted(async () => {
        await get();
    });
</script>

<template>
    <Admin title="Permintaan">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Permintaan</h4>

                <hr />

                <div class="table-responsive">
                    <Loading v-if="state.is.loading"></Loading>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Pemesan</th>
                                <th>Link Video</th>
                                <th>Tarif</th>
                                <th>Durasi</th>
                                <th>Jumlah Tayang</th>
                                <th>Biaya Pembuatan</th>
                                <th>Total</th>
                                <th>Nomor Rekening</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="state.data.items.length" v-for="item in state.data.items">
                                <td>{{ item.iklan.jenis }}</td>
                                <td>{{ item.user.nama }}</td>
                                <td>
                                    <a target="_blank" :href="item.link_video">{{ item.link_video }}</a>
                                </td>
                                <td>{{ item.iklan.tarif_detail }}</td>
                                <td>{{ item.iklan.durasi_detail }}</td>
                                <td>{{ item.jumlah_tayang }}</td>
                                <td>{{ item.dibuatkan == '1' ? item.iklan.harga_pembuatan_detail : 'Rp.0' }}</td>
                                <td>{{ item.total }}</td>
                                <td>{{ item.nomor_rekening }}</td>
                                <td>
                                    <span :class="'badge bg-' + (item.status == '1' ? 'success' : 'warning')">{{ item.status_detail }}</span>
                                </td>
                                <td>
                                    <a class="badge bg-primary" target="_blank" :href="item.bukti_pembayaran_url" v-if="item.bukti_pembayaran">Lihat</a>
                                    <span v-else="">Tidak tersedia</span>
                                </td>
                                <td align="center">
                                    <button
                                        class="btn me-1 btn-info btn-sm"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#status"
                                        @click="
                                            state.req = item;
                                            state.req.bukti_pembayaran = '';
                                        "
                                    >
                                        <i class="fa fa-edit"></i> Status
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delete" @click="state.req = item">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-else>
                                <td colspan="12" align="center">data tidak tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </Admin>

    <div class="modal fade" id="status">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="update">
                    <div class="modal-header">
                        <div class="modal-title">Edit Status</div>
                    </div>
                    <div class="modal-body">
                        <Loading v-if="state.is.submit"></Loading>

                        <div class="form-group">
                            <ReqLabel>Status</ReqLabel>
                            <select required class="form-control" v-model="state.req.status">
                                <option value="0" disabled>Menunggu</option>
                                <option value="1">Setujui</option>
                                <option value="2">Tolak</option>
                            </select>
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
