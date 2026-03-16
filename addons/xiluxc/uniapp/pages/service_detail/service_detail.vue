<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav plr30 ptb10" @click="onBuy()">
				
				<view class="btn2"><text class="pr20">¥ {{detail.service_price.length>0?(detail.is_vip.status != 1?detail.service_price[nindex].salesprice:detail.service_price[nindex].vip_price):''}}</text>立即购买</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="bg-white pt30 plr30 pb40">
				<swiper class="swiper" circular :autoplay="true" :interval="3000" :duration="1000">
					<swiper-item >
						<image :src="detail.image_text" mode="aspectFill" class="banner"></image>
					</swiper-item>
				</swiper>
				<view class="mt30 fs36 fwb col1">{{detail.service?detail.service.name:''}}</view>
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
				<view class="title">规格</view>
				<view class="mt30 box">
					<view class="car_item flex-box flex-col" :class="[nindex==index?'active':'']"
						@click="chooseNav(index)" v-for="(item,index) in detail.service_price" :key="index">
						<view>{{item.title}}</view>
						<image src="@/static/icon/icon_true.png" v-if="nindex==index" mode="aspectFill"
							class="icon_true"></image>
					</view>
				</view>
				<view class="title mt30">商品详情</view>
				<view class="box1 fs32">
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
				id:0,
				shopId: 0,
				detail: {
					service_price: [],
					is_vip:{
						status: '',
						name:''
					}
				},
				nindex: 0
			}
		},
		onLoad(options) {
			this.id = options.id;
			this.shopId = options.shop_id;
			this.fetchDetail();
		},
		methods: {
			chooseNav(index) {
				this.nindex = index
			},
			fetchDetail(){
				this.$core.get({url: 'xiluxc.shop/service_detail',data: {id: this.id,shop_id: this.shopId},loading:false,success:(ret)=>{
					this.detail = ret.data;
				}})
			},
			onBuy(){
				if(!this.$core.getUserinfo(true)){
					return;
				}
				let nindex = this.nindex;
				let id = this.detail.service_price[nindex].id;
				let param = {
					type: 'service',
					shop_id: this.shopId,
					service_id: this.detail.id,
					service_price_id: id
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
.swiper{
	width: 690rpx;
	height: 350rpx;
	.banner{
		display: block;
		width: 100%;
		height: 100%;
		border-radius: 20rpx;
		
	}
}
.box{
	width: 690rpx;
	background: #FFFFFF;
	border-radius: 15rpx;
	padding: 40rpx 30rpx 30rpx;
	font-size: 30rpx;
	color: #101010;
	line-height: 30rpx;
	.item +.item{
		margin-top: 40rpx;
	}
}
.box1{
	width: 690rpx;
	background: #FFFFFF;
	border-radius: 15rpx;
	margin-top: 30rpx;
	padding: 25rpx 20rpx;
}
.page-foot{
	box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
	border-radius: 30rpx 30rpx 0rpx 0rpx;
}
.car_item {
			width: 305rpx;
			height: 100rpx;
			line-height: 100rpx;
			background: #F5F7FB;
			border-radius: 15rpx;
			padding: 0 20rpx;
			font-size: 30rpx;
			color: #101010;
			display: inline-flex;
			position: relative;
			vertical-align: top;
			border: 2rpx solid #F5F7FB;
			margin-right: 20rpx;
			margin-top: 20rpx;

			&:nth-of-type(2n) {
				margin-right: 0;
			}

			&:nth-of-type(-n+2) {
				margin-top: 0;
			}

			&.active {
				background: #FFFFFF;
				border-radius: 15rpx;
				border: 2rpx solid #FE4B01;
			}

			.icon {
				width: 130rpx;
				margin-bottom: 20rpx;
			}

			.icon_true {
				width: 35rpx;
				height: 35rpx;
				position: absolute;
				bottom: -2rpx;
				right: -2rpx;
			}
		}
</style>
