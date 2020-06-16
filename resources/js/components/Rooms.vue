<template>
    <el-card shadow="never">
        <div slot="header" class="card__header">
            <span>Rooms</span>
            <div class="buttons">
                <el-button @click="showCreateForm">Create room</el-button>
            </div>
        </div>
        <el-table
            :data="rooms"
            :border="true"
            style="width: 100%">
            <el-table-column
                prop="name"
                label="Name"
                width="300">
            </el-table-column>
            <el-table-column
                prop="description"
                label="Description">
            </el-table-column>
            <el-table-column
                prop="clients_count"
                label="Users">
            </el-table-column>
            <el-table-column label="Buttons" width="160">
                <template slot-scope="scope">
                    <el-button size="mini" @click="joinRoom(scope.row)">Join</el-button>
                </template>
            </el-table-column>
        </el-table>
        <room-form
            :room-form-visible="roomFormVisible"
            :action="roomFormAction"
            @closeForm="closeForm"
        ></room-form>
    </el-card>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    import RoomForm from "./RoomForm";

    export default {
        name: 'Rooms',
        components: {
            RoomForm
        },
        data() {
            return {
                roomFormVisible: false,
                roomFormAction: "",
            };
        },
        mounted() {
            this.getRooms();
        },
        sockets: {
            roomCreated(data) {
                this.addRoom(data);
            },
        },
        methods: {
            ...mapActions([
                'getRooms',
                'addRoom',
                'removeClientFromRoom',
                'removeClientFromAllRooms'
            ]),
            showCreateForm() {
                this.roomFormVisible = true;
                this.roomFormAction = "update"
            },
            showEditForm() {

            },
            closeForm() {
                this.roomFormVisible = false;
                this.roomFormAction = ""
            },
            joinRoom(room) {
                this.$router.push({name: 'room', params: {
                    room_id: room.room_id
                }});
            },
        },
        computed: {
            ...mapState({
                rooms: state => state.Rooms.list.data || [],
            })
        }
    }
</script>

<style>

</style>
