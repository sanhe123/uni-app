<template>
	<view class="wrap">
		<view class="write_title"><input type="text" v-model="title" placeholder="请输入标题" /></view>
		<!-- 内容展示区 -->
		<view class="artList">
			<block v-for="(item, index) in artList" :key="index">
				<view class="item" v-if="item.type == 'image'"><image :src="item.content" :data-index="index" mode="widthFix" @tap="removeImg" /></view>
				<view class="item" v-if="item.type == 'text'">
					<textarea placeholder="" v-model="artList[index].content" />
					<view :data-index="index" class="deleteText" @tap="deleteText">删除</view>
				</view>
			</block>
		</view>
		<!-- 输入区 -->
		<form @submit="submit">
			<view class="inputArea">
				<view class="addImg" @tap="addImg">+图片</view>
				<view class="addText">
					<textarea name="artText" maxlength="-1" v-model="inputContent" placeholder="请输入文本" />
					<button type="primary" form-type="submit">添加</button>
				</view>
			</view>
		</form>
		<!-- 选择分类 -->
		<view class="art-cate">
			<view>文章分类</view>
			<view class="">
				<!--从底部弹起的滚动选择器，现支持五种选择器，通过mode来区分，
				分别是普通选择器，多列选择器，时间选择器，日期选择器，省市区选择器，
				默认是普通选择器。 mode="selector" -->
				<picker mode="selector" :range="caties" @change="cateChange">
					<view>{{ caties[currentCateIndex] }}</view>
				</picker>
			</view>
		</view>
		<!-- 提交按钮 -->
		<view class="submitNow" v-if="artList.length > 0" @tap="submitNow">发布文章</view>
		<view>
</view>
	</view>
</template>

<script>
var loginRes, _self;
var signModel = require('../../common/js/sign.js');
export default {
	data() {
		return {
			title: '',
			artList: [],
			inputContent: '',
			needUploadImg: [],
			uploadIndex: 0,
			//分类
			caties: ['点击选择'],
			currentCateIndex: 0,
			catiesFromApi: [], //存储接口请求分类数据
			// 记录真实选择的api接口数据的分类id
			sedCateIndex: 0
		};
	},
	onLoad() {
		_self = this;
		signModel.sign(this.apiServer);
		loginRes = this.checkLogin('../write/write', 2);
		if (!loginRes) {
			return;
		}
		// 加载文章分类
		uni.request({
			url: this.apiServer + 'category&m=index',
			method: 'GET',
			success: res => {
				if (res.data.status == 'ok') {
					// 把数据格式整理为 picker 支持的格式 ['分类名', ...]
					var categories = res.data.data;
					for (var k in categories) {
						_self.caties.push(categories[k]);
					}
					// 记录分类信息
					_self.catiesFromApi = categories;
				}
			}
		});
	},
	methods: {
		//添加文本数据
		submit(res) {
			console.log(res);
			var content = res.detail.value.artText;
			if (content.length < 1) {
				uni.showToast({
					title: '请输入内容',
					icon: 'none'
				});
				return;
			}
			this.artList.push({ type: 'text', content: content });
			console.log(this.artList);
			this.inputContent = '';
		},
		//选择分类
		cateChange(e) {
			console.log(e);
			//获取用户选中的索引
			var sedIndex = e.detail.value;
			this.currentCateIndex = sedIndex;
			if (sedIndex < 1) {
				return;
			}
			var cateName = this.caties[sedIndex];
			for (var k in this.catiesFromApi) {
				if (cateName == this.catiesFromApi[k]) {
					this.sedCateIndex = k;
				}
			}
			this.currentCateIndex = sedIndex;
		},
		//添加图片
		addImg() {
			var _self = this;
			uni.chooseImage({
				count: 1, //一次上传一张
				sizeType: ['compressed'], //压缩
				success: function(res) {
					_self.artList.push({ type: 'image', content: res.tempFilePaths[0] });
				}
			});
		},
		//删除图片
		removeImg(e) {
			var _self = this;
			var sedIndex = e.target.dataset.index;
			uni.showModal({
				content: '确定要删除此图片吗',
				title: '提示',
				success(e) {
					if (e.confirm) {
						_self.artList.splice(sedIndex, 1);
					}
				}
			});
		},
		deleteText(e) {
			var _self = this;
			var sedIndex = e.target.dataset.index;
			uni.showModal({
				content: '确定要删除此文本吗',
				title: '提示',
				success(e) {
					if (e.confirm) {
						_self.artList.splice(sedIndex, 1);
					}
				}
			});
		},
		submitNow() {
			//检查提交数据是否符合规则
			if (this.title.length < 2) {
				uni.showToast({
					title: '请输入标题',
					icon: 'none'
				});
				return;
			}
			if (this.artList.length < 1) {
				uni.showToast({
					title: '请填写文章内容',
					icon: 'none'
				});
				return;
			}
			if (this.sedCateIndex < 1) {
				uni.showToast({
					title: '请选择分类',
					icon: 'none'
				});
				return;
			}
			//整理需要上传的图片
			this.needUploadImg = [];
			for (let i = 0; i < this.artList.length; i++) {
				if (this.artList[i].type == 'image') {
					this.needUploadImg.push({ tmpurl: this.artList[i].content, indexID: i });
				}
			}
			this.uploadImg();
		},
		uploadImg() {
			var sign = uni.getStorageSync('sign');
			_self = this;
			if (this.needUploadImg.length < 1 || this.uploadIndex >= this.needUploadImg.length) {
				uni.showLoading({
					title: '正在提交'
				});

				uni.request({
					url: _self.apiServer + 'art&m=add',
					method: 'POST',
					header: { 'content-type': 'application/x-www-form-urlencoded' },
					data: {
						title: _self.title,
						content: JSON.stringify(_self.artList),
						uid: loginRes[0],
						random: loginRes[1],
						cate: _self.sedCateIndex,
						sign: sign
					},
					success: res => {
						console.log(res);
						if (res.data.status == 'ok') {
							uni.showToast({ title: '提交成功', icon: 'none' });
							_self.artList = [];
							// 清空数据
							signModel.sign(_self.apiServer);
							// 防止数据缓存
							_self.currentCateIndex = 0;
							_self.sedCateIndex = 0;
							_self.needUploadImg = [];
							_self.title = '';
							setTimeout(function() {
								uni.switchTab({
									url: '../my/my'
								});
							}, 1000);
						} else {
							uni.showToast({ title: res.data.data, icon: 'none' });
						}
					},
					fail: () => {},
					complete: () => {}
				});
			} else {
				uni.showLoading({
					title: '图片上传中'
				});
				//将本地临时图片上传到服务器
				var uploader = uni.uploadFile({
					url: _self.apiServer + 'uploadImg&m=index',
					filePath: _self.needUploadImg[_self.uploadIndex].tmpurl,
					name: 'file',
					success(uploadFileRes) {
						console.log(uploadFileRes.data);
						uploadFileRes = JSON.parse(uploadFileRes.data);
						if (uploadFileRes.status != 'ok') {
							uni.showToast({
								title: '图片上传失败，请重试',
								icon: 'none'
							});
							return false;
						}
						//将已上传的文件地址赋值给文章数据
						var index = _self.needUploadImg[_self.uploadIndex].indexID;
						_self.artList[index].content = _self.staticServer + uploadFileRes.data;
						_self.uploadIndex++;
						setTimeout(function() {
							uni.hideLoading();
							_self.uploadImg();
						}, 1000);
					}
				});
			}
		}
	}
};
</script>

<style></style>
