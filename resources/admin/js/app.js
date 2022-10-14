import {
    createApp
} from "vue";

import store from "./store";

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import VueProgressBar from '@aacassandra/vue3-progressbar'
const options = {
    color: "#14a172",
    failedColor: "red",
    thickness: "3px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    inverse: false,
};

import axios from "axios";
axios.defaults.baseURL = "/api/admin";
axios.interceptors.request.use(function (config) {
    const admin = store.getters.admin;
    if (admin) {
        config.headers.Authorization = `Bearer ${admin.access_token}`;
    }
    return config;
});
axios.interceptors.response.use(null, (error) => {
    if (error.response.status == 401) {
        store.commit("LOGOUT");
        router.push({
            name: "admin.login"
        });
    }

    return Promise.reject(error.response);
});
window.axios = axios;

// Vue Router
import {
    createRouter,
    createWebHistory
} from "vue-router";
import {
    routes
} from "./routes";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const admin = store.getters.admin;

    if (requiresAuth && !admin) {
        next({
            name: "admin.login"
        });
    } else if (to.name == "admin.login" && admin) {
        next({
            name: "admin.dashboard"
        });
    } else {
        next();
    }
});

import HomeApp from "./HomeApp.vue";

const app = createApp({});

app.config.globalProperties.$FilePath = process.env.MIX_APP_ENV == "local" ? '/storage' : process.env.MIX_DO_URL + '/public'

app.component("HomeApp", HomeApp);
app.use(router);
app.use(store);
app.use(VueToast);
app.use(VueProgressBar, options)

app.mount("#app");
