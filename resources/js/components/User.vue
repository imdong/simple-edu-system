<style scoped>
.cell-item {
    display: flex;
    align-items: center;
}

.margin-top {
    margin-top: 20px;
}
</style>

<template>
    <el-descriptions :column=1 border>
        <template #extra>
            <el-button type="primary" @click="logout">退出登陆</el-button>
        </template>
        <el-descriptions-item label="Role">{{
                {teacher: "教师", student: "学生"}[userStore.role]
            }}
        </el-descriptions-item>
        <el-descriptions-item label="ID">{{ userStore.user.id }}</el-descriptions-item>
        <el-descriptions-item label="Username">{{ userStore.user.username }}</el-descriptions-item>
        <el-descriptions-item label="Name">{{ userStore.user.name }}</el-descriptions-item>
    </el-descriptions>

</template>

<script>
import {useUserStore} from '../stores/user.js'
import {ElNotification} from 'element-plus'
import {defineComponent} from "vue";

export default defineComponent({
    name: 'User',


    data() {
        return {
            userStore: useUserStore()
        }
    },
    mounted() {
        this.userStore.info()
    },
    methods: {
        logout: function () {
            useUserStore().logout()

            ElNotification({
                title: 'logout Success',
                message: '已退出，再见👋',
                type: 'success',
            })

            this.$router.push({
                name: 'login'
            })
        }
    }
})

</script>

