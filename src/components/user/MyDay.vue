<template>
  <el-table
    :data="tableData.filter(data => !search || data.tname.toLowerCase().includes(search.toLowerCase()))"
    style="width: 100%"
    v-loading="loading">
    

    <el-table-column
      label="状态"
      width="60">
      <template slot-scope="scope">
        <el-switch
          v-model="scope.row.tdone"
          @change="tdoneChanged(scope.row.tid)"
          active-color="#13ce66"
          inactive-color="#ff4949">
        </el-switch>
      </template>
    </el-table-column>


    <el-table-column
      label="任务名称"
      prop="tname"
      width="180">
      <template slot-scope="scope" >
        <div slot="reference" class="name-wrapper">
            <el-tag size="medium" >{{ scope.row.tname }}</el-tag>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      label="截止日期"
      width="180">
      <template slot-scope="scope">
        <i class="el-icon-time"></i>
        <span style="margin-left: 10px">{{ scope.row.tdeadline | dateFormat }}</span>
      </template>
    </el-table-column>
    
    <!-- <el-table-column
      label=""
      width="180">
      <template slot-scope="scope">
        <el-popover trigger="hover" placement="top">
          <p>姓名: {{ scope.row.name }}</p>
          <p>住址: {{ scope.row.address }}</p>
          <div slot="reference" class="name-wrapper">
            <el-tag size="medium">{{ scope.row.name }}</el-tag>
          </div>
        </el-popover>
      </template>
    </el-table-column> -->

    <el-table-column
      label="星标"
      width="180">
      <template slot-scope="scope">
        <i :class="scope.row.timportant == true ? 'el-icon-star-on' : 'el-icon-star-off'" 
        @click="changeImportant(scope.row.tid)" style="font-size: 23px;"></i>
      </template>
    </el-table-column>

    <el-table-column label="操作">
      <template slot="header" slot-scope="scope">
        <el-input
          v-model="search"
          size="small"
          style="padding-left:8px;width:220px;"
          prefix-icon="el-icon-search"
          placeholder="输入关键字搜索" />
        
      </template>
      <template slot-scope="scope">
        <el-button
          size="mini"
          @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
        <el-button
          size="mini"
          type="danger"
          @click="handleDelete(scope.$index, scope.row)">删除</el-button>
      </template>

    </el-table-column>

  </el-table>
  
  
</template>

<script>
  export default {
    data() {
      return {
        tableData: [],
        search: '',
        loading: true,
      }
    },
    created(){
      this.getMyToDos();
    },
    methods: {
      handleEdit(index, row) {
        console.log(index, row);
        this.$router.push('/admin/edittask/'+row.tid+'/'+row.tname)
      },
      handleDelete(index, row) {
        console.log(index, row);
        this.axios.post('taskStatusChange.php', {"tid": row.tid}).then( response => {
          let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
          console.log(res);
          this.tableData.splice(index, 1);
          if(res.status == 1){
            this.$message({
              message: '日程删除成功！',
              type: 'success'
            });
            
          }else{
            this.$message({
              message: '删除失败！',
              type: 'warning'
            });
            this.tableData[pos].tdone = !tdone;
          }
        })
        this.$store.commit('clearTodos');
        this.reload();
      },

      tdoneChanged(el){
        var tdone;
        var pos;
        this.tableData.some( (item, i) => {
          if(item.tid === el){
            tdone = item.tdone;
            pos = i;
          }
        })
        console.log(tdone);
        this.axios.post('taskStatusChange.php', {"tid": el, "tdone": tdone}).then( response => {
          let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
          console.log(res);
          if(res.status == 1){
            this.$message({
              message: '修改成功！',
              type: 'success'
            });
            
          }else{
            this.$message({
              message: '修改失败！',
              type: 'warning'
            });
            this.tableData[pos].tdone = !tdone;
          }
        })
        this.$store.commit('clearTodos');
        this.reload();
      },
      changeImportant(el){
        var timportant;
        var pos;
        this.tableData.some( (item, i) => {
          if(item.tid === el){
            item.timportant = !item.timportant;
            timportant = item.timportant;
            pos = i;
          }
        })
        console.log(timportant);
        this.axios.post('taskStatusChange.php', {"tid": el, "timportant": timportant}).then( response => {
          let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
          console.log(res);
          if(res.status == 1){
            this.$message({
              message: '修改成功！',
              type: 'success'
            });
            
          }else{
            this.$message({
              message: '修改失败！',
              type: 'warning'
            });
            this.tableData[pos].timportant = !timportant;
          }
        })
        this.$store.commit('clearTodos');
        this.reload();
      },

      getMyToDos(){
        let isInstore = this.$store.getters.getTodaytodos.length;
        console.log(isInstore)
        if(isInstore != 0){
          this.tableData = this.$store.getters.getTodaytodos;
          this.loading = false;
          console.log('直接从仓库取得');
        }else{
          this.axios.get('mydaytodos.php').then( response => {
              this.loading = true;
              let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
              console.log(res[0].status)
              if(res[0].status == 1){
                this.$store.commit('setTodaytodos', res);
                this.tableData = this.$store.getters.getTodaytodos;
                this.loading = false;
                console.log('通过api取得');
              }else if(res[0].status == 2){
                this.tableData = this.$store.getters.getTodaytodos;
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

<style>
  
</style>