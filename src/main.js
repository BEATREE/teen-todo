import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import Qs from 'qs'
import './plugins/element.js'
import './styles/index.scss'
// 导入时间插件
import moment from 'moment'

Vue.config.productionTip = false;

axios.defaults.headers = {
  "Content-Type": "application/x-www-form-urlencoded;charset=utf-8"
}
axios.defaults.baseURL = 'http://localhost:3000/api/'
// axios.defaults.baseURL = 'http://lab.teenshare.club/teen-todo/api/'
// 使每个请求带上session信息，我设置了withCredentials=true; 这个如果设置后，则服务器端必须指定域名
// Access-Control-Allow-Origin 字段必须指定域名，不能为*
// Access-Control-Allow-Credentials为true
axios.defaults.withCredentials = true;

Vue.prototype.axios = axios;
Vue.prototype.qs = Qs;


// 定义全局的过滤器 (名称， 方法)
Vue.filter('dateFormat', function(dataStr, pattern="YYYY-MM-DD HH:mm:ss"){
  if(dataStr!==null){
    return moment(dataStr).format(pattern);
  }else{
    return "今天";
  }
  
})

new Vue({
  el: "#app",
  router,
  store,
  render: h => h(App)
}).$mount('#app')
