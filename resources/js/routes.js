import Home from  './components/Home.vue'
import Rooms from "./components/Rooms";
import Room from "./components/Room";
import template from "./components/template";

export default {
    mode: 'history',

    routes: [
        {
            path: '/',
            component: Home,
            name: 'home'
        },
        {
            path: '/rooms',
            component: Rooms,
            name: 'rooms'
        },
        {
            path: '/rooms/:room_id',
            component: Room,
            name: 'room'
        },
        {
            path: '*',
            component: template,
            name: 'not_found'
        }
    ]
}
