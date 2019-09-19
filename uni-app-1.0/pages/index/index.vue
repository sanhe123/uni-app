<template>
	<view class="content">
		<view class="uni-list">
			<!-- <navigator url="../info/info"> -->
				<view 
					class="uni-list-cell" 
					hover-class="uni-list-cell-hover" 
					v-for="(item, index) in news" 
					:key="index" 
					@tap="openinfo" 
					:data-newsid="item.post_id">
					<view class="uni-media-list">
						<image class="uni-media-list-logo" :src="item.author_avatar"></image>
						<view class="uni-media-list-body">
							<view class="uni-media-list-text-top">{{ item.title }}</view>
							<view class="uni-media-list-text-bottom uni-ellipsis">{{ item.created_at }}</view>
						</view>
					</view>
				</view>
			<!-- </navigator> -->
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			news:[]
		};
	},
	//表示页面加载完毕
	onLoad() {
		uni.request({
			url: 'https://unidemo.dcloud.net.cn/api/news', //仅为示例，并非真实接口地址。
			data: {},
			header: {},
			success: res => {
				this.news = res.data;
			}
		});
	},
	methods: {
		openinfo(e){
			let newsid = e.currentTarget.dataset.newsid;
			//页面跳转
			uni.navigateTo({
				url: '../info/info?newsid='+newsid
			});
		}
	}
};
</script>

<style>
	.uni-media-list-body{
		height:auto;
	}
	.uni-media-list-text-top{
		line-height:1.6em;
	}
</style>
