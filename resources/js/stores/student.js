import {defineStore} from 'pinia'
import {useUserStore} from "./user.js";

export const useStudentStore = defineStore('Student', {
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
        list(search, page, limit) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.get(`/api/${userStore.role}/students`, {
                    params: Object.assign(search, {page, limit}),
                    headers: {
                        Authorization: `Bearer ${userStore.token}`
                    }
                }).then(response => {
                    resolve(response.data)
                }).catch(error => {
                    reject(error)
                })
            })
        },
    }
})
