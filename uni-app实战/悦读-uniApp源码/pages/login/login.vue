<template>
	<view>
		<!-- #ifdef MP-WEIXIN -->
		<button type="primary" open-type="getUserInfo" @getuserinfo="getUserInfo">使用微信登录</button>
		<!-- #endif -->
	</view>
</template>

<script>
var _self, session_key, openid, pageOptions;
var sign = require('../../commons/sign.js');
export default {
	data() {
		return {
			
		};
	},
	methods:{
		getUserInfo : function(info){
			//
			info = info.mp.detail.userInfo;
			var sign = uni.getStorageSync('sign');
			uni.request({
				url: _self.apiServer+'member&m=login',
				method: 'POST',
				header: {'content-type' : "application/x-www-form-urlencoded"},
				data: {
					openid : openid,
					name   : info.nickName,
                    face   : info.avatarUrl,
					sign   : sign
				},
				success: res => {
					console.log(res);
					res = res.data;
					uni.showToast({title:"登录成功"});
					uni.setStorageSync('SUID' , res.data.u_id + '');
					uni.setStorageSync('SRAND', res.data.u_random + '');
					uni.setStorageSync('SNAME', res.data.u_name + '');
					uni.setStorageSync('SFACE', res.data.u_face + '');
					// 跳转
					if(pageOptions.backtype == 1){
						uni.redirectTo({url:pageOptions.backpage});
					}else{
						uni.switchTab({url:pageOptions.backpage});
					}
				},
				fail: (e) => {
					console.log(e);
				}
			});
		}
	},
	onLoad:function(options){
		sign.sign(this.apiServer);
		
		pageOptions = options;
		_self = this;
		
		//微信
		// #ifdef MP-WEIXIN
		uni.login({
			success:function(res){
				console.log(res);
				uni.request({
					url: _self.apiServer+'member&m=codeToSession&code='+res.code,
					method: 'GET',
					success: res => {
						console.log(res);
						session_key = res.data.session_key;
                        openid      = res.data.openid;
					}
				});
			}
		})
		// #endif
		
		// app
		// #ifdef APP-PLUS
		uni.login({
			provider: 'weixin',
			success: (res) => {
				uni.getUserInfo({
					success:function(info){
						console.log(JSON.stringify(info));
						var sign = uni.getStorageSync('sign');
						uni.request({
							url: _self.apiServer+'member&m=login',
							method: 'POST',
							header: {'content-type' : "application/x-www-form-urlencoded"},
							data: {
								openid : info.userInfo.openId,
                                name   : info.userInfo.nickName,
                                face   : info.userInfo.avatarUrl,
								sign   : sign
							},
							success: res => {
								console.log(JSON.stringify(res));
								res = res.data;
								if(res.status == 'ok'){
									uni.showToast({title:"登录成功"});
									uni.setStorageSync('SUID' , res.data.u_id + '');
                                    uni.setStorageSync('SRAND', res.data.u_random + '');
						            uni.setStorageSync('SNAME', res.data.u_name + '');
                                    uni.setStorageSync('SFACE', res.data.u_face + ''); 
									// 跳转
                                    if(options.backtype == 1){
                                        uni.redirectTo({url:options.backpage});
                                    }else{
                                        uni.switchTab({url:options.backpage});
                                    }
								}else{
									console.log(JSON.stringify(res));
									uni.showToast({title:res.data});
								}
							},
							fail: (res) => {
								console.log(res);
							},
							complete: () => {}
						});
					},
					fail:function(){
						uni.showToast({title:"微信登录授权失败", icon:"none"});
					}
				})
			},
			fail: () => {
				uni.showToast({title:"微信登录授权失败",  icon:"none"});
			}
		})
		// #endif
	}
	
}
</script>

<style>

</style>
