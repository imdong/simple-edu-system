<style scoped>

</style>

<template>
    <el-table :data="tableList" style="width: 100%" v-loading="isLoading">
        <el-table-column label="ID" prop="id" width="50"/>
        <el-table-column label="课程名" prop="name" min-width="150"/>
        <el-table-column label="年月" prop="date" width="90"/>
        <el-table-column label="费用" prop="cost" width="70"/>
        <el-table-column label="创建时间" prop="created_at" :formatter="formatterDate" width="170"/>
        <el-table-column v-show="userStore.role === 'teacher'" label="学生" prop="student.name"/>
        <el-table-column v-show="userStore.role === 'student'" label="老师" prop="teacher.name"/>
        <el-table-column align="right" min-width="140">
            <template #header>
                <el-button v-show="userStore.role === 'teacher'" size="small"
                           @click="handleAddShow">
                    新建课程
                </el-button>
            </template>
            <template #default="scope">
                <el-button v-show="userStore.role === 'teacher'" size="small"
                           @click="handleEditShow(scope.$index, scope.row)">
                    编辑
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

    <el-dialog
        v-model="dialogAdd"
        title="新建课程"
        width="500"
    >
        <el-form :model="formAddCourse" label-width="auto" style="max-width: 600px">
            <el-form-item label="课程名">
                <el-input v-model="formAddCourse.name"/>
            </el-form-item>
            <el-form-item label="年月">
                <el-date-picker
                    v-model="formAddCourse.date"
                    type="month"
                    value-format="YYYY-MM"
                    placeholder="选择年月"
                />
            </el-form-item>
            <el-form-item label="费用">
                <el-input-number v-model="formAddCourse.cost" :precision="2" :step="1" :min="0"/>
            </el-form-item>
            <el-form-item label="学生">
                <el-select
                    v-model="formAddCourse.student_id"
                    filterable
                    remote
                    reserve-keyword
                    placeholder="搜索学生"
                    :remote-method="handleStudentSearch"
                    :loading="studentLoading"
                    style="width: 240px"
                >
                    <el-option
                        v-for="item in studentList"
                        :key="item.id"
                        :label="`${item.name} (${item.username})`"
                        :value="item.id"
                    />
                </el-select>
            </el-form-item>

        </el-form>
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogAdd = false">Cancel</el-button>
                <el-button type="primary" @click="handleCourseSubmit"
                           v-loading="dialogAddSubmitLoading">
                    提交
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import {useCourseStore} from "../stores/course.js";
import {defineComponent} from "vue";
import {useUserStore} from "../stores/user.js";
import {useStudentStore} from "../stores/student.js";
import {dayjs, ElNotification} from "element-plus";
import {useInvoiceStore} from "../stores/invoice.js";

export default defineComponent({
    name: 'Course',
    data() {
        return {
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
            this.courseStore.list({}, this.currentPage, this.pageSize).then(response => {
                this.tableList = response.data.data
                this.listTotal = response.data.total
                this.isLoading = false
            })
        },
        formatterDate(row, column, value, index) {
            return dayjs(value * 1000).format('YYYY-MM-DD HH:mm:ss')
        },
        handleStudentSearch(name) {
            this.studentLoading = true;
            this.studentStore.list({name}, 1, 20).then(response => {
                this.studentList = response.data.data;
                this.studentLoading = false;
            })
        },
        handleCourseSubmit() {
            if (this.formEditCourseId === 0) {
                this.handleAdd()
            } else {
                this.handleEdit()
            }
        },
        handleAddShow() {
            this.formAddCourse = {
                name: '',
                date: '',
                cost: null,
                student_id: null,
            }
            this.formEditCourseId = 0;
            this.dialogAdd = true
        },
        handleAdd() {
            this.dialogAddSubmitLoading = true;
            this.courseStore.create(this.formAddCourse).then(response => {
                if (response.code === 200) {
                    ElNotification({
                        title: 'Add Success',
                        message: '添加成功',
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
                        title: 'Add Failed',
                        message: response.message,
                        type: 'error',
                    })
                }

                this.dialogAddSubmitLoading = false;
            })
        },
        handleEditShow(index, row) {
            this.formEditCourseId = row.id;
            this.dialogAdd = true
            this.formAddCourse = {
                name: row.name,
                date: row.date,
                cost: parseFloat(row.cost),
                student_id: row.student_id,
            }
            this.studentList = [row.student]
        },
        handleEdit() {
            this.dialogAddSubmitLoading = true;
            this.courseStore.update(this.formEditCourseId, this.formAddCourse).then(response => {
                if (response.code === 200) {
                    ElNotification({
                        title: 'Edit Success',
                        message: '修改成功',
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
                        title: 'Edit Failed',
                        message: response.message,
                        type: 'error',
                    })
                }

                this.dialogAddSubmitLoading = false;
            })
        },
        handleDelete(index, row) {
            this.isLoading = true;
            this.courseStore.delete(row.id).then(response => {
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
        handleInvoice(index, row) {
            this.dialogAddSubmitLoading = true;
            this.invoiceStore.create({
                course_id: row.id
            }).then(response => {
                if (response.code === 200) {
                    ElNotification({
                        title: 'Invoice Create Success',
                        message: '账单创建成功',
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
                        title: 'Invoice Create Failed',
                        message: response.message,
                        type: 'error',
                    })
                }

                this.dialogAddSubmitLoading = false;
            }).catch(e => {
                console.log(e);
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
