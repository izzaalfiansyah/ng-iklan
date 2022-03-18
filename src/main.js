import 'sweetalert2/dist/sweetalert2.css';
import './main.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import mdiVue from 'mdi-vue/v3';
import * as mdijs from '@mdi/js';
import Swal from "sweetalert2";

const apiHeaders = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
}

window.http = {
    get: (url, params) => {
        const queryString = '?' + (new URLSearchParams(params)).toString();
        return fetch(window.apiUrl + url + queryString, {
            headers: apiHeaders
        }).then(res => res.json());
    },
    post: (url, params) => {
        return fetch(window.apiUrl + url, {
            method: 'POST',
            headers: apiHeaders,
            body: JSON.stringify(params),
        }).then(res => res.json());
    },
    put: (url, params) => {
        return fetch(window.apiUrl + url, {
            method: 'PUT',
            headers: apiHeaders,
            body: JSON.stringify(params),
        }).then(res => res.json());
    },
    del: (url) => {
        return fetch(window.apiUrl + url, {
            method: 'DELETE',
        }).then(res => res.json());
    },
}

window.notif = (message, type, mixin = false) => {
    if (mixin) {
        const Mixin = Swal.mixin({
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
            showCloseButton: true,
        });

        return Mixin.fire({
            title: message,
            icon: type,
        });
    } else {
        return Swal.fire({
            text: message,
            icon: type,
            timer: 3000,
        });
    }
}

window.toBase64 = (file, callback) => {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        callback(reader.result);
    }
}

window.user = {
    xid: localStorage.getItem('xid'),
    xrole: localStorage.getItem('xrole'),
}

const app = createApp(App);

app.use(router);

app.use(mdiVue, {
    icons: mdijs,
});

app.mount('#app');
