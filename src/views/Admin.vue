<template>
  <el-container class="wrapper">
    <el-header height="80px"
      :style="{ 'background-color': primaryColor }">
      <img
          src="../assets/todologo.png"
          alt="TEEN-TODO-logo"
          class="header-logo">

      <ul class="header-operations">

        <li>
          <el-select v-model="primaryColor" placeholder="更换主题" style="width:160px;">
            <el-option
              v-for="item in colors"
              :key="item.value"
              :label="item.label"
              :value="item.value"
              :disabled="item.disabled">
            </el-option>
          </el-select>
          <!-- <el-color-picker v-model="primaryColor"></el-color-picker> -->
        </li>
        

        <li>
          <el-button type="primary" icon="el-icon-plus" @click="toAddTask()" round>添加任务</el-button>
        </li>

        <li>
          <!-- 退出账号 -->
          <el-popover
                placement="bottom"
                width="160"
                trigger="click"
                v-model="popovervisible">
              <p>确定退出当前账号吗？</p>
              <div style="text-align: right; margin: 0">
                  <el-button size="mini" type="text" @click="popovervisible = false">取消</el-button>
                  <el-button type="primary" size="mini" @click="logout">确定</el-button>
              </div>
              <el-tooltip class="item" effect="dark" content="安全退出" placement="bottom" transition="el-zoom-in-top" slot="reference">
                <el-button type="danger" icon="el-icon-switch-button" circle></el-button>
              </el-tooltip>
          </el-popover>
          

        </li>
      </ul>
    </el-header>

    <el-container>
      <el-aside class="menu">
        
          <el-menu
            :default-active="$route.path"
            class="el-menu-vertical-demo"
            :active-text-color="primaryColor"
            @open="handleOpen"
            @close="handleClose"
            router
            >
            
             <el-card class="box-card" style="word-wrap: break-word;">
               <router-link :to="'/admin/userinfo/'+currentUser.userid">
                <el-image
                  style="width: 100px; height: 100px;"
                  :src="currentUser.headImg"
                  fit="cover">
                </el-image>
                <span style="font-size: 18px;margin-left:2px;">{{currentUser.username}}</span>
               </router-link>
            </el-card>
            
          <hr>

          <el-menu-item index="/admin/myday">
            <i class="el-icon-sunny"></i>
            <span slot="title">我的一天</span>
          </el-menu-item>
          <el-menu-item index="/admin/importanttask">
            <i class="el-icon-star-off"></i>
            <span slot="title">重要TODO</span>
          </el-menu-item>
          <el-menu-item index="/admin/alltasks">
            <i class="el-icon-document"></i>
            <span slot="title">任务</span>
          </el-menu-item>
          <hr>
          <el-submenu index='mylist'>
            <template slot="title">
              <i class="el-icon-notebook-1"></i>
              <span>我的清单</span>
            </template>
            
            <el-menu-item-group>
                <el-menu-item  v-if="lists.length == 0">
                  <i class="el-icon-notebook-2"></i>
                  暂无ToDo清单
                </el-menu-item>
               <el-menu-item  v-for="list in lists" :key="list.lname" :index="'/admin/list/'+list.lname" v-show='!loading'>
                  <i class="el-icon-notebook-2"></i>
                  <span slot="title">{{ list.lname }}</span>
                </el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-menu-item index="/admin/addlist" class="is-active">
            <i class="el-icon-plus"></i>
            <span slot="title">新建清单</span>
          </el-menu-item>

          <hr>

          <el-menu-item :index="'/admin/userinfo/'+currentUser.userid">
            <i class="el-icon-user"></i>
            <span slot="title">个人信息</span>
          </el-menu-item>

        </el-menu>
      </el-aside>

      <!--右侧主区域-->
      <el-main class="content">
        <!-- 放置中间的路由 router-view 区域 -->
        <!-- 添加动画样式 -->
        <transition name='mainContent'>
          <!-- @freshData="reload" 将父组件方法传递给子组件，让子组件进行调用，保证数据 -->
          <router-view v-if="isRouterAlive" ></router-view>
        </transition>
      </el-main>
    </el-container>

  </el-container>
