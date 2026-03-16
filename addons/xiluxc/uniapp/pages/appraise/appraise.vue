<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="appraise_item" v-for="(comment,index) in commentList" :key="index">
					<view class="flex-box">
						<image :src="comment.user.avatar" mode="aspectFill" class="user_img"></image>
						<view class="flex-grow-1 plr25">
							<view class="fs30 col1 lh30 m-ellipsis">{{comment.user.nickname}}</view>
							<view class="mt15 fs24 col89 lh24">{{comment.createtime_text}}</view>
						</view>
						<view class="flex-box">
							<htz-rate readonly v-model="comment.avg_star" size="26" gutter="4" disHref="../../static/icon/icon_star_uc.png"
								checkedHref="../../static/icon/icon_star.png"></htz-rate>
								<view class="col2 fs24 pl10">{{comment.avg_star}}分</view>
							<!-- <image src="@/static/icon/icon_star.png" v-for="(item,index) in parseInt(comment.avg_star)" mode="widthFix"
								class="star"></image> -->
						</view>
					</view>
					<view class="mt25 fs30 col1 lh42">{{comment.content}}</view>
					<view class="pt10" v-if="comment.images_text.length>0">
						<image :src="img" mode="aspectFill" class="cover"
							v-for="(img,index2) in comment.images_text" @click="imgPrev(index,index2)"></image>
							
					</view>
				</view>
				
				<view class="nothing" v-if="commentMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchComment">{{commentMore.text}}</view>
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
				commentMore: {page: 1},
				shopId:null
			}
		},
		onLoad(options) {
			this.shopId = options.id || 0;
			this.fetchComment()
		},
		onReachBottom() {
			this.fetchComment();
		},
		methods: {
			fetchComment(){
				this.$util.fetch(this, 'xiluxc.comment', {shop_id: this.shopId,pagesize:10}, 'commentMore', 'commentList', 'data', data=>{
				
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
.appraise_item {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-bottom: 30rpx;
		padding: 30rpx;

		.user_img {
			width: 75rpx;
			height: 75rpx;
			border-radius: 50%;
		}

		.star {
			width: 26rpx;
			height: 26rpx;
			margin-left: 4rpx;
		}

		.cover {
			width: 200rpx;
			height: 200rpx;
			border-radius: 15rpx;
			margin-top: 15rpx;
		}

		.cover+.cover {
			margin-left: 15rpx;
		}
	}
</style>
