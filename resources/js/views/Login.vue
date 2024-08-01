<style scoped>

.login-form {
    width: 500px;
    max-width: 100%;
    margin: 0 auto;
}

.title {
    font-size: 26px;
}

</style>

<template>
    <el-container class="main-container">
        <el-header>
            <h3 class="title">登陆</h3>
        </el-header>
        <el-main>
            <div>
                <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on"
                         label-position="left">

                    <el-form-item prop="type" label="角色">
                        <el-select ref="type" v-model="loginForm.type" placeholder="请选择" tabindex="1">
                            <el-option label="教师" value="teacher"/>
                            <el-option label="学生" value="student"/>
                        </el-select>
                    </el-form-item>

                    <el-form-item prop="username" label="帐号">
                        <el-input ref="username" v-model="loginForm.username" placeholder="Username" name="username"
                                  type="text"
                                  tabindex="2" auto-complete="on"/>
                    </el-form-item>

                    <el-form-item prop="password" label="密码">
                        <el-input
                            ref="password"
                            v-model="loginForm.password"
                            type="password"
                            placeholder="Password"
                            name="password"
                            tabindex="3"
                            auto-complete="on"
                            @keyup.enter.native="handleLogin"
                            show-password
                        />
                    </el-form-item>

                    <el-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;"
                               @click.native.prevent="handleLogin">Login
                    </el-button>

                    <el-form-item label="测试帐号">
                        <el-button @click="setUser('teacher')">教师</el-button>
                        <el-button @click="setUser('student')">学生</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-main>
    </el-container>


</template>

<script>
import {useUserStore} from '../stores/user.js'
import {ElNotification} from 'element-plus'

export default {
    name: 'Login',
    data() {
        return {
            loginForm: {
                type: 'teacher',
                username: 'teacher',
                password: 'teacher',
            },
            loginRules: {
                type: [{required: true}],
                username: [{required: true}],
                password: [{required: true}]
            },
            loading: false,
            userStore: useUserStore(),
        }
    },
    watch: {
        $route: {
            handler: function (route) {
                this.redirect = route.query && route.query.redirect
            },
            immediate: true
        }
    },
    methods: {
        setUser(role) {
            this.loginForm.type = this.loginForm.username = this.loginForm.password = role;
        },
        handleLogin() {
            this.$refs.loginForm.validate(valid => {
                if (valid) {
                    this.loading = true
                    this.userStore.login(this.loginForm)
                        .then(response => {
                            if (response.code === 200) {
                                ElNotification({
                                    title: 'Login Success',
                                    message: '登陆成功，欢迎回家',
                                    type: 'success',
                                })

                                // 获取用户信息
                                this.userStore.info()

                                this.$router.push({path: '/'})
                            } else {
                                ElNotification({
                                    title: 'Login Failed',
                                    message: response.message,
                                    type: 'error',
                                })
                            }

                            this.loading = false
                        })
                } else {
                    console.log('error submit!!')
                    return false
                }
            })
        }
    }
}
</script>