</template>

<script>
import HelloWorld from '../components/HelloWorld.vue'
import AddList from '../components/user/AddList.vue'
import List from '../components/user/List.vue'

export default {
  name: 'app',
  data(){
    return {
      primaryColor: "#bbe6d6", // 主题颜色
      isActive: "",
      currentTheme: "",  // 主题颜色
      colors: [],
      popovervisible: false,   // 设置确认框
      currentUser: {
        userid: "", // user id
        username: "BEATREE", // 用户昵称
        headImg: "", // 用户头像
        useremail: "",
      },
      lists: [],
      isRouterAlive: true,  // 控制路由模块显示
      loading: true,
    }
  },
  created(){
    this.checkLogin();
    this.getLists();          // 获取列表信息
    this.setThemeColors();
    this.isActive = this.$route.path.split('/')[this.$route.path.split('/').length-1];
    this.setPrimaryColor();
    this.getUserInfo();       // 获取用户信息
  },
  methods: {

    reload(){
      this.isRouterAlive = false;
      this.$nextTick(() => {
        this.isRouterAlive = true;
      })
    },

    handleOpen(key, keyPath) {      // 子列表展开
      // console.log(key, keyPath);
      this.getLists();
    },
    handleClose(key, keyPath) {
      // console.log(key, keyPath);
      this.getLists();
    },
    setPrimaryColor(){  // 设置主题颜色
      this.primaryColor = this.$store.getters.getPrimaryColor;
    },
    setThemeColors(){   // 初始化所有主题颜色
      this.colors = this.$store.getters.getThemeColors;
    },
    checkLogin(){   // 登录拦截器
      var loginStatus = localStorage.getItem('currentUser') || false;
      if(!loginStatus){
        this.$router.push("/login")
      }else{
        this.showNotify();
      }
    },
    showNotify(){   // 显示通知消息
      this.$notify({
        iconClass:"el-icon-s-opportunity",
        title: '网站通知',
        message: '退出系统请点击右上角按钮"安全退出"',
        position: 'bottom-right'
      });
    },
    getUserInfo(){
      this.currentUser = this.$store.getters.getUserInfo;
    },
    getLists(){     // 获取清单列表
      this.lists = [];
      if(this.$store.getters.getLists.length != 0){
        this.lists = this.$store.getters.getLists;
        this.loading = false;
      }else{
        this.axios.post('getlist.php', {'init':true}).then( response => {
          var res = response.data;
          if(res[0].status == 1){
            this.$store.commit('setLists', res[1]);
            this.lists = this.$store.getters.getLists;
            this.loading = false;
          }
          console.log(res)
        })
      }
      
    },
    toAddTask(){      // 跳到添加任务页
        this.$router.push('/admin/addtask')
    },
    logout(){     // 退出系统
      localStorage.removeItem('currentUser');
      localStorage.removeItem('userinfo');
      this.$store.commit('clearTodos');
      this.$router.push("/login");
      
      this.axios.get('logout.php').then( response => {
        if(response.data.status==1){
          this.$message({
            message: '已成功退出！',
            type: 'info'
          });
        }
      })
    }
  },
  
  components: {
    HelloWorld,
    AddList,
    List
  },
  watch: {
    currentTheme(newVal, oldVal) {
      this.primaryColor = newVal;
    },
    primaryColor(newVal, oldVal) {
      this.$store.commit('changePrimaryColor', newVal);
    },
  },
  provide(){
    return {
      reload: this.reload,
    }
  },
}
</script>

<style scoped>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
.header-logo {
  height: 76px;
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
}
hr {
  width: 82%;
  margin:0 auto;
  border: 0;
  height: 2px;
  border-top: 2px solid rgba(0, 0, 0, 0.1);
}
.box-card .el-image{
  border-radius: 25%;
  width: 30% !important;
  height: 30% !important;
}



</style>