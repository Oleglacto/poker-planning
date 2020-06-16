import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';
import store from './store';
import ElementUI from 'element-ui';
import lang from 'element-ui/lib/locale/lang/ru-RU';
import locale from 'element-ui/lib/locale';
import 'element-ui/lib/theme-chalk/index.css';
import * as lodash from "lodash";
import VueSocketIO from 'vue-socket.io'
import SocketIO from "socket.io-client"
import MyAsideMenu from "./components/MyAsideMenu";
import MyHeader from "./components/MyHeader";

const options = {
    path: '/ws',
    transports: ['websocket'],
};

Vue.use(new VueSocketIO({
    debug: true,
    connection: SocketIO('ws://poker-planning.app.ru', options),
    vuex: {
        store,
        actionPrefix: 'SOCKET_',
        mutationPrefix: 'SOCKET_'
    },
}));


Vue.use(ElementUI);
Vue.use(VueRouter);

locale.use(lang);
window._ = lodash;
const app = new Vue({
    el: '#app',
    store,
    components: {MyAsideMenu, MyHeader},
    router: new VueRouter(routes)
});
