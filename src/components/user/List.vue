<template>
  <el-table
    :data="tableData.filter(data => !search || data.tname.toLowerCase().includes(search.toLowerCase()))"
    style="width: 100%"
    v-loading="loading"
    >
    
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

    <el-table-column label="删除">
      <template slot="header" slot-scope="scope">

        <el-popover
                placement="bottom"
                width="160"
                trigger="click"
                v-model="popovervisible">
              <p>确定删除该清单吗？</p>
              <div style="text-align: right; margin: 0">
                  <el-button size="mini" type="text" @click="popovervisible = false">取消</el-button>
                  <el-button type="primary" size="mini" @click="deleteThisList">确定</el-button>
              </div>
              <el-tooltip class="item" effect="dark" content="删除该清单" placement="bottom" transition="el-zoom-in-top" slot="reference">
                  <el-button type="danger" icon="el-icon-delete"  circle></el-button>
              </el-tooltip>
          </el-popover>


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
        pagelname: '',
        popovervisible:false  // 控制确认框显示
      }
    },
    created(){
      this.getListToDos();
    },
    methods: {
      handleEdit(index, row) {
        console.log(index, row);
        this.$router.push('/admin/edittask/'+row.tid+'/'+row.tname);
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
            this.$store.commit('setLists', []);
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
        }).then( then => {                // 保证默认组件与元组件定义内容不冲突
          this.$store.commit('clearTodos');
          this.reload();
        })
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

      getListToDos(){
        this.pagelname = this.$route.params.lname;   // 获取路径参数
        let listInstore = null;
        this.$store.getters.getLists.some((list)=>{
            if(list.lname == this.pagelname){
                listInstore = list;
            }
        });
        console.log(listInstore)

        if(listInstore != null){                      // 判断本地缓冲中是否存在
          let tids = listInstore.tid; 
          
          this.fullTableData(tids)  // 填充tabledata

          this.loading = false;
          console.log('直接从仓库取得');
        }else{
          this.axios.post('getlist.php', {'lname': this.pagelname}).then( response => {
              this.loading = true;
              let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
              console.log(res[0].status)
              if(res[0].status == 1){
                
                this.fullTableData(res[1]) ;    // 填充tabledata

                // // 再次进行判断
                // listInstore = null;
                // this.$store.getters.getLists.some((list)=>{
                //     if(list.lname == this.pagelname){
                //         listInstore = list;
                //     }
                // });
                // if(listInstore == null){
                //    this.$store.commit('addLists', {'lname':this.pagelname, 'tid':res[1]} );
                // }
               

                console.log(res[1])

                this.loading = false;
                console.log('通过api取得');
              }else if(res[0].status == 2){
                this.tableData = [];
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

      fullTableData(tids){
        if(this.$store.getters.getAlltodos.length != 0){
          this.$store.getters.getAlltodos.forEach(element => {
              if(tids.indexOf(element.tid) != -1){      // 列表中含有该任务
                  this.tableData.push(element);           // 放入table data
              }
          });
        }else{
          this.axios.get('alltodos.php').then( response => {
              let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
              console.log(res[0].status)
              if(res[0].status == 1){
                this.$store.commit('setAlltodos', res);
              }else if(res[0].status == 2){
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
              this.$store.getters.getAlltodos.forEach(element => {
                  if(tids.indexOf(element.tid) != -1){      // 列表中含有该任务
                      this.tableData.push(element);           // 放入table data
                  }
              });
          })
          
        }
      },

      deleteThisList(){       // 删除清单
          this.axios.post('dellist.php', {'lname': this.pagelname}).then( response => {
            let rel = response.data;
            if(rel.status == 1){
              this.$store.commit('clearLists');
              this.$router.push('/admin')
            }else if(rel.status == 0){
              localStorage.removeItem('currentUser');
                localStorage.removeItem('userinfo');
                this.$router.push("/login")
                this.$message({
                  message: '登录信息已过期！',
                  type: 'warning'
                });
            }else{
              console.log(response)
            }
          })
      }

    },
    watch: {
      $route(newValue, oldValue) {
        this.tableData = []
        this.getListToDos();
      },
    },
    
    inject:['reload'],

  }
</script>

<style>
  
</style>