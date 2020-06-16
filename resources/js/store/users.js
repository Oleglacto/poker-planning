import axios from 'axios';

export default {
    state: {
        pref: '/api',
        activeUser: null,
        GET: {
            whoAmI: '/user'
        },
        POST: {

        },
        DELETE: {

        },
        PATCH: {

        },
        PUT: {

        },
    },
    getters: {

    },
    mutations: {
        addActiveUser(state, data) {
            state.activeUser = data.data;
        }
    },
    actions: {
        logout() {
            return axios.post('/logout');
        },

        getActiveUser(context) {
            if (context.state.activeUser === null) {
                return new Promise((resolve, reject) => {
                    axios.get(context.state.pref + context.state.GET.whoAmI)
                        .then((response => {
                            context.commit('addActiveUser', response.data);
                            resolve(response.data.data);
                        }))
                        .catch(error => {
                            reject(error);
                        })
                });
            }

            return Promise.resolve(context.state.activeUser);
        }
    }
}
