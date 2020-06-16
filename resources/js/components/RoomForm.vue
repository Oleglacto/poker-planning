<template>
    <div>
        <el-dialog @open="prepareForm" class="room_form" :visible.sync="roomFormVisible" :title="name" :before-close="hideModal">
            <el-form ref="form" label-position="top">
                <el-form-item label="Room name" prop="name">
                    <el-input v-model="room.name"></el-input>
                </el-form-item>
                <el-form-item label="Small description" prop="description">
                    <el-input v-model="room.description"></el-input>
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
        name: 'RoomForm',
        components: {

        },
        props: {
            roomFormVisible: {
                type: Boolean,
                default: false
            },
            action: {
                type: String,
                default: 'create'
            },
            roomForUpdate: {
                type: Object,
                default: () => {}
            }
        },
        data() {
            return {
                room: {},
            };
        },
        mounted() {

        },
        methods: {
            ...mapActions([
                'createRoom'
            ]),
            hideModal() {
                this.$emit('closeForm');
                this.room = {}
            },
            prepareForm() {

            },
            onSubmit() {
                this[this.actionFunction](this.room).then(() => {
                    this.hideModal()
                })
            }
        },
        computed: {
            name() {
                return this.action === 'update' ? "Обновление комнаты" : "Созжание комнаты";
            },
            actionFunction() {
                return this.action === 'update' ? "createRoom" : "updateRoom";
            }
        }
    }
</script>

<style>

</style>
