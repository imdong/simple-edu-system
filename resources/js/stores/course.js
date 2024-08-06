import {defineStore} from 'pinia'
import {useUserStore} from "./user.js";

export const useCourseStore = defineStore('Course', {
    state: () => ({}),
    getters: {},
    actions: {
        list(search, page, limit) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.get(`/api/${userStore.role}/courses`, {
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
        create(data) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.post(`/api/${userStore.role}/courses`, data, {
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
        update(id, data) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.put(`/api/${userStore.role}/courses/${id}`, data, {
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
        delete(id) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.delete(`/api/${userStore.role}/courses/${id}`, {
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
