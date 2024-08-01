import {defineStore} from 'pinia'
import {useUserStore} from "./user.js";


export const useInvoiceStore = defineStore('Invoice', {
    state: () => ({}),
    getters: {},
    actions: {
        list(page, limit) {
            const userStore = useUserStore()

            return new Promise((resolve, reject) => {
                axios.get(`/api/${userStore.role}/invoices`, {
                    params: {page, limit},
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
                axios.post(`/api/${userStore.role}/invoices`, data, {
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
                axios.put(`/api/${userStore.role}/invoices/${id}`, data, {
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
                axios.delete(`/api/${userStore.role}/invoices/${id}`, {
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
