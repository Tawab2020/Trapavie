import axios from "axios";

export default {
    initialize(context) {
        return new Promise((resolve, reject) => {
            axios
            .get('initialize')
            .then(({ data }) => {
                context.commit('INITIALIZE', data)
                resolve({})
            })
            .catch(error => {
                reject(error)
            })
        })
    },

    login({ commit }, credentials) {
        return new Promise((resolve, reject) => {
            axios
                .post(`auth/login`, credentials)
                .then(({ data }) => {
                    commit("LOGIN", data);
                    resolve(data);
                })
                .catch((err) => {
                    reject(err);
                });
        });
    },
    logout({ commit }) {
        axios
            .post(`auth/logout`)
            .then((result) => {
                this.$store.commit("LOGOUT");
                alert();
                // Push router to login page
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
