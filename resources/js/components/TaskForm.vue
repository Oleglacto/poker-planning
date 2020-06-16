<template>
    <div>
        <el-dialog @open="prepareForm" class="room_form" :visible.sync="taskFormVisible" :title="name" :before-close="hideModal">
            <el-form ref="form" label-position="top">
                <el-form-item label="Task name" prop="name">
                    <el-input v-model="task.name"></el-input>
                </el-form-item>
                <el-form-item label="Small description" prop="description">
                    <el-input
                        v-model="task.description"
                        type="textarea"
                        :rows="5">
                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">Create</el-button>
                    <el-button @click="hideModal">Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        name: 'TaskForm',
        components: {

        },
        props: {
            taskFormVisible: {
                type: Boolean,
                default: false
            },
            action: {
                type: String,
                default: 'create'
            },
            taskForUpdate: {
                type: Object,
                default: () => {}
            },
            roomId: {
                type: Number
            }
        },
        data() {
            return {
                task: {},
            };
        },
        mounted() {

        },
        methods: {
            ...mapActions([
                'createTask'
            ]),
            hideModal() {
                this.$emit('closeForm');
                this.task = {}
            },
            prepareForm() {

            },

            onSubmit() {
                this.createTask({...this.task, room_id: this.roomId}).then(response => {
                    this.hideModal();
                })
            }
        },
        computed: {
            name() {
                return this.action === 'update' ? "Обновление комнаты" : "Созжание комнаты";
            },
            actionFunction() {
                return this.action === 'update' ? "createTask" : "updateTask";
            }
        }
    }
</script>

<style>

</style>
