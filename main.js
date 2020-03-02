import Vue from 'vue'
import App from './App'

import store from './store'
import request from '@/common/request.js'
import config from '@/common/config.js'
Vue.config.productionTip = false

Vue.prototype.$store = store
Vue.prototype.$request = request
Vue.prototype.$userInfo = function(){
	return JSON.parse(uni.getStorageSync('login_info'))
}

function connectSocket(){
	uni.connectSocket({
	  url: `ws://${config.ip}:3000`
	});
}
uni.loadFontFace({
	family: 'ikingFont',
	// 本地字体路径需转换为平台绝对路径
	// #ifdef APP-PLUS
	source: `url(${plus.io.convertLocalFileSystemURL('@/static/font/SourceHanSansCN-Bold.otf')})`,
	// #endif
	// #ifdef H5
	source: `url(${'/static/font/SourceHanSansCN-Bold.otf'})`,
	// #endif
	success() {
		console.log('success')
	},
	fail(e) {
		console.log('fail',JSON.stringify(e))
	}
})
connectSocket();
uni.onSocketOpen(function (res) {
	setInterval(()=>{
		uni.getLocation({
			type: 'gcj02',
			geocode: true,
			success: function(e) {
				e.userCode = JSON.parse(uni.getStorageSync('login_info')).username
				uni.sendSocketMessage({
				  data: JSON.stringify(e)
				});
			}
		});
	},1000)
	
});

uni.onSocketMessage(function (res) {
  // console.log('收到服务器内容：' +res.data);
});
uni.onSocketError(function (res) {
  console.log('WebSocket连接打开失败，请检查！');
  connectSocket()
});
uni.onSocketClose(function (res) {
  console.log('WebSocket 已关闭！');
  connectSocket()
});

App.mpType = 'app'


const app = new Vue({
    store,
    ...App
})
app.$mount()
