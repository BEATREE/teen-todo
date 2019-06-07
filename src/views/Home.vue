<template>
<div class="outestDiv">
  <el-container class="wrapper">
    <el-menu default-active="1-4-1"
        class="el-menu-vertical-demo"
        @open="handleOpen"
        @close="handleClose"
        :collapse="isCollapse"
        router
        >
        
        <el-menu-item index="/home/about">
          <i class="el-icon-menu"></i>
          <span slot="title">关于我们</span>
        </el-menu-item>
        
        <el-menu-item index="/home/login">
          <i class="el-icon-setting"></i>
          <span slot="title">登录</span>
        </el-menu-item>
    </el-menu>

    <router-view></router-view>
  </el-container>
</div>
</template>

<script>
  export default {
    data() {
      return {
        isCollapse: true
      };
    },
    methods: {
      handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      }
    }
  }
</script>

<style lang="scss" scoped>

.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}

</style>