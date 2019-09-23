import Vue from 'vue'
import App from './App'
import {checkLogin,apiServer} from './common/js/tool.js'


Vue.config.productionTip = false
Vue.prototype.checkLogin = checkLogin;
Vue.prototype.apiServer = apiServer;
App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
