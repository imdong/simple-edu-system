<style scoped>

</style>

<template>
    <el-container class="main-container">
        <el-header>
            <h1 class="title">{{ title }}</h1>
        </el-header>
        <el-main>
            <el-tabs type="border-card" v-model="activeName">
                <el-tab-pane label="课程列表" name="course">
                    <CourseView ref="course"/>
                </el-tab-pane>
                <el-tab-pane label="账单列表" name="invoice">
                    <InvoiceView ref="invoice"/>
                </el-tab-pane>
                <el-tab-pane label="用户" name="user">
                    <UserView ref="user"/>
                </el-tab-pane>
            </el-tabs>
        </el-main>
    </el-container>
</template>

<script>
import CourseView from '../components/Course.vue'
import InvoiceView from '../components/Invoice.vue'
import UserView from '../components/User.vue'

export default {
    name: 'Home',
    expose:{},
    components: {
        CourseView,
        InvoiceView,
        UserView,
    },
    data() {
        return {
            title: import.meta.env.VITE_APP_NAME,
            activeName: 'course',
        }
    }
}
</script>
