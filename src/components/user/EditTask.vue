<template>
    <el-form :model="addTodoForm" :rules="rules" ref="addTodoForm" label-width="80px">
        <el-form-item label="活动名称" :label-width="formLabelWidth" prop="tname">
            <el-input v-model="addTodoForm.tname" autocomplete="off" style="width: 220px;"></el-input>
        </el-form-item>
        <el-form-item label="是否重要" :label-width="formLabelWidth" prop="timportant">
          <el-radio v-model="addTodoForm.timportant" label="0">不重要</el-radio>
          <el-radio v-model="addTodoForm.timportant" label="1">重要</el-radio>
        </el-form-item>
        <el-form-item label="截止日期" :label-width="formLabelWidth" prop="tdeadline">
        <el-date-picker
            v-model="addTodoForm.tdeadline"  
            align="left"
            type="datetime"
            placeholder="选择日期"
            :picker-options="pickerOptions"
            value-format="yyyy-MM-dd HH:mm:ss">
        </el-date-picker>
        </el-form-item>

        <el-form-item :label-width="formLabelWidth">
            <el-button type="primary" icon="el-icon-upload" @click="updateToDo()">修改ToDo</el-button>
        </el-form-item>

    </el-form>
</template>

<script>
    export default {
        data(){
            return {
                addTodoForm:{             // 弹框表单内容
                    tid: '',
                    tname:'',
                    timportant: '',
                    tdeadline:'',
                    uid: '',
                },
                formLabelWidth: '120px',
                pickerOptions: {
                    disabledDate(time) {
                        return time.getTime() < Date.now();
                    },
                    shortcuts: [{
                        text: '今天',
                        onClick(picker) {
                        picker.$emit('pick', new Date());
                        }
                    }, {
                        text: '昨天',
                        onClick(picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        picker.$emit('pick', date);
                        }
                    }, {
                        text: '一周前',
                        onClick(picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', date);
                        }
                    }]
                },// 设置时间
                rules: {
                    tname: [
                        { required: true, message: '请输入活动名称', trigger: 'blur' },
                        { min: 2, message: '活动至少两个字符', trigger: 'blur' },
                    ],
                    timportant: [
                        { required:true, message: '如果不重要就选个不重要吧~', trigger: 'blur'}
                    ]
                }
            }
        },
        methods:{
            updateToDo() {
                this.addTodoForm.uid = JSON.parse(localStorage.getItem('userinfo')).userid;
                this.axios.post('updatetodo.php', this.addTodoForm).then( response => {
                    let res = response.data;
                    if(res.status === 1){
                        this.addTodoForm.timportant = Boolean(parseInt(this.addTodoForm.timportant));
                        
                        this.$store.commit('clearTodos');

                        this.$router.push('/admin/alltasks');
                    }else if(res.status === 0){
                        localStorage.removeItem('currentUser');
                        localStorage.removeItem('userinfo');
                        this.$router.push("/login")
                        this.$message({
                            message: '登录信息已过期！',
                            type: 'warning'
                        });
                    }else{
                        console.log(res)
                    }
                })
            },
            
        },
        created(){
          this.addTodoForm.tname = this.$route.params.tname;
          this.addTodoForm.tid = this.$route.params.tid;
        },
        watch: {
          $route(newValue, oldValue) {
            this.addTodoForm = {             // 弹框表单内容
                  tname:'',
                  timportant: '',
                  tdeadline:'',
                  uid: '',
              };
            this.getListToDos();
          },
        },
        
    }
</script>

<style scoped>

</style>