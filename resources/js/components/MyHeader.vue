<template>
    <el-header class="header">
        <div class="header__menu">
            <span class="m-r-15 name">{{ activeUser.name }}</span>
            <el-button size="mini" @click="onLogout">Logout</el-button>
        </div>
    </el-header>
</template>

<script>
    import { mapActions, mapState } from 'vuex';
    export default {
        name: 'MyHeader',
        components: {

        },
        data() {
            return {
                activeUser: {
                    name: ''
                },
            };
        },
        mounted() {
            this.getActiveUser().then((user) => {
                this.activeUser = user;
            })
        },
        methods: {
            ...mapActions([
                'logout',
                'getActiveUser'
            ]),

            onLogout() {
                this.logout().then(() => {
                    window.location ='/'
                })
            }
        }
    }
</script>

<style lang="scss">
    .header {
        display: flex;
        justify-content: flex-end;
    }
    .header__menu {
        display: flex;
        align-items: center;
        .name {
            font-size: 20px;
        }
    }
</style>
