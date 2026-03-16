<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav plr30 ptb10" @click="onBuy()">
				<view class="btn2">立即购买</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="bg-white pt30 plr30 pb40">
				<swiper class="swiper" circular :autoplay="true" :interval="3000" :duration="1000">
					<swiper-item>
						<image :src="detail.image_text" mode="aspectFill" class="banner"></image>
					</swiper-item>
				</swiper>
				<view class="mt30 fs36 fwb col1">{{detail.name}}</view>
				<view class="mt30 fs36 col4">
					<text class="fs24">¥</text>
					<block v-if="detail.is_vip.status != 1">
						<text>{{detail.salesprice}}</text>
						<text class="pl20 col7 fs30">VIP价¥{{detail.vip_price}}</text>
					</block>
					<block v-else>
						<text>{{detail.vip_price}}</text>
						<text class="pl20 col1 fs30 tdl">非VIP价¥{{detail.salesprice}}</text>
					</block>
				</view>
			</view>
			<view class="ptb40 plr30">
				<view class="title">套餐明细</view>
				<view class="box mt30">
					<view class="flex-box name">
						<view class="flex-grow-1 fs30 col1 m-ellipsis">{{detail.name}}</view>

					</view>
					<view class="item" v-for="(item,index) in detail.package_service2" :key="index">
						<view class="flex-box">
							<view class="flex-grow-1 pr30 m-ellipsis">{{item.service.name}}</view>
							<view>{{item.use_count}}次</view>
						</view>
						<view class="fs24 col5 mt15">规格：{{item.service_price.title}}</view>
					</view>
				</view>
				<view class="title mt40">商品详情</view>
				<view class="box1">
					<!-- 富文本 -->
					<u-parse :content="detail.content"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id: 0,
				shopId: 0,
				detail: {
					name: '',
					is_vip: {
						status: '',
						name: ''
					}
				}
			}
		},
		onLoad(options) {
			this.id = options.id;
			this.shopId = options.shop_id;
			this.fetchDetail();
		},
		methods: {
			fetchDetail() {
				this.$core.get({
					url: 'xiluxc.shop/package_detail',
					data: {
						id: this.id,
						shop_id: this.shopId,
					},
					loading: false,
					success: (ret) => {
						this.detail = ret.data;
					}
				})
			},
			onBuy() {
				if(!this.$core.getUserinfo(true)){
					return;
				}
				let param = {
					type: 'package',
					shop_id: this.shopId,
					package_id: this.detail.id
				};
				// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/pay_order/pay_order?param=' + encodeURIComponent(JSON.stringify(param))
				})
				// #endif
				// #ifdef H5
					this.$core.checkH5Openid('pages/pay_order/pay_order',encodeURIComponent(JSON.stringify(param)))
				// #endif
			}
		}
	}
</script>

<style lang="scss" scoped>
	.swiper {
		width: 690rpx;
		height: 350rpx;

		.banner {
			display: block;
			width: 100%;
			height: 100%;
			border-radius: 20rpx;

		}
	}

	.box {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		padding: 40rpx 30rpx 40rpx 40rpx;
		font-size: 30rpx;
		color: #101010;
		line-height: 30rpx;

		.item {
			margin-top: 30rpx;
		}
	}

	.box1 {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-top: 30rpx;
		padding: 25rpx 20rpx;
	}

	.page-foot {
		box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
	}

	.name {
		border-bottom: 1px solid #EEEEEE;
		padding-bottom: 30rpx;
	}
</style>