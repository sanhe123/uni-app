<template>
	<view>
		login
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		onLoad(options){
			console.log(options)
			// app端-微信登录-需要在微信开放平台进行注册
			// #ifdef MP-WEIXIN
			uni.login({ //获取openid
				success(res) {
					console.log(res)
					uni.getUserInfo({ //获取用户信息
						success(info) {
							console.log(info)
						},
						fail() {
							uni.showToast({
								title:'微信登录授权失败',
								icon:"none"
							})
						}
					})
				},
				fail() {
					//给出提示信息
					uni.showToast({
						title:'微信登录授权失败',
						icon:"none"
					})
				}
			})
			// #endif
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
