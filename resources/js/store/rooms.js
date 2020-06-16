import axios from 'axios';
import Cache from "./cache";


const cacheHashFunction = (url, data) => {
    let result = url;
    for (const item in data) {
        result += `${item}=${data[item]}`
    }
    return result
};

export default {
    state: {
        pref: '/api',
        list: {
            data: []
        },
        GET: {
            rooms: '/rooms',
            room: '/rooms/:room_id',
            users: '/rooms/:room_id/users'
        },
        POST: {
            room: '/rooms',
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
        addRooms(state, data) {
            state.list = data.data;
        },
        addRoom(state, data) {
            const existRoom = state.list.data.find(el => el.room_id === data.room_id);
            if (!existRoom) {
                state.list.data.push(data);
            }

        }
    },
    actions: {
        createRoom(context, data) {
            return new Promise((resolve, reject) => {
                axios.post(context.state.pref + context.state.POST.room, data)
                    .then((response => {
                        context.dispatch('addRoom', response.data);
                        resolve(response);
                    }))
                    .catch(error => {
                        reject(error);
                    })
            });
        },

        "SOCKET_userJoinedToRoom"(context, data) {
            context.dispatch('addClientToRoom', data);
        },
        "SOCKET_userLeavedFromRoom"(context, data) {
            context.dispatch('removeClientFromRoom', data);
        },

        "SOCKET_userDisconnected"(context, data) {
            context.dispatch('removeClientFromAllRooms', data.user_id);
        },



        addRoom(context, data) {
            context.commit('addRoom', data);
        },

        getUsers(context, roomId) {
            return axios.get(context.state.pref + context.state.GET.users.replace(':room_id', roomId))
        },
        getRooms(context, data) {
            const url = `${context.state.GET.rooms}/`;

            const cache = Cache.getItem(cacheHashFunction(url, {id: data}));

            if (cache) {
                return new Promise((resolve, reject) => {
                    resolve(cache.data);
                });
            }

            return new Promise((resolve, reject) => {
                axios.get(context.state.pref + context.state.GET.rooms, data)
                    .then((response => {
                        Cache.setItem(cacheHashFunction(url, {id: data}), response);
                        context.commit('addRooms', response.data);
                        resolve(response);
                    }))
                    .catch(error => {
                        reject(error);
                    })
            });
        },

        removeClientFromAllRooms(context, userId) {
            _.each(context.state.list.data, function (el) {
                if (!el.clients) {
                    return;
                }
                let client = el.clients.find(el => el === userId);
                if (client) {
                    el.clients = el.clients.filter(el => el !== userId);
                    el.clients_count--;
                }
            })
        },

        addClientToRoom(context, data) {
            let existsRoom = context.state.list.data.find(el => el.room_id === data.room_id);

            if (!existsRoom) {
                return;
            }

            existsRoom.clients.push(data.user_id);
            existsRoom.clients = existsRoom.clients.filter((v, i, a) => a.indexOf(v) === i);
            existsRoom.clients_count = existsRoom.clients.length;
        },

        removeClientFromRoom(context, data) {
            let existsRoom = context.state.list.data.find(el => el.room_id === data.room_id);

            if (!existsRoom) {
                return;
            }

            existsRoom.clients = existsRoom.clients.filter(el => el !== data.user_id);
            existsRoom.clients_count = existsRoom.clients.length;
        },

        getRoom(context, roomId) {
            let existsRoom = context.state.list.data.find(el => el.room_id === roomId);

            if (existsRoom) {
                return Promise.resolve(existsRoom);
            }

            return new Promise((resolve, reject) => {
                axios.get(context.state.pref + context.state.GET.room.replace(':room_id', roomId))
                    .then((response => {
                        context.dispatch('addRoom', response.data);
                        resolve(response.data);
                    }))
                    .catch(error => {
                        reject(error);
                    })
            });
        }
    }
}
