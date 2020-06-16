import axios from 'axios';

export default {
    state: {
        pref: '/api',
        GET: {
            tasks: '/rooms/:room_id/tasks'
        },
        POST: {
            task: '/tasks'
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

    },
    actions: {
        createTask(context, data) {
            return axios.post(context.state.pref + context.state.POST.task, data)
        },
        getRoomTasks(context, roomId) {
            return axios.get(context.state.pref + context.state.GET.tasks.replace(':room_id', roomId), )
        },
    }
}
