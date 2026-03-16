<template>
	<view>
		<view class="container">
			<view class="p30">
				<view class="item" v-for="(item,index) in shopAccountList" :key="index">
					<view class="view">
						<view class="stores_info flex-box">
							<image :src="item.brand?item.brand.logo:item.shop.image_text" mode="aspectFill" class="cover"></image>
							<view class="flex-grow-1 plr10 fs30 col1">{{item.brand?item.brand.brand_name:item.shop.name}}</view>
							<image v-if="!item.brand" @click="onLocation(item.shop)" src="@/static/icon/icon_address2.png" mode="aspectFill" class="ico30"></image>
						</view>
						<view class="money_box flex-box">
							<view class="flex-grow-1 fs24">
								<view class="colf  lh40">
									<text>¥</text>
									<text class="fs40">{{item.money}}</text>
								</view>
								<view class="mt15 colf_8">我的余额</view>
							</view>
							<view class="btn_pay" @click="onRecharge(item.shop_id)">充值</view>
						</view>
					</view>
				</view>
				
				<view class="nothing" v-if="shopAccountMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchShopAccount">{{shopAccountMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				shopAccountList: [],
				shopAccountMore: {page: 1}
			}
		},
		onLoad(options) {
			this.fetchShopAccount();
			
			uni.$on("shopRecharge",this.eventShopRecharge)
		},
		onReachBottom() {
			this.fetchShopAccount();
		},
		onUnload() {
			uni.$off("shopRecharge",this.eventShopRecharge)
		},
		methods: {
			fetchShopAccount(){
				this.$util.fetch(this, 'xiluxc.user/my_shop_account', {
					pagesize: 10
				}, 'shopAccountMore', 'shopAccountList', 'data', data => {
							
				})
			},
			
			//充值
			onRecharge(id,shopId){
				// uni.navigateTo({
				// 	url: '/pages/recharge/recharge?id='+id
				// })
				// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/recharge/recharge?id=' + id
				})
				// #endif
				// #ifdef H5
					this.$core.checkH5Openid('pages/recharge/recharge?id='+id)
				// #endif
			},
			//导航
			onLocation(shop){
				uni.openLocation({
					latitude: Number(shop.lat),
					longitude: Number(shop.lng),
					name: shop.name,
					address: shop.address
				})
			},
			eventShopRecharge(){
				this.shopAccountList = [];
				this.shopAccountMore = {page: 1};
				this.fetchShopAccount();
			},
		}
	}
</script>

<style lang="scss" scoped>
	.item {
		width: 100%;
		height: 250rpx;
		background: rgba(254, 76, 1, 0.2);
		border-radius: 20rpx;
		position: relative;

		&+& {
			margin-top: 40rpx;
		}

		&::after {
			content: '';
			width: 690rpx;
			height: 170rpx;
			background: linear-gradient(180deg, #FF8202 0%, #FE4B01 100%);
			border-radius: 20rpx;
			position: absolute;
			left: 0;
			right: 0;
			bottom: 0;
			z-index: 2;
		}

		.view {
			position: relative;
			width: 100%;
			height: 170rpx;
			z-index: 3;
		}

		.stores_info {
			height: 80rpx;
			padding: 0 30rpx;

			.cover {
				width: 35rpx;
				height: 35rpx;
				border-radius: 50%;
			}
		}

		.money_box {
			width: 690rpx;
			height: 170rpx;
			padding: 0 40rpx;

			.btn_pay {
				font-size: 30rpx;
				color: #FE4B01;
				width: 180rpx;
				height: 85rpx;
				line-height: 85rpx;
				text-align: center;
				background: rgba(255, 255, 255, 0.8);
				border-radius: 43rpx;
			}
		}
	}
</style>