<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="fwb fs34 col1">我的评价</view>
				<view>
					<view class="item" v-for="(item,index) in commentList">
						<view class="flex-box">
							<image :src="item.shop.image_text" mode="aspectFill" class="user_img"></image>
							<view class="flex-grow-1 m-ellipsis plr10 fs30 col1">{{item.shop.name}}</view>
							<image v-for="(item,index) in parseInt(item.avg_star)" src="@/static/icon/icon_star.png" mode="aspectFill" class="ico26 ml5"></image>
							
						</view>
						<view class="flex-box mt15">
							<view class="flex-grow-1 fs24 col5">订单号 {{item.ordering.order_no}}</view>
							<view class="fs24 col89">{{item.createtime_text}}</view>
						</view>
						<view class="mt20 fs30 col1 lh42">{{item.content}}</view>
						<view class="pt10" v-if="item.images_text.length>0">
							<image :src="img" v-for="(img,index2) in item.images_text" @click="imgPrev(index,index2)" mode="aspectFill" class="img"></image>
							
						</view>
					</view>
					
					<view class="nothing" v-if="commentMore.nothing">
						<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
						<text>暂无内容</text>
					</view>
					<view class="g-btn3-wrap" v-else>
						<view class="g-btn3" @click="fetch">{{commentMore.text}}</view>
					</view>
					
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				commentList: [],
				commentMore: {page: 1}
			}
		},
		onLoad(options) {
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetch(){
				this.$util.fetch(this, 'xiluxc.comment/my_comment', {
					pagesize: 10
				}, 'commentMore', 'commentList', 'data', data => {
						
				})
			},
			imgPrev(index,index2){
				uni.previewImage({
					current: index2,
					urls: this.commentList[index].images_text
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.item {
		width: 690rpx;
		margin-top: 30rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;

		.user_img {
			width: 35rpx;
			height: 35rpx;
			border-radius: 50%;
		}
		.img{
			width: 200rpx;
			height: 200rpx;
			border-radius: 15rpx;
			display: inline-block;
			vertical-align: top;
			margin-top: 15rpx;
			margin-right: 15rpx;
			&:nth-of-type(3n){margin-right: 0;}
		}
	}
</style>