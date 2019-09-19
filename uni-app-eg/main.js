import Vue from 'vue'
import App from './App'

import pageHead from './components/page-head.vue'
import pageFoot from './components/page-foot.vue'
import uLink from '@/components/uLink.vue'
import store from './store'

Vue.config.productionTip = false

Vue.prototype.$store = store
Vue.prototype.$backgroundAudioData = {
	playing: false,
	playTime: 0,
	formatedPlayTime: '00:00:00'
}

Vue.component('page-head', pageHead) //全局注册page-head组件
Vue.component('page-foot', pageFoot)
Vue.component('uLink', uLink)

App.mpType = 'app'

const app = new Vue({
	store,
	...App
})
app.$mount()//挂载Vue实例

/**
 * 注意事项：
 * 
 * 无法使用vue-router，路由须在pages.json中进行配置。
 * nvue 暂不支持在 main.js 注册全局组件
 */
