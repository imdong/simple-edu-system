import {defineStore} from 'pinia'

export const useUserStore = defineStore('User', {
    state: () => ({
        role: null,
        user: {},
        token: null,
    }),
    persist: {
        enabled: true,
    },
    getters: {},
    actions: {
        login(loginFrom) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', loginFrom).then(response => {
                    this.role = response.data.data.role;
                    this.token = response.data.data.token;

                    resolve(response.data)
                }).catch(error => {
                    reject(error)
                })
            })
        },
        logout(){
            this.role = null
            this.token = null
            this.user = {}
        },
        /**
         * 获取用户资料
         */
        info(){
            return new Promise((resolve, reject) => {
                axios.get(`/api/${this.role}/user`, {
                    headers: {
                        Authorization: `Bearer ${this.token}`
                    }
                }).then(response => {
                    const {data} = response
                    this.user = data.data.user;

                    resolve(response.data)
                }).catch(error => {
                    reject(error)
                })
            })
        }
    }
})
