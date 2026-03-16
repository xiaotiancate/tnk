<template>
	<view>
		<view class="page-foot bg-white">
			<view class="ptb10 plr30" @click="onAdvice()">
				<view class="btn2">填写我的投诉</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item" v-for="(item,index) in adviceList" :key="index">
					<view class="flex-box fs30 col1">
						<image src="@/static/icon/icon_suggestion.png" mode="aspectFill" class="ico40"></image>
						<view class="flex-grow-1 plr15 fwb">投诉建议</view>
						<view class="col89 fs24">{{item.createtime_text}}</view>
					</view>
					<view class="mt25 fs30 col1 lh42">{{item.content}}</view>
					<view v-if="item.images_text.length>0">
						<image :src="img" v-for="(img,index2) in item.images_text" :key="index" @click="onPreviewImage(item.images_text,index2)" mode="aspectFill" class="imgs"></image>
					</view>
					<view class="col4 mt30 fs30">已提交</view>
					<view class="mt30 flex-box" v-if="item.reply_status==1">
						<view class="flex-grow-1 col4 fs30 fwb">平台回复</view>
						<view class="fs24 col89">{{item.replytime_text}}</view>
					</view>
					<view v-if="item.reply_status==1" class="mt25 fs30 col1 lh42">{{item.reply_content}}</view>
				</view>
				
				<view class="nothing" v-if="adviceMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchAdvice">{{adviceMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				adviceList: [],
				adviceMore: {page: 1}
			}
		},
		onLoad() {
			this.fetchAdvice();
		},
		onReachBottom() {
			this.fetchAdvice();
		},
		methods: {
			refresh(){
				this.adviceList = [];
				this.adviceMore = {page: 1};
				this.fetchAdvice();
			},
			fetchAdvice() {
				this.$util.fetch(this, 'xiluxc.user/myadvice', {
					pagesize: 10
				}, 'adviceMore', 'adviceList', 'data', data => {
			
				})
			},
			onAdvice(){
				uni.navigateTo({
					url: "/pages/write_complaint/write_complaint",
					events:{
						adviceSuccess: data=>{
							this.refresh();
						}
					},
				})
			},
			onPreviewImage(images,index){
				uni.previewImage({
					urls: images,
					current: index
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.item {
		width: 690rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 20rpx;

		&+& {
			margin-top: 30rpx;
		}

		.imgs {
			width: 200rpx;
			height: 200rpx;
			border-radius: 10rpx;
			display: inline-block;
			vertical-align: top;
			margin-right: 15rpx;
			margin-top: 15rpx;
			&:nth-of-type(3n){margin-right: 0;}
			&:nth-of-type(-n+3){margin-top: 25rpx;}
		}
	}
</style>