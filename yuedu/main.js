import Vue from 'vue'
import App from './App'
import {checkLogin,apiServer,postRequest} from './common/js/tool.js'


Vue.config.productionTip = false
Vue.prototype.checkLogin = checkLogin;
Vue.prototype.apiServer = apiServer;
Vue.prototype.$post = postRequest;
Vue.prototype.staticServer = 'http://192.168.1.180/';
App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
