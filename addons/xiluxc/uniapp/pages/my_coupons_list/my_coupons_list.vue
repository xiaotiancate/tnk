<template>
	<view>
		<view class="container flex flex-col">
			<view class="tab_nav">
				<view class="tab_item" :class="[nindex==item.id?'active':'']" @click="chooseNav(item.id)"
					v-for="(item,index) in tab">{{item.name}}</view>
			</view>
			<scroll-view scroll-y="true" class="flex-grow-1 pt20 plr30">
				<view class="li" v-for="(shop,index) in couponList" :key="index">
					<view class="flex-box">
						<image :src="shop.image_text" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl10 fs30 col1">{{shop.name}}</view>
						<image src="@/static/icon/icon_address.png" mode="aspectFill" class="ico30"></image>
					</view>
					<view class="coupons_item" :class="(item.state==1?'':'uc')" v-for="(item,index2) in shop.coupon" :key="index2">
						<image v-if="item.state==1" src="@/static/images/icon_coupons_large.png" mode="aspectFill" class="bg"></image>
						<image v-else src="@/static/images/icon_coupons_large_uc.png" mode="aspectFill" class="bg"></image>
						<view class="view flex-box">
							<view class="left pt40 tc">
								<view class="fs32 colf lh74">¥<text class="fs74 fwb lh74">{{item.coupon.money}}</text></view>
								<view class="mt10 fs24 colf_8 lh24">满{{item.coupon.at_least}}可用</view>
							</view>
							<view class="flex-grow-1 pl40 flex-box pr30">
								<view class="flex-grow-1 colf fs24 lh24">
									<view class="fs30 fwb lh30">{{item.coupon.name}}</view>
									<view class="mt15">限该店铺所有商品可用</view>
									<view class="mt10 colf_8">{{item.coupon.use_end_time_text}}到期</view>
								</view>
								<view class="btn6" v-if="item.state==1" @click="onShopDetail(shop.id)">去使用</view>
								<view class="btn6" v-else-if="item.state==2">已使用</view>
								<view class="btn6" v-else>已过期</view>
							</view>
						</view>
					</view>
				</view>
				
				<view class="nothing" v-if="couponMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{couponMore.text}}</view>
				</view>
				<view class="pt30"></view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				tab: [{id:1,name:'待使用'},{id:2,name:'已使用'},{id:3,name:'已过期'}],
				nindex: 1,
				couponList: [],
				couponMore: {page:1}
			}
		},
		onLoad() {
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			chooseNav(index) {
				this.nindex = index;
				this.refresh();
			},
			refresh(){
				this.couponList = [];
				this.couponMore = {page:1};
				this.fetch();
			},
			fetch(){
				this.$util.fetch(this, 'xiluxc.coupon/mycoupons', {tab: this.nindex,pagesize:10}, 'couponMore', 'couponList', 'data', data=>{
				
				})
			},
			onShopDetail(id){
				uni.navigateTo({
					url: '/pages/stores_info/stores_info?id='+id
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {

		min-height: auto;
		/* #ifdef MP*/
		height: 100vh;
		/* #endif*/
		/* #ifndef MP */
		height: calc(100vh - 44px);
		/* #endif */
	}

	.tab_nav {
		height: 90rpx;
		background: #fff;
		white-space: nowrap;

		.tab_item {
			height: 90rpx;
			line-height: 90rpx;
			display: inline-block;
			vertical-align: top;
			font-size: 30rpx;
			color: #555555;
			margin-left: 200rpx;

			&:first-child {
				margin-left: 40rpx;
			}

			&:last-child {
				margin-right: 40rpx;
			}

			&.active {
				font-weight: bold;
				font-size: 30rpx;
				color: #FE4B01;
			}
		}
	}

	.li {
		&+& {
			margin-top: 50rpx;
		}

		.cover {
			width: 35rpx;
			height: 35rpx;
			border-radius: 50%;
		}

		.coupons_item {
			margin-top: 20rpx;
			width: 100%;
			height: 190rpx;
			position: relative;

			.bg {
				width: 100%;
				height: 190rpx;
				position: absolute;
				top: 0;
				left: 0;
				z-index: 1;
			}

			.view {
				width: 100%;
				height: 190rpx;
				position: relative;
				z-index: 2;
			}

			.left {
				width: 185rpx;
				height: 190rpx;
			}

			&.uc {
				.colf {
					color: #CCCCCC;
				}

				.colf_8 {
					color: #CCCCCC;
				}

				.btn6 {
					background: #DDDDDD;
					color: #FFFFFF;
				}
			}
		}
	}

	.lh74 {
		line-height: 74rpx;
		height: 74rpx;
	}

	.fs74 {
		font-size: 74rpx;
	}
</style>