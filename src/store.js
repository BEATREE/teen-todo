import Vue from 'vue'
import Vuex from 'vuex'
import { Date } from 'core-js';

Vue.use(Vuex)

// 读取本地用户设置
var initColor = JSON.parse( localStorage.getItem('primaryColor')) || "#bbe6d6";
console.log(localStorage.getItem('primaryColor'))

var initUserInfo = JSON.parse( localStorage.getItem('userinfo')) || {
  userid: "", // user id
  username: "BEATREE", // 用户昵称
  headImg: "", // 用户头像
  useremail: "",

};


export default new Vuex.Store({
  state: {
    primaryColor: initColor,
    themeColors:[{
      value: '#bbe6d6',
      label: '默认主题'
    },{
      value: '#409eff',
      label: '亮色蓝'
    },{
      value: '#40FFDF',
      label: '荧光绿'
    },{
      value: '#BC40FF',
      label: '高贵紫'
    },{
      value: '#E6A23C',
      label: '活力橙'
    }],
    userInfo: initUserInfo,   // 用户信息
    todayTodos: [],           // 今天 Todo
    allTodos:[],              // 所有 Todo
    importantTodos:[],        // 重要 Todo

    lists:[],                 // 一个数组，数组中，存列表list对象
  },
  mutations: {
    changePrimaryColor(state, newColor){
      state.primaryColor = newColor;
      // console.log(JSON.stringify(state.primaryColor))
      localStorage.setItem('primaryColor', JSON.stringify(state.primaryColor));
    },
    setUserInfo(state, user){
      state.userInfo.userid = user.userid;
      state.userInfo.username = user.username;
      state.userInfo.headImg = user.headImg;
      state.userInfo.useremail = user.useremail;
      localStorage.setItem('userinfo', JSON.stringify(state.userInfo));
    },
    setAlltodos(state, allTodos){             // 设置所有Todo项
      for(var i = 1; i<allTodos.length; i++){
        state.allTodos[i-1] = allTodos[i];
      }
    },
    setImportantTodos(state, importantTodos){ // 设置importantTodos 项
      for(var i = 1; i<importantTodos.length; i++){
        state.importantTodos[i-1] = importantTodos[i];
      }
    },
    setTodaytodos(state, todayTodos){         // 设置今日的 Todo 项
      for(var i = 1; i<todayTodos.length; i++){
        state.todayTodos[i-1] = todayTodos[i];
      }
    },
    setLists(state, lists){                   // 设置 lists
      for(var i = 0; i<lists.length; i++){
        state.lists[i] = lists[i];
      }
    },

    addTodos(state, todo){            // 向所有 todo 中添加
      state.allTodos.push(todo);
    },
    addMyTodos(state, todo){          // 向今日 todo 中添加
      state.todayTodos.push(todo);
    },
    addImportantTodos(state, todo){   // 向重要 todo 中添加项
      state.importantTodos.push(todo);
    },
    addLists(state, list){            // 想List中添加项
      state.lists.push(list);
    },

    clearAlltodos(state){  // 清空alltodos
      state.allTodos = [];
    },
    clearTodayTodos(state){  // 清空todayTodos
      state.todayTodos = [];
    },
    clearImportantTodos(state){  // 清空todayTodos
      state.importantTodos = [];
    },
    clearTodos(state){
      state.importantTodos = [];
      state.todayTodos = [];
      state.allTodos = [];
    },
    clearLists(state){
      state.lists=[];
    }
    
  },
  getters: {
    getPrimaryColor(state){   // 返回选用的主题颜色
      return state.primaryColor;
    },
    getThemeColors(state){    // 返回所有主题颜色
      return state.themeColors;
    },
    getUserInfo(state){       // 返回 用户信息
      return state.userInfo;
    },
    getAlltodos(state){       // 返回所有的 todos
      return state.allTodos;
    },
    getTodaytodos(state){     // 获取今天的 todos
      return state.todayTodos;
    },
    getImportanttodos(state){
      return state.importantTodos;
    },
    getLists(state){          // 返回列表
      return state.lists;
    }

  },
  
})
