<template>
<div class="outestDiv">
  <div class="loginContainer">
    
    <el-tabs v-model="activeName"  class="login-box" stretch> <!-- @tab-click="handleClick" -->
        <el-tab-pane label="登录" name="first">
            <el-form ref="loginForm" :model="form" :rules="rules" label-width="80px"  >
                <h3 class="login-title">欢迎登录</h3>
                <el-form-item label="账号" prop="email">
                    <el-input type="text" placeholder="请输入账号" v-model="form.email"/>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" placeholder="请输入密码" v-model="form.password" show-password/>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" v-on:click="onLogin('loginForm')">登录</el-button>
                </el-form-item>
            </el-form>
        </el-tab-pane>
        <el-tab-pane label="注册" name="second" lazy>
            <el-form ref="registerForm" :model="form" :rules="rules" label-width="80px"  >
                <h3 class="login-title">欢迎注册</h3>

                <el-form-item label="选择头像">
                    <el-upload
                      class="avatar-uploader"
                      name="userheadpic"
                      action="http://localhost:3000/fileupload.php"
                        
                      :with-credentials=true
                      :show-file-list="false"
                      :on-success="handleAvatarSuccess"
                      :before-upload="beforeAvatarUpload">
                      <img v-if="imageUrl" :src="imageUrl" class="avatar">
                      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>

                <el-form-item label="昵称" prop="username">
                    <el-input type="text" placeholder="请输入昵称" v-model="form.username"/>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input type="text" placeholder="请输入邮箱，邮箱将被用来登录" v-model="form.email"/>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" placeholder="请输入密码" v-model="form.password" show-password/>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" v-on:click="onRegister('registerForm')">注册</el-button>
                </el-form-item>
            </el-form>
        </el-tab-pane>
    </el-tabs>
    

    <el-dialog
      title="温馨提示"
      :visible.sync="dialogVisible"
      width="30%">
      <span>请输入账号和密码</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
  </div>
</template>

<script>

  export default {
    name: "Login",
    data() {
      return {
        form: {
          username: '',
          password: '',
          email: '',
          userhead: '',
        },

        // 表单验证，需要在 el-form-item 元素中增加 prop 属性
        rules: {
          username: [
            {required: true, message: '账号不可为空', trigger: 'blur'}
          ],
          email: [
            {required: true, message: '邮箱不可为空', trigger: 'blur'},
            { type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }
          ],
          password: [
            {required: true, message: '密码不可为空', trigger: 'blur'}
          ]
        },
        // el-tabs active element
        activeName: 'first',
        // 对话框显示和隐藏
        dialogVisible: false,
        // 登录表单显示与隐藏
        loginFormVisible: true,
        // 注册表单显示与隐藏
        registerFormVisible: false,
        imageUrl: '',
      }
    },
    created(){
        this.checkLoginStatus();
        this.$notify({
          title: 'TeenTODO | 做一个坚定地执行者',
          message: '<h4>这是一个优雅简约的TODO网站，可以方便的记录您的TODO事项。</h4><br><h4>快来创建你的TODO吧~</h4>',
          dangerouslyUseHTMLString: true,
          iconClass:'el-icon-s-opportunity',
          position: 'bottom-right',
          customClass:'webinfo',
          duration: 5200
        });
    },
    methods: {
      checkLoginStatus(){ // 查看登录状态
        var loginStatus = localStorage.getItem('currentUser') || false;
        if(!loginStatus){
          return ;
        }else{
          this.$router.push("/admin")
        }
      },
      onLogin(formName) {
        // 为表单绑定验证功能
        this.$refs[formName].validate((valid) => {
          if (valid) {
            // 使用 vue-router 路由到指定页面，该方式称之为编程式导航
            // this.$router.push("/main");
            this.axios.post('login.php',this.form).then( response => {
              let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
              if(res.status=='1'){   //显示登录结果             
                console.log('登录成功');
                localStorage.setItem('currentUser', true);
                this.$message({
                  message: 'Bingo, 登录成功！',
                  type: 'success'
                });
                this.$store.commit('setUserInfo', {'userid':res.uid, 'username':res.uname, 'headImg': res.uhead, 'useremail': res.uemail});
                this.$router.push("/admin")
              }else {
                console.log('登录失败');
                console.log(response);
                // 弹出登录失败的提示
                this.$message.error('用户名或密码不正确，登录失败！');
              }
            })

          } else {
            this.dialogVisible = true;
            return false;
          }
        });
      },
      onRegister(formName){
        // 为表单绑定验证功能
        this.$refs[formName].validate((valid) => {
          if (valid) {
            // 使用 vue-router 路由到指定页面，该方式称之为编程式导航
            // this.$router.push("/main");
            this.axios.post('register.php',this.form).then( response => {
              let res = response.data;   //用res承接返回后台的json文件(像使用数组那样)
              console.log(res);
              if(res.status=='1'){   //显示登录结果             
                console.log('注册成功');
                localStorage.setItem('currentUser', true);
                this.$message({
                  message: '恭喜你，注册成功，已自动登录',
                  type: 'success'
                });
                this.$store.commit('setUserInfo', {'userid':res.uid, 'username':res.uname, 'headImg': res.uhead, 'useremail': res.uemail});
                this.$router.push("/admin")
              }else if(res.status=='2'){
                console.log('注册失败');
                console.log(response);
                // 弹出登录失败的提示框
                this.$message({
                  message: '当前邮箱已被注册！',
                  type: 'warning'
                });
              }else {
                console.log('注册失败');
                console.log(response);
                // 弹出登录失败的提示框
                this.$message.error('帐号注册失败！');
              }
            })

          } else {
            this.dialogVisible = true;
            return false;
          }
        });
      },
      handleClick(tab, event) {
        console.log(tab, event);
      },
      handleAvatarSuccess(res, file) {
        this.imageUrl = URL.createObjectURL(file.raw);
        this.form.userhead = res.storepath;
        // console.log(res)
      },
      beforeAvatarUpload(file) {
        const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png'|| file.type === 'image/gif');
        const isLt2M = file.size / 1024 / 1024 < 2;

        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 或 PNG 或 GIF 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 2MB!');
        }
        return isJPG && isLt2M;
      }
    }
  }
</script>

<style lang="scss">
.el-notification .webinfo{
  width: 380px !important;
}
</style>