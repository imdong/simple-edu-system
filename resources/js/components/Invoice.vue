<style scoped>

</style>

<template>
    <el-table :data="tableList" style="width: 100%" v-loading="isLoading">
        <el-table-column label="ID" prop="id" width="50"/>
        <el-table-column label="课程名" prop="course.name" min-width="150"/>
        <el-table-column v-show="userStore.role === 'teacher'" label="学生" prop="student.name"/>
        <el-table-column v-show="userStore.role === 'student'" label="老师" prop="teacher.name"/>
        <el-table-column label="金额" prop="amount_raed" width="70"/>
        <el-table-column label="状态" prop="status" :formatter="formatterStatus" width="100"/>
        <el-table-column label="创建时间" prop="created_at" :formatter="formatterDate" width="170"/>
        <el-table-column label="付款时间" prop="paid_at" :formatter="formatterDate" width="170"/>
        <el-table-column align="right" min-width="80">
            <template #default="scope">
                <el-button v-show="userStore.role === 'teacher' && scope.row.status === 1" size="small" type="primary"
                           @click="handleSend(scope.$index, scope.row)">
                    发送
                </el-button>
                <el-button v-show="userStore.role === 'student' && [2,8].indexOf(scope.row.status) >= 0" size="small"
                           type="primary"
                           @click="handlePay(scope.$index, scope.row)">
                    支付
                </el-button>

                <el-popconfirm title="确定要删除么?" @confirm="handleDelete(scope.$index, scope.row)">
                    <template #reference>
                        <el-button v-show="userStore.role === 'teacher'" size="small" type="danger">
                            删除
                        </el-button>
                    </template>
                </el-popconfirm>

            </template>
        </el-table-column>
    </el-table>
    <el-row class="pos-center" style="margin-top: 10px">
        <el-pagination background
                       layout="total, sizes, prev, pager, next, jumper"
                       :total="listTotal"
                       :page-sizes="[10, 20, 50, 100]"
                       :page-size="pageSize"
                       :current-page="currentPage"
                       @size-change="handleSizeChange"
                       @current-change="handleCurrentChange"
        />
    </el-row>
</template>

<script>
import {useCourseStore} from "../stores/course.js";
import {defineComponent} from "vue";
import {useUserStore} from "../stores/user.js";
import {useStudentStore} from "../stores/student.js";
import {dayjs, ElNotification} from "element-plus";
import {useInvoiceStore} from "../stores/invoice.js";
import OmiseCreateToken from "../utils/omise.js";

export default defineComponent({
    name: 'Invoice',
    data() {
        return {
            statusMap: {
                1: '已创建',
                2: '待支付',
                3: '付款中',
                4: '已付款',
                5: '已完成',
                8: '付款失败',
                9: '失败',
                0: '关闭',
            },
            tableList: [],
            listTotal: 0,
            isLoading: false,
            courseStore: useCourseStore(),
            userStore: useUserStore(),
            studentStore: useStudentStore(),
            invoiceStore: useInvoiceStore(),
            currentPage: 1,
            pageSize: 10,
            dialogAdd: false,
            dialogAddSubmitLoading: false,
            formAddCourse: {
                name: '',
                date: '',
                cost: null,
                student_id: null,
            },
            formEditCourseId: 0,
            studentList: [],
            studentLoading: false,
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.isLoading = true;
            this.invoiceStore.list(this.currentPage, this.pageSize).then(response => {
                this.tableList = response.data.data
                this.listTotal = response.data.total
                this.isLoading = false
            })
        },
        formatterStatus(row) {
            return this.statusMap[row.status]
        },
        formatterDate(row, column, value, index){
            if (!value) {
                return '-'
            }
            return dayjs(value * 1000).format('YYYY-MM-DD HH:mm:ss')
        },
        handleDelete(index, row) {
            this.isLoading = true;
            this.invoiceStore.delete(row.id).then(response => {
                if (response.code === 200) {
                    ElNotification({
                        title: 'Delete Success',
                        message: '删除成功',
                        type: 'success',
                    })

                    this.fetchData()
                } else {
                    ElNotification({
                        title: 'Delete Failed',
                        message: response.message,
                        type: 'error',
                    })
                }
                this.isLoading = false
            })
        },
        handleSend(index, row) {
            this.dialogAddSubmitLoading = true;
            this.invoiceStore.update(row.id, {
                action: 'send'
            }).then(response => {
                if (response.code === 200) {
                    ElNotification({
                        title: 'Send Success',
                        message: '账单已经发送成功',
                        type: 'success',
                    })

                    this.formAddCourse = {
                        name: '',
                        date: '',
                        cost: null,
                        student_id: null,
                    }
                    this.dialogAdd = false;
                    this.fetchData()
                } else {
                    ElNotification({
                        title: 'Send Failed',
                        message: response.message,
                        type: 'error',
                    })
                }

                this.dialogAddSubmitLoading = false;
            }).catch(e => {
                console.log(e);
            })
        },
        handlePay(index, row) {
            this.isLoading = true;

            OmiseCreateToken({
                amount: row.amount,
                currency: 'jpy',
                defaultPaymentMethod: 'credit_card',
            }).then(omise_token => {
                // 提交到后台
                this.invoiceStore.update(row.id, {
                    action: 'pay',
                    omise_token,
                }).then(response => {
                    if (response.code === 200) {
                        ElNotification({
                            title: 'Pay Success',
                            message: '账单支付成功',
                            type: 'success',
                        })

                        this.formAddCourse = {
                            name: '',
                            date: '',
                            cost: null,
                            student_id: null,
                        }
                        this.dialogAdd = false;
                        this.fetchData()
                    } else {
                        ElNotification({
                            title: 'Pay Failed',
                            message: response.message,
                            type: 'error',
                        })
                    }

                    this.dialogAddSubmitLoading = false;
                }).catch(e => {
                    console.log(e);
                })

            })






        },
        handleSizeChange(size) {
            this.pageSize = size
            this.fetchData();
        },
        handleCurrentChange(page) {
            this.currentPage = page
            this.fetchData();
        },
    }
})
</script>
