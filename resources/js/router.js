import {createWebHashHistory, createRouter} from 'vue-router'
import {useUserStore} from './stores/user.js'

import HomeView from './views/Home.vue'
import LoginView from './views/Login.vue'

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {path: '/', component: HomeView},
        {path: '/login', component: LoginView, name: 'login'},
    ],
})

router.beforeEach(async (to, from, next) => {

    // 未登陆就跳到登陆
    if (!useUserStore().role && to.name !== 'login') {
        next({
            name: "login",
            query: {redirect: to.path} //登录后再跳回此页面时要做的配置
        });

        return
    }

    next()
})

export default router
