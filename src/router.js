import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import About from './views/About.vue'
import Login from './views/Login.vue'
import Admin from './views/Admin.vue'
// import MyDay from './components/user/MyDay.vue'
// import ImportantTask from './components/user/ImportantTask.vue'
// import AllTask from './components/user/AllTask.vue'
import AddTask from './components/user/AddTask.vue'
import EditTask from './components/user/EditTask.vue'
import EditUser from './components/user/EditUser.vue'
import { resolve } from 'path';

Vue.use(Router)

export default new Router({
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/home', component: Home },
    { path: '/admin/', component: Admin, redirect: '/admin/myday', 
      children: [
        { path: 'myday', component: resolve => require(['./components/user/MyDay.vue'], resolve)},
        { path: 'importanttask', component: resolve => require(['./components/user/ImportantTask.vue'], resolve)},
        { path: 'alltasks', component: resolve => require(['./components/user/AllTask.vue'], resolve) },
        { path: 'addtask', component: AddTask},
        { path: 'edittask/:tid/:tname', component: EditTask},
        { path: 'addlist', component: resolve => require(['./components/user/AddList.vue'], resolve) },
        { path: 'list/:lname', component: resolve => require(['./components/user/List.vue'], resolve)},
        { path: 'userinfo/:uid', component: EditUser}
      ],
    },
    { path: '/login', component: Login },
  ]
})
