import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";
import Rooms from './rooms';
import Users from './users';
import Tasks from './tasks';

Vue.use(Vuex);

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.querySelector('meta[name="user-token"]').getAttribute('content');

let store = new Vuex.Store({

    modules: {
        Rooms,
        Users,
        Tasks
    },
    state: {

    },
    getters: {

    },
    actions: {

    },

    mutations: {

    }
});

export default store;
