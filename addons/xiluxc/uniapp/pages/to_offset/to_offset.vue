<template>
	<view>
		<view class="container">
			<view class="p30">
				<view class="fwb fs30 col1">{{order.shop?order.shop.name: '门店已注销'}}</view>
				<view class="flex-box mt25">
					<image :src="order.order_item.image" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs30 fwb col1 lh30">{{order.order_item.title}}</view>
						<view class="mt20 fs24 col4 lh24">预约时间 {{order.appoint_date_text}}</view>
						<view class="mt45 fs24 lh30 col10">¥<text class="fs30">{{order.pay_fee}}</text></view>
					</view>
				</view>
				<view class="mt40 fwb fs30 col1 lh30">服务项</view>
				<view class="serve_item flex-box" >
					<view class="flex-grow-1 pr20 m-ellipsis">{{order.order_item.sku_text}}</view>
					<!-- <view class="pr30">剩余2</view> -->
					<view class="bt1" @click="toggleConfirm()">核销</view>
				</view>
				
			</view>
		</view>
		<u-popup :show="show" bgColor="transparent" mode="center" close="close">
			<view class="popup">
				<view class="fs30 col0">提示</view>
				<view class="col0 fs40 lh50 fwb mt80">确定“{{order.order_item.title}}（{{order.order_item.sku_text}}）”</view>
				<view class="col0 fs40 lh50 fwb">核销次数-1</view>
				<view class="flex-box flex-center mt80">
					<view class="pop_btn1" @click="toggleCancel()">取消</view>
					<view class="pop_btn2 ml30" @click="onSave()">确定</view>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				token: '',
				show: false,
				order:{
					appoint_date_text: '',
					pay_fee: '0.00',
					shop:{
						name:''
					},
					order_item:{
						title: '',
						image: '',
						sku_text:''
					}
				},
				qrcode:{
					
				}
			}
		},
		onLoad(options) {
			this.token = options.scene;
			this.fetchOrder();
		},
		methods: {
			fetchOrder(){
				this.$core.post({url:'xiluxc.order/order_confirm',data:{token:this.token},success:(ret)=>{
					this.order = ret.data.order;
					this.qrcode = ret.data.qrcode;
				},fail:(ret)=>{
					uni.showModal({
						title:'提示',
						content: ret.msg,
						success() {
							if(getCurrentPages().length == 1){
								uni.switchTab({
									url: '/pages/index/index'
								})
							}else{
								uni.navigateBack({})
							}
						}
					})
					return false;
				}
				});
			},
			toggleConfirm(){
				this.show = true;
			},
			toggleCancel() {
				this.show = false
			},
			//确认核销
			onSave(){
				let page = this;
				page.$core.post({url:'xiluxc.order/verifier_order',data:{token:page.token},success:(ret)=>{
					uni.showModal({
						title: '提示',
						content: '核销成功',
						showCancel: false,
						success() {
							uni.switchTab({
								url: '/pages/profile/profile'
							})
						}
					})
				},fail:(ret)=>{
					uni.showModal({
						title: '提示',
						content: ret.msg,
						showCancel: false,
						success() {
							uni.switchTab({
								url: '/pages/profile/profile'
							})
						}
					})
					return false;
				}
				});
				
			},
		}
	}
</script>

<style lang="scss" scoped>
	.cover {
		width: 150rpx;
		height: 150rpx;
		border-radius: 15rpx;
	}

	.serve_item {
		width: 690rpx;
		height: 100rpx;
		margin-top: 30rpx;
		padding: 0 30rpx;
		font-size: 30rpx;
		color: #000000;
		font-weight: bold;
		background: #F5F7FB;
		border-radius: 15rpx;

		.bt1 {
			width: 100rpx;
			height: 60rpx;
			line-height: 60rpx;
			text-align: center;
			background: #FE4B01;
			border-radius: 30rpx;
			font-size: 24rpx;
			color: #FFFFFF;
		}
	}

	.offset_item {
		padding: 30rpx 0;
		border-bottom: 1px solid #EEEEEE;

		.user_img {
			width: 30rpx;
			height: 30rpx;
			border-radius: 50%;
		}
	}

	.popup {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		padding: 30rpx 30rpx 50rpx;
		text-align: center;
	}
	.mt80{margin-top: 80rpx;}
	.pop_btn1,.pop_btn2{
		width: 260rpx;
		height: 90rpx;
		border-radius: 55rpx;
		line-height: 90rpx;
		font-size: 30rpx;
	}
	.pop_btn1{
		background: #F5F7FB;
		color: #555555;
	}
	.pop_btn2{
		background: #FE4B01;
		color: #FFFFFF;
	}
</style>