<template>
    <div>
        <h1>Room: {{ room.name }} </h1>

        <el-container :gutter="20">
            <el-aside class="m-r-20">
                <el-card class="box-card m-b-20" shadow="never">
                    <div slot="header" class="clearfix">
                        <span>Users Online:</span>
                    </div>
                    <el-card class="box-card m-b-5" shadow="hover" v-for="user in usersList" :key="user.user_id">
                        <div class="content__center">
                            {{ user.name }} <span v-if="voteFinished">{{ user.result }}</span> <i class="el-icon-circle-check" :class="{ voted: user.isVoted }" v-if="!voteFinished"></i>
                        </div>
                    </el-card>

                </el-card>
            </el-aside>

            <el-container>
                <el-card class="box-card m-b-20" shadow="never">
                    <div slot="header" class="content__center">
                        <span>Task list:</span>
                        <div class="buttons">
                            <el-button @click="showTaskForm" size="mini">Create task</el-button>
                        </div>
                    </div>
                    <el-table
                        border
                        :data="taskList"
                        stripe
                        style="width: 100%">
                        <el-table-column
                            prop="name"
                            label="Name"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="value"
                            label="Estimation"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            fixed="right"
                            label="Operations"
                            width="120">
                            <template slot-scope="scope">
                                <el-button
                                    type="primary"
                                    plain
                                    @click.native.prevent="voteAction(scope.row.task_id)"
                                    v-if="voteFinished"
                                    size="small">
                                    Start vote
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </el-container>
        </el-container>

        <el-dialog top="35vh" :title="'Задача: ' + activeTask.name" :visible.sync="voteDialogVisible" :before-close="() => {}">
            <p>
                {{ activeTask.description }}
            </p>
            <el-form :model="form" :rules="rules" ref="voteForm">
                <el-form-item label="Оценка" prop="value">
                    <el-select v-model="form.value" placeholder="Choice">
                        <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.value"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="confirmVote('voteForm')">Confirm</el-button>
            </span>
        </el-dialog>

        <task-form
            action="create"
            :task-form-visible="taskFormVisible"
            :room-id="room.room_id"
            @taskCreated="addTaskToList"
            @closeForm="taskFormVisible = false"
        ></task-form>
    </div>
</template>

<script>
    import TaskForm from "./TaskForm";
    import { mapActions, mapState } from 'vuex';
    export default {
        name: 'Room',
        components: {
            TaskForm
        },
        props: {

        },
        data() {
            return {
                room: {
                    name: "",
                    description: "",
                },
                form: {
                    task_id: 0,
                    value: null
                },
                rules: {
                    value: [
                        { required: true, message: 'Please select your value', trigger: 'change' }
                    ]
                },
                options: [
                    { value: 1 },
                    { value: 2 },
                    { value: 3 },
                    { value: 5 },
                    { value: 8 },
                    { value: 13 },
                    { value: 21 },
                ],
                activeTask: {
                    name: '',
                    description: ''
                },
                voteFinished: true,
                user: {},
                usersList: [],
                taskList: [],
                taskFormVisible: false,
                voteDialogVisible: false,
            };
        },
        sockets: {
            userIsVoted(data) {
                let user = this.usersList.find(el => +el.id === data.user_id);
                user.isVoted = true;
                user.value = data.value;
            },
            voteFinished(data) {
                  this.voteFinished = true;
                  data.result.map((userVote => {
                      console.log(userVote);
                      const userExist = this.usersList.find(el => el.id === userVote.user_id);

                      if (!userExist) {
                          return;
                      }

                      userExist.result = userVote.vote;
                  }));
            },
            voteStarted(data) {
                this.voteFinished = false;
                this.voteDialogVisible = true;
                this.form.task_id = data.task_id;
                this.activeTask = this.taskList.find(el => +el.task_id === +data.task_id)
            },

            userJoinedToRoom(data) {
                const userExist = this.usersList.find(el => el.id === data.user_id);

                if (userExist) {
                    return;
                }

                if (+data.room_id !== +this.$route.params.room_id) {
                    return;
                }

                this.$notify({
                    title: 'Joined',
                    message: data.user.name,
                    position: 'bottom-right'
                });

                this.usersList.push(data.user);
            },
            userLeavedFromRoom(data) {
                this.$notify({
                    title: 'Leaved',
                    message: data.user.name,
                    position: 'bottom-right'
                });

                this.usersList = this.usersList.filter(el => +el.id !== +data.user_id)
            },
            userDisconnected(data) {
                this.usersList = this.usersList.filter(el => +el.id !== +data.user_id)
            },
            taskCreated(data) {
                this.$nextTick(() => {
                    const taskExist = this.taskList.find(el => el.task_id === data.task.task_id);

                    if (taskExist) {
                        return;
                    }

                    if (+data.room_id !== +this.$route.params.room_id) {
                        return;
                    }

                    this.addTaskToList(data.task)
                })
            }
        },
        mounted() {
            this.getActiveUser().then(user => {
                this.user = user;
                this.usersList.push(this.user);

                this.$socket.emit('joinToRoom', {
                    user_id: user.id,
                    room_id: this.$route.params.room_id
                });

                this.getUsers(this.$route.params.room_id).then(response => {
                    this.usersList = this.usersList.concat(response.data.data.filter(el => el.id !== this.user.id));
                });
            });

            this.getRoomTasks(this.$route.params.room_id).then(response => {
                this.taskList = this.taskList.concat(response.data.data);
            });

            this.getRoom(this.$route.params.room_id).then((room) => {
                this.room = room;
            }).catch(err => {
                if (err.response.status === 404) {
                    this.$router.push({name: 'not_found'});
                }
            });
        },
        methods: {
            ...mapActions([
                'getRoom',
                'getActiveUser',
                'getUsers',
                'createTask',
                'getRoomTasks'
            ]),

            confirmVote(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.voteDialogVisible = false;
                        this.$socket.emit('userIsVoted', { user_id: this.user.id, room_id: this.room.room_id, form: this.form });
                    } else {
                        return false;
                    }
                });
            },

            voteAction(taskId) {
                this.$socket.emit('voteStarted', { room_id: this.room.room_id, task_id: taskId });
            },

            addTaskToList(data) {
                this.taskList.push(data)
            },
            showTaskForm() {
                this.taskFormVisible = true;
            }


        },
        computed: {
            ...mapState({

            })
        },
        destroyed() {
            this.$socket.emit('leaveFromRoom', { room_id: this.room.room_id, user_id: this.user.id });
        }
    }
</script>

<style lang="scss" scoped>
.voted {
    color: #0E9A00;
}
    .el-icon-circle-check {
        font-size: 30px;
    }
.el-card__header {
    max-height: 60px;
}
</style>
