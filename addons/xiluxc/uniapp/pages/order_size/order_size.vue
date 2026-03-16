<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav flex-box flex-end plr30" v-if="order.state==1" @click="onComment()">
				<view class="btn1">立即评价</view>
			</view>
			<view class="foot_nav flex-box flex-end plr30" v-if="order.state==0" @click="onRefund()">
				<view class="btn_cancel">取消</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="box1">
					<view class="fwb fs30 col1">{{order.shop.name}}</view>
					<view class="mt25 flex-box">
						<image :src="order.order_item.image" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl30">
							<view class="m-ellipsis fs30 col1 fwb lh30">{{order.order_item.title}}</view>
							<view class="fs24 lh24 col4 mt20">预约时间 {{order.appoint_date_text}}</view>
							<view class="flex flex-align-end mt10">
								<view class="col10 fs24 lh30 flex-grow-1">¥<text class="fs30">{{order.pay_fee}}</text></view>
								<view class="h60">
									<view class="refund" v-if="false">我要退款</view>
								</view>
							</view>
						</view>
					</view>
					<view class="guide_item mt30 flex-box">
						<view class="flex-grow-1 pr30">
							<view class="fwb fs30 col1">{{order.shop.address}}</view>
							<view class="mt20 fs26 col89">距你{{order.shop.distance}}</view>
						</view>
						<view class="tc" @click="onLocation()">
							<image src="@/static/icon/icon_map.png" mode="aspectFill" class="ico40"></image>
							<view class="mt15 col89 fs24">导航</view>
						</view>
						<view class="tc ml50" @click="onCall()">
							<image src="@/static/icon/icon_phone.png" mode="aspectFill" class="ico40"></image>
							<view class="mt15 col89 fs24">电话</view>
						</view>
					</view>
				</view>

				<view class="box2 mt30" v-if="order.qrcode">
					<view class="fwb fs30 col1">核销券码</view>
					<!-- 蒙层 -->
					<view class="code">
						<view :class="{moudle: order.state!=0||order.qrcode.verifier_status==1}"></view>
						<image :src="order.qrcode.qrcode" mode="aspectFill" class="img"></image>
					</view>
					<view class="tc">
					<view class="number_box tl">
						券码<text class="pl30" :class="{tdl: order.state!=0 || order.qrcode.verifier_status==1}">{{order.qrcode.code}}</text>
					</view>
					</view>
				</view>
				<view class="box3 mt30 fs30 col1">
					<view class="fwb">服务项</view>
					<view class="flex-box mt40">
						<view class="flex-grow-1 pr30 m-ellipsis">·{{order.order_item.title}} · {{order.order_item.sku_text}}</view>
						<!-- col4 橙色 不加class黑色 -->
						<!-- <view class="col4">已使用5/5</view> -->
					</view>
				</view>
				<!-- <view class="box4 mt30 fs30 col1">
					<view class="fwb pb10">核销记录</view>
					<view class="h_item" v-for="(item,index) in 3">
						<view class="flex-box">
							<view class="flex-grow-1 m-ellipsis pr30">标准洗车（5座小型轿车）</view>
							<view>-1</view>
						</view>
						<view class="flex-box mt20">
							<view class="col89 fs24 flex-grow-1">2024.12.25 16:02</view>
							<view class="h60">
								<view class="btn_eva" v-if="index==1">评价</view>
								<view class="fs24 col1" v-else>已评价</view>

							</view>
						</view>
					</view>
				</view> -->
				<view class="box5 mt30">
					<view class="fwb fs30 col1">订单信息</view>
					<view class="mt50 flex-box fs30">
						<view class="flex-grow-1 pr30">订单号</view>
						<view>{{order.order_no}}</view>
					</view>
					<view class="mt50 flex-box fs30">
						<view class="flex-grow-1 pr30">商品金额</view>
						<view class="col4 fs24">¥<text class="fs30">{{order.order_amount}}</text></view>
					</view>
					<view class="mt50 flex-box fs30" v-if="order.coupon_discount_fee>0">
						<view class="flex-grow-1 pr30">优惠券</view>
						<view class="col4 fs24">-¥<text class="fs30">{{order.coupon_discount_fee}}</text></view>
					</view>
					<view class="mt50 flex-box fs30" v-if="order.points_fee>0">
						<view class="flex-grow-1 pr30">积分抵扣</view>
						<view class="col4 fs24">-¥<text class="fs30">{{order.points_fee}}</text></view>
					</view>
					<view class="mt50 flex-box fs30">
						<view class="flex-grow-1 pr30">支付方式</view>
						<view>{{order.pay_type_text}}</view>
					</view>
					<view class="mt50 flex-box fs30">
						<view class="flex-grow-1 pr30">支付时间</view>
						<view>{{order.paid_time_text}}</view>
					</view>
					<!-- <view class="mt50 flex-box flex-wrap fs30">
						<view class="flex-grow-1 pr30">备注</view>
						<view>暂无备注</view>
					</view> -->
					<view class="tr fs24 col4 mt50">
						<text class="col89">合计</text>
						<text class="pl10">¥</text>
						<text class="fs30">{{order.pay_fee}}</text>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	const app = getApp();
	export default {
		data() {
			return {
				orderId: 0,
				order: {
					shop:{
						name:'',
						address:''
					},
					order_item:{
						sku_text:'',
						title:'',
					},
					qrcode:{
						qrcode:'',
						code: ''
					},
					appoint_date_text:''
				}
			}
		},
		onLoad(options) {
			this.orderId = options.id;
			this.fetchDetail();
		},
		methods: {
			fetchDetail(){
				let currentCity = this.$core.getCurrentCity();
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude
				this.$core.get({url:'xiluxc.order/detail',data:{order_id: this.orderId,lat: lat,lng:lng},success:(ret)=>{
					this.order = ret.data;
					uni.setNavigationBarTitle({
						title: ret.data.state_text
					})
				}});
			},
			//导航
			onLocation(){
				uni.openLocation({
					latitude: Number(this.order.shop.lat),
					longitude: Number(this.order.shop.lng),
					name: this.order.shop.name,
					address: this.order.shop.address
				})
			},
			onCall(){
				let contactMobile = this.order.shop.concat_mobile;
				uni.makePhoneCall({
					phoneNumber: contactMobile
				})
			},
			onComment(){
				let order = this.order;
				uni.navigateTo({
					url: '/pages/rate/rate?id='+order.id,
					events:{
						commentSuccess: data=>{
							this.fetchDetail();
						}
					}
				})
			},
			onRefund(){
				let page = this;
				let order = this.order
				uni.showModal({
					title: '提示',
					content:"确定要取消服务？",
					success(res) {
						if(res.confirm){
							page.$core.post({url:'xiluxc.order/aftersale',data:{id: order.id},success:(ret)=>{
								uni.showToast({
									title: ret.msg,
									icon: 'none'
								})
								page.fetchDetail();
							},
							fail:ret=>{
								uni.showModal({
									title: '提示',
									content: ret.msg,
									showCancel:false
								})
								return false;
							}});
						}
					}
				})
				
			}
		}
	}
