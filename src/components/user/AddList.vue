<template>
    <el-form :model="addListForm" :rules="rules" ref="addListForm" label-width="80px">
        <el-form-item label="清单名称" :label-width="formLabelWidth" prop="lname">
            <el-input v-model="addListForm.lname" autocomplete="off" style="width: 360px;"></el-input>
        </el-form-item>
        <el-form-item label="添加ToDo项" :label-width="formLabelWidth" prop="tid" v-loading='loading'>
            <el-select v-model="addListForm.tid" multiple placeholder="请选择" style="width: 360px;" >
                <el-option
                v-for="task in options"
                :key="task.tid"
                :label="task.tname"
                :value="task.tid" >
                </el-option>
            </el-select>
        </el-form-item>

        <el-form-item :label-width="formLabelWidth">
            <el-button type="primary" icon="el-icon-upload" @click="addList()">创建清单</el-button>
        </el-form-item>

    </el-form>
</template>

<script>
    export default {
        data(){
            return {
                addListForm:{             // 弹框表单内容
                    lname:'',
                    tid: [],
                },
                options: [],
                loading: true,
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
                    lname: [
                        { required: true, message: '请输入清单名称', trigger: 'blur' },
                        { min: 2, message: '名称至少两个字符', trigger: 'blur' },
                    ],
                    tid: [
                        { required:true, message: '如果不重要就选个不重要吧~', trigger: 'blur'},
                        { min: 1, message: '至少添加一个任务', trigger: 'blur'},
                    ]
                },

            }
        },
        created(){
            this.getAllToDos();
        },
        methods:{
            addList() {
                this.addListForm.uid = JSON.parse(localStorage.getItem('userinfo')).userid;
                this.axios.post('addlist.php', this.addListForm).then( response => {
                    let res = response.data;
                    if(res.status === 1){
                        let tempLists = this.$store.getters.getLists;
                        let existed = false;        // 判断是否存在
                        tempLists.some((list) =>{
                            if(list.lname == this.addListForm.lname){    // 当前列表在现有缓冲中已存在
                                existed = true;
                                for(var i = 0; i < this.addListForm.length; i++){
                                    list.tid.indexOf(this.addListForm[i]) === -1 ? list.tid.push(this.addListForm[i]) : 0 ;
                                }
                            }
                        })
                        if(!existed){
                            this.$store.commit('addLists', this.addListForm);
                        }
                        this.$router.push('/admin/');
                    }else if(res.status === 0){                 // 服务器端用户登录状态已失效
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
            getAllToDos(){
                let isInstore = this.$store.getters.getAlltodos.length;
                console.log(isInstore)
                if(isInstore != 0){
                this.options = this.$store.getters.getAlltodos;
                this.loading = false;
                console.log('直接从仓库取得');
                }else{
                this.axios.get('alltodos.php').then( response => {
                    this.loading = true;
                    let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
                    console.log(res[0].status)
                    if(res[0].status == 1){
                        this.$store.commit('setAlltodos', res);
                        this.options = this.$store.getters.getAlltodos;
                        this.loading = false;
                        console.log('通过api取得');
                    }else if(res[0].status == 2){
                        this.options = this.$store.getters.getAlltodos;
                        this.loading = false;
                    }else{
                        localStorage.removeItem('currentUser');
                        localStorage.removeItem('userinfo');
                        this.$router.push("/login")
                        this.$message({
                        message: '登录信息已过期！',
                        type: 'warning'
                        });
                    }
                })
                }
            },
        },
        inject:['reload'],
        
    }
</script>

<style scoped>

</style>