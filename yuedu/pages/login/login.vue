<template>
	<view>
		<!-- #ifdef MP-WEIXIN -->
		<button @getuserinfo="getuserinfo" openType="getUserInfo">微信授权</button>
		<!-- #endif -->
		
	</view>
</template>

<script>
	var session_key,openid;
export default {
	data() {
		return {
			userInfo: '',
			openId: '',
			options:''
		};
	},
	onLoad(options) {
		let _this = this;
		//微信
		// #ifdef MP-WEIXIN
		uni.login({
			success:function(res){
				console.log('code'+res.code);
				uni.request({
					url: _this.apiServer+'member&m=codeToSession&code='+res.code,
					method: 'GET',
					success: res => {
						session_key = res.data.session_key;
						openid = res.data.openid;
					}
				});
			}
		})
		// #endif 
		
		// app端-微信登录-需要在微信开放平台进行注册
		// #ifdef APP-PLUS
		uni.login({
			//获取code码
			success(res) {
				// console.log(res)
				_this.getUserInfo_APP();
			},
			fail() {
				//给出提示信息
				uni.showToast({
					title: '微信登录授权失败',
					icon: 'none'
				});
			}
		});
		// #endif
	},
	methods: {
		//app端获取用户信息
		getUserInfo_APP(){
			let _this = this;
			// 获取用户信息
			uni.getUserInfo({
				provider: 'weixin',
				success: function(info) {
					console.log(info.userInfo);
					_this.userInfo = info.userInfo;
					_this.$post({
						url: 'member&m=login',
						data: {
							openid: _this.userInfo.openId,
							name: _this.userInfo.nickName,
							face: _this.userInfo.avatarUrl
						},
						callBack: function(data) {
							uni.showToast({
								title: '登录成功',
								icon: 'none'
							});
							//存储在本地-同步
							uni.setStorageSync('SUID', data.u_id + '');
							uni.setStorageSync('SRAND', data.u_random + ''); //随机码
							uni.setStorageSync('SNAME', data.u_name + ''); //name
							uni.setStorageSync('SFACE', data.u_face + ''); //face
							if (_this.options.backtype == 1) {
								uni.redirectTo({
									url: _this.options.backpage
								});
							} else {
								uni.switchTab({
									url: _this.options.backpage
								});
							}
						}
					});
				}
			});
		},
		getuserinfo(info){
			var _this = this;
			console.log(info)
			_this.$post({
				url: 'member&m=login',
				data: {
					openid: openid,
					name: info.detail.userInfo.nickName,
					face: info.detail.userInfo.avatarUrl
				},
				callBack: function(data) {
					console.log(data)
					uni.showToast({
						title: '登录成功',
						icon: 'none'
					});
					//存储在本地-同步
					uni.setStorageSync('SUID', data.u_id + '');
					uni.setStorageSync('SRAND', data.u_random + ''); //随机码
					uni.setStorageSync('SNAME', data.u_name + ''); //name
					uni.setStorageSync('SFACE', data.u_face + ''); //face
					
					if (_this.options.backtype == 1) {
						uni.redirectTo({
							url: _this.options.backpage
						});
					} else {
						uni.switchTab({
							url: _this.options.backpage
						});
					}
				}
			});
		}
	}
};
</script>

<style>
button {
	margin: 20rpx;
}
</style>
