<template>
	<view>
		<view class="container bg-f5">
			<hx-navbar ref="hxnb" :config="config">
				<view class="w100 flex-box" slot="max">
					<image :src="shop.brand?shop.brand.logo:shop.shop.image_text" mode="aspectFill" class="stores_img"></image>
					<view class="fwb fs36 col1 pl10">{{shop.brand?shop.brand.brand_name:shop.shop.name}}</view>
				</view>
			</hx-navbar>
			<view class="p30">
				<view class="fs34 col1 fwb pb10">余额明细</view>
				<view class="item" v-for="(log,index) in moneyLogList" :key="index">
					<view class="flex-box">
						<view class="flex-grow-1 fs30 col1">{{log.memo}}</view>
						<view class="fs24 col89">{{log.createtime_text}}</view>
					</view>
					<view class="flex-box mt30 fs24">
						<view class="add flex-grow-1" v-if="log.money>0">+¥<text class="fs30">{{log.money_abs}}</text></view>
						<view class="del flex-grow-1" v-else>-¥<text class="fs30">{{log.money_abs}}</text></view>
						<view class="col5">订单号{{log.extra_text.order_no}}</view>
					</view>
				</view>
				<!-- <view class="item">
					<view class="flex-box">
						<view class="flex-grow-1 fs30 col1">余额支出</view>
						<view class="fs24 col89">2024.10.25 16:20</view>
					</view>
					<view class="flex-box mt30 fs24">
						<view class="del flex-grow-1">+¥<text class="fs30">1000</text></view>
						<view class="col5">订单号819898918918</view>
					</view>
				</view> -->
				
				<view class="nothing" v-if="moneyLogMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{moneyLogMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				config: {
					color: '#101010',
					maxSlot: true,
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
				},
				shopId: 0,
				shop: {
					shop:{
						name: '',
						image_text: ''
					},
					brand: null
				},
				moneyLogList: [],
				moneyLogMore: {page: 1}
			}
		},
		onLoad(options) {
			this.shopId = options.shop_id || 0;
			this.fetchShopAccount();
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetchShopAccount(){
				this.$core.post({url: 'xiluxc.user/shop_account',data: {shop_id: this.shopId},loading: false,success: (ret) => {
						this.shop = ret.data;
					}
				})
			},
			fetch() {
				this.$util.fetch(this, 'xiluxc.user/money_log', {
					pagesize: 10,
					type: 1,
					shop_id: this.shopId
				}, 'moneyLogMore', 'moneyLogList', 'data', data => {
			
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.w100 {
		width: 100%;
	}

	.stores_img {
		width: 35rpx;
		height: 35rpx;
		border-radius: 50%;
	}

	.item {
		margin-top: 20rpx;
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		padding: 30rpx;
	}
	.add{
		color: #FE4B01;
	}
	.del{
		color: #06B89B;
	}
	/* #ifndef MP */
	::v-deep .hx-navbar {
		&__content {
			&__main {
				&_center {
					padding-left: 80rpx;
				}
			}
		}
	}
	
	/* #endif */
</style>