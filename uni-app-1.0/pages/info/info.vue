<template>
	<view class="content">
		<view class="title">{{title}}</view>
		<rich-text class="richText" :nodes='string'></rich-text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				title:'',
				string:''
			}
		},
		onLoad(e) {
			//获取到前一个页面传递的数据
			let newsid = e.newsid;
			uni.request({
				url: 'https://unidemo.dcloud.net.cn/api/news/36kr/'+newsid, //仅为示例，并非真实接口地址。
				data: {},
				header: {},
				success: res => {
					console.log(res.data);
					this.title = res.data.title;
					this.string = res.data.content;
				}
			});
		},
		methods: {
			
		}
	}
</script>

<style>
	.content{
		padding:10upx 2%;
		width:96%;
		flex-wrap: warp;
	}
	.title{
		line-height: 2em;
		font-weight: 700;
		font-size: 38upx;
	}
</style>
