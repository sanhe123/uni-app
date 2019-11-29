<template>
	<view>
		<!-- 标题 -->
		<view :class="['grace-article-title', graceSkeleton == 'ing' ? 'grace-skeleton' : '']">{{ article.art_title }}</view>
		<!-- 作者信息 -->
		<view class="grace-article-author-line">
			<view :class="['grace-article-author', graceSkeleton == 'ing' ? 'grace-skeleton' : '']">
				<image :src="article.u_face" mode="widthFix"></image>
				<view class="author-name">{{ article.u_name }}</view>
			</view>
			<view>{{ article.art_createtime }}</view>
		</view>
		<!-- 文章内容 -->
		<view class="grace-article-contents">
			<block v-for="(item, index) in artContents" :key="index">
				<view :class="['img-item', graceSkeleton == 'ing' ? 'grace-skeleton' : '']" v-if="item.type == 'image'">
					<image :src="item.content" :data-url="item.content" mode="widthFix" @click="showImgs"></image>
				</view>
				<view :class="['text-item', graceSkeleton == 'ing' ? 'grace-skeleton' : '']" v-if="item.type == 'text'">{{ item.content }}</view>
			</block>
		</view>
	</view>
</template>

<script>
var artid, _self;
export default {
	data() {
		return {
			article: [],
			artContents: [],
			graceSkeleton: 'ing'
		};
	},
	methods: {
		showImgs(e){
			var currentUrl = e.currentTarget.dataset.url;
			var imgsNeedShows = [];
			for(var i =0;i<this.artContents.length;i++){
				if(this.artContents[i].type==='image'){
					imgsNeedShows.push(this.artContents[i].content);
				}
			}
			uni.previewImage({
				urls:imgsNeedShows,
				current:currentUrl
			})
		}
	},
	onLoad(option) {
		_self = this;
		artid = option.artid;
		uni.showLoading({
			title:''
		})
		uni.request({
			url: _self.apiServer + 'art&m=infoWithUser&artid=' + artid,
			method: 'GET',
			success: res => {
				console.log(res);
				var art = res.data.data;
				var artContentItems = JSON.parse(art.art_content);
				//规划骨架
				this.artContents = [];
				for (var i = 0; i < artContentItems.length; i++) {
					this.artContents.push({ type: artContentItems[i].type });
				}
				setTimeout(function() {
					_self.article = art;
					_self.artContents = artContentItems;
					_self.graceSkeleton = 'end';
					uni.hideLoading();
				}, 500);
			}
		});
	}
};
</script>

<style></style>