</script>

<style lang="scss" scoped>
	.box1 {
		width: 690rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;

		.cover {
			width: 150rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}

		.guide_item {
			width: 630rpx;
			min-height: 150rpx;
			background: #F5F7FB;
			background: #F5F7FB;
			border-radius: 15rpx;
			padding: 30rpx;
			

		}
	}

	.box2 {
		width: 690rpx;
		padding: 35rpx 30rpx 50rpx;
		background: #FFFFFF;
		border-radius: 15rpx;

		.code {
			width: 360rpx;
			height: 360rpx;
			display: block;
			margin-left: auto;
			margin-right: auto;
			margin-top: 30rpx;
			position: relative;

			.img {
				width: 360rpx;
				height: 360rpx;
				position: relative;
				z-index: 1;
			}

			.moudle {
				width: 360rpx;
				height: 360rpx;
				position: absolute;
				top: 0;
				left: 0;
				z-index: 2;
				background: rgba(0, 0, 0, 0.3);
			}
		}

		.number_box {
			min-width: 400rpx;
			max-width: 100%;
			display: inline-block;
			height: 88rpx;
			background: #F5F7FB;
			border-radius: 42rpx;
			margin: 30rpx auto 0;
			padding-left: 30rpx;
			padding-right: 30rpx;
			line-height: 88rpx;
			font-size: 30rpx;
			color: #101010;
		}
	}

	.box3 {
		width: 690rpx;
		padding: 30rpx 30rpx 40rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
	}

	.box4 {
		width: 690rpx;
		padding: 30rpx 30rpx 0;
		background: #FFFFFF;
		border-radius: 20rpx;

		.h_item {
			padding: 30rpx 0;

		}

		.h_item+.h_item {
			border-top: 1px solid #EEEEEE;
		}
	}

	.box5 {
		width: 690rpx;
		padding: 30rpx 30rpx 50rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
	}

	.foot_nav {
		width: 750rpx;
		height: 98rpx;
		background: #FFFFFF;
	}

	.h60 {
		height: 60rpx;
		line-height: 60rpx;
	}

	.btn_eva {
		width: 120rpx;
		height: 60rpx;
		line-height: 60rpx;
		text-align: center;
		font-size: 24rpx;
		color: #FFFFFF;
		background: #FE4B01;
		border-radius: 40rpx;
	}

	.h60 {
		height: 60rpx;
	}

	.refund {
		width: 140rpx;
		height: 60rpx;
		line-height: 60rpx;
		text-align: center;
		font-size: 24rpx;
		color: #FFFFFF;
		background: #FE4B01;
		border-radius: 33rpx;
	}
	.btn_cancel{
		width: 140rpx;
		height: 60rpx;
		line-height: calc(60rpx - 2px);
		text-align: center;
		font-size: 24rpx;
		border-radius: 33rpx;
		color: #999;
		border: 1px solid #999;
	}
</style>