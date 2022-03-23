<script setup="">
    import { reactive, onMounted } from 'vue';
    import Loading from '../components/Loading.vue';

    const state = reactive({
        data: {
            items: [],
        },
        is: {
            loading: true,
        },
    });

    function get() {
        state.is.loading = true;
        http.get('/api/iklan').then((res) => {
            state.data.items = res.data;
            state.is.loading = false;
        });
    }

    onMounted(async () => {
        await get();
    });
</script>

<template>
    <div class="col-lg-10">
        <div class="card mt-5">
            <div class="card-body">
                <h4 class="card-title">Data Iklan</h4>

                <hr />

                <Loading v-if="state.is.loading"></Loading>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Durasi</th>
                                <th>Tarif</th>
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
                                </tr>
                                <tr>
                                    <td>Keterangan:</td>
                                    <td colspan="3">{{ item.keterangan }}</td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="4" align="center">data tidak tersedia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
