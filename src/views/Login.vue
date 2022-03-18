<script setup="">
    import { reactive } from "vue";
    import { RouterLink } from "vue-router";
    import Loading from "@/components/Loading.vue";

    const state = reactive({
        req: {
            username: '',
            password: '',
        },
        is: {
            submit: false,
        }
    })

    function login() {
        state.is.submit = true;
        http.post('/api/user/login', state.req).then(res => {
            state.is.submit = false;
            if (res.error) {
                notif(res.message[0], 'warning', true);
            } else {
                notif(res.message, 'success');
                
                localStorage.setItem('xid', res.data.id);
                localStorage.setItem('xrole', res.data.role);

                window.location = window.location.origin + window.location.pathname;
            }
        });
    }
</script>

<template>
    <div class="col-lg-4 col-md-6">
        <div class="card mt-5">
            <form @submit.prevent="login" class="card-body">
                <h4 class="card-title">Login</h4>

                <hr>

                <Loading v-if="state.is.submit"></Loading>

                <div class="form-group">
                    <label for="">Username</label>
                    <input type="text" class="form-control" required="required" v-model="state.req.username" placeholder="Masukkan Username">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" required="required" v-model="state.req.password" placeholder="Masukkan Password">
                </div>

                <p align="center">Belum punya akun? daftar <RouterLink to="/register">disini</RouterLink></p>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>