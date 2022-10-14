import {
    createApp
} from "vue";
import i18n from "./locales/i18n";

import store from "./store";

// Toast and Progress Bar
import VueToast from "vue-toast-notification";
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

// Axios
import axios from "axios";

axios.defaults.baseURL = "/api";
axios.interceptors.request.use(function (config) {
    const user = store.getters.user;
    if (user) {
        config.headers.Authorization = `Bearer ${user.access_token}`;
    }
    return config;
});

axios.interceptors.response.use(null, (error) => {
    if (error.response.status == 401) {
        store.commit("LOGOUT");
        router.push({
            name: "login"
        });
    }
    if (error.response.status == 403) {
        router.push({
            name: "home"
        })
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
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return {
            top: 0
        }
    },

});

router.beforeEach((to, from, next) => {
    document.getElementsByClassName('modal-backdrop')[0] ? document.getElementsByClassName('modal-backdrop')[0].remove() : '';
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const user = store.getters.user;

    if (requiresAuth && !user) {
        next({
            name: "login"
        });
    } else if (to.name == "login" && user) {
        next({
            name: "home"
        });
    } else {
        next();
    }
});

// Google Maps
import VueGoogleMaps from '@fawmi/vue-google-maps'

// Vue Geolocation
import Vue3Geolocation from 'vue3-geolocation';

import HomeApp from "./HomeApp.vue";

import {
    Storage
} from "./../../../helpers/storage";
const activeLocale = Storage.get('activeLocale') || "en"

//Custom helper functions
import CustomFunctions from "../../../helpers/CustomFunctions";

// Luxon (DateTime)
import {
    Settings,
    DateTime
} from "luxon"


const app = createApp({
    beforeCreate() {
        this.$i18n.locale = activeLocale;
        // set Luxon DateTime locale
        this.$DateTimeSettings.defaultLocale = activeLocale

        // Get translations of language other than english
        if (activeLocale != "en") {
            axios.get(`loadTranslations/${activeLocale}`).then(({
                data
            }) => {
                this.$i18n.setLocaleMessage(activeLocale, data);
            });
        }
        // Get English Translation anyways, used for fallback locale
        axios.get(`loadTranslations/en`).then(({
            data
        }) => {
            this.$i18n.setLocaleMessage('en', data);
        });
    }
});

app.config.globalProperties.$DateTime = DateTime
app.config.globalProperties.$DateTimeSettings = Settings

app.config.globalProperties.$CF = CustomFunctions

app.config.globalProperties.$FilePath = process.env.MIX_APP_ENV == "local" ? '/storage' : process.env.MIX_DO_URL + '/public'


app.component("HomeApp", HomeApp);
app.use(VueProgressBar, options);
app.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyA1rWHl-0ttgQhYCtkwaFfzmWIBooL1gJM",
        libraries: "places"
    }
});

// V-Calendar
import VCalendar from 'v-calendar';
app.use(VCalendar, {
    screens: {
        desktop: '1200px',
    },
})

app.use(Vue3Geolocation);
app.use(router);
app.use(store);
app.use(VueToast);
app.use(i18n)
app.mount("#app");
