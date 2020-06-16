<template>
    <div>
        <span class="project_name">Poker planning</span>

        <el-aside width="300px" style="background-color: rgb(238, 241, 246)">
            <el-menu style="height: calc(100vh - 60px)" :router="true" :default-active="$route.path">
                <el-menu-item index="/">
                    <i class="el-icon-s-home"></i>
                    <span>Dashboard</span>
                </el-menu-item>
                <el-menu-item index="/rooms" :route="roomRoute">
                    <i class="el-icon-menu"></i>
                    <span>Rooms</span>
                </el-menu-item>
            </el-menu>
        </el-aside>
    </div>

</template>

<script>
    export default {
        name: 'MyAsideMenu',
        components: {

        },
        data() {
            return {
                roomRoute: { name: 'rooms' }
            };
        },
        mounted() {

        },
        methods: {

        }
    }
</script>

<style lang="scss">
    .project_name {
        height: 60px;
        max-width: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
    }
</style>
