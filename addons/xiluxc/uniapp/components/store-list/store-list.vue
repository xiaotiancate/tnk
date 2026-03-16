<template>
	<view>
		<template>
			<view class="stores_item p30" v-for="(shop,index) in shopList">
				<view class="flex-box" @click="onShopDetail(shop.id)">
					<view class="cover">
						<image :src="shop.image_text" mode="aspectFill" class="cover"></image>
						<view class="tips" v-if="shop.type==2">
							<image src="@/static/icon/icon_liansuo.png" mode="aspectFill" class="icon_tips">
							</image>
							<view class="tips_text"> 连锁</view>
						</view>
					</view>
					<view class="flex-grow-1 pl20">
						<view class="flex flex-align-baseline lh36 col89 fs24">
							<view class="fs36 fwb col1 flex-grow-1 pr20 m-ellipsis">{{shop.name}}</view>
							<view>已售{{shop.sales}}</view>
						</view>
						<view class="flex-box mt20">
							<htz-rate readonly v-model="shop.point" size="26" gutter="4"
								disHref="../../static/icon/icon_star_uc.png"
								checkedHref="../../static/icon/icon_star.png"></htz-rate>
							<view class="flex-grow-1 pl20 col2 fs24">{{shop.point}}分</view>
							<view class="col89 fs26">{{shop.distance}}</view>
						</view>
						<view class="mt20 flex-box">
							<view class="flex-grow-1 m-ellipsis pr20 col5 fs26">{{shop.address}}</view>
							<image src="@/static/icon/icon_address.png" mode="aspectFill" class="ico30"></image>
						</view>
					</view>
		
				</view>
				<view class="stores_menu flex-box" v-if="type==1" v-for="(item,index3) in shop.shop_services"
					@click="onServiceDetail(item.shop_service.id,shop.id)">
					<image :src="item.shop_service.image_text" mode="aspectFill" class="mini_cover"></image>
					<view class="flex-grow-1 pl25 pr15">
						<view class="fs30 fwb col1 lh30">{{item.service.name}}</view>
						<view class="flex-box mt20">
							<block v-if="shop.is_vip && shop.is_vip.status != 1">
								<view class="fs24 col4 lh30">
									<text>¥</text>
									<text class="fs30">{{item.shop_service.salesprice}}</text>
								</view>
								<view class="fs24 col7 lh30 ml20">VIP价¥{{item.shop_service.vip_price}}</view>
							</block>
							<block v-else>
								<view class="fs24 col4 lh30">
									<text>¥</text>
									<text class="fs30">{{item.shop_service.vip_price}}</text>
								</view>
								<view class="fs24 col1 tdl lh30 ml20">¥{{item.shop_service.salesprice}}</view>
							</block>
						</view>
					</view>
		
					<view class="mini_btn1" @click.stop="onBuy(item.shop_service.id,shop.id)">购买</view>
				</view>
				<view class="stores_menu flex-box" v-if="type==2" v-for="(item,index3) in shop.shop_services" @click="onServiceDetail(item.shop_service.id,shop.id)">
					<view class="flex-grow-1 pr20">
						<view class="fs30 fwb col1 lh30 m-ellipsis">{{item.service.name}}</view>
						<!-- <view class="mt15 fs24 col89 lh24">适用于全部5座小型轿车</view> -->
						<view class="mt15 fs24 col89 lh24"></view>
					</view>
					<view class="tr">
						<block v-if="shop.is_vip && shop.is_vip.status != 1">
							<view class="fs24 col4 lh30">
								<text>¥</text>
								<text class="fs30">{{item.shop_service.salesprice}}</text>
							</view>
						<view class="fs24 col7 mt10">VIP价¥{{item.shop_service.vip_price}}</view>
						</block>
						<block v-else>
							<view class="fs24 col4 lh30">
								<text>¥</text>
								<text class="fs30">{{item.shop_service.vip_price}}</text>
							</view>
							<view class="fs24 col1 tdl mt10">非VIP价¥{{item.shop_service.salesprice}}</view>
						</block>
					</view>
					<view class="mini_btn1 ml30" @click.stop="onBuy(item.shop_service.id,shop.id)">购买</view>
				</view>
			</view>
		</template>
	</view>
</template>

<script>
	const app = getApp();
	import htzRate from '@/components/htz-rate/htz-rate.vue';
	export default {
	components: {
		htzRate
	},
		name: "company-list",
		props: {
			shopList: {
				type: Array,
				default: []
			},
			type: {
				type: Number,
				default: 1
			}
		},
		data() {
			return {
				
			};
		},
		methods: {
			//门店详情
			onShopDetail(id) {
				uni.navigateTo({
					url: '/pages/stores_info/stores_info?id=' + id
				})
			},
			onServiceDetail(id, shopId) {
				uni.navigateTo({
					url: '/pages/service_detail/service_detail?id=' + id + '&shop_id=' + shopId
				})
			},
			//购买
			onBuy(serviceId, shopId) {
				if (!this.$core.getUserinfo(true)) {
					return;
				}
				let param = {
					type: 'service',
					shop_id: shopId,
					service_id: serviceId,
					service_price_id: 0
				};
				// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/pay_order/pay_order?param=' + encodeURIComponent(JSON.stringify(param))
				})
				// #endif
				// #ifdef H5
					this.$core.checkH5Openid('pages/pay_order/pay_order',encodeURIComponent(JSON.stringify(param)))
				// #endif
			},
		}
	}
</script>

<style lang="scss" scoped>
	.stores_item {
		margin: 30rpx 30rpx 0;
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		border: 1px solid #EEEEEE;
	
		.cover {
			width: 180rpx;
			height: 150rpx;
			border-radius: 15rpx;
			position: relative;
	
			.tips {
				position: absolute;
				left: -5rpx;
				top: -5rpx;
				z-index: 2;
				width: 48rpx;
				height: 31rpx;
	
				.icon_tips {
					width: 48rpx;
					height: 31rpx;
					position: absolute;
					top: 0;
					left: 0;
					z-index: 1;
				}
	
				.tips_text {
					width: 48rpx;
					height: 31rpx;
					position: relative;
					z-index: 2;
					font-size: 18rpx;
					color: #FFFFFF;
					line-height: 31rpx;
					text-align: center;
				}
			}
		}
	
		.mini_cover {
			width: 109rpx;
			height: 90rpx;
			border-radius: 10rpx;
		}
	
		.star {
			width: 26rpx;
			height: 26rpx;
		}
	
		.star+.star {
			margin-left: 4rpx;
		}
	
		.stores_menu {
			margin-top: 25rpx;
			width: 630rpx;
			height: 120rpx;
			background: #F5F7FB;
			border-radius: 15rpx;
			padding: 0 30rpx;
		}
	}
	
</style>