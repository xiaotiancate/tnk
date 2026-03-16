<template>
	<view>
		<view class="container">
			<view class="p30">
				<view class="fwb fs30 col1">{{order.shop?order.shop.name: '门店已注销'}}</view>
				<view class="flex-box mt25">
					<image :src="order.package_image" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs30 fwb col1 lh30">{{order.package_name}}</view>
						<view class="mt20 fs24 col4 lh24"></view>
						<view class="mt45 fs24 lh30 col10">¥<text class="fs30">{{order.pay_fee}}</text></view>
					</view>
				</view>
				<view class="mt40 fwb fs30 col1 lh30">服务项</view>
				<view class="serve_item flex-box" v-for="(item,index) in order.package_service">
					<view class="flex-grow-1 pr20 m-ellipsis">{{item.service_name}}（{{item.service_price_name}}）</view>
					<view class="pr30">剩余{{item.stock}}</view>
					<view class="bt1" @click="onVerify(index)">核销</view>
				</view>
				<view class="mt50 fs34 fwb col1 pb10">核销记录</view>
				<view class="offset_item" v-for="(item,index) in order.verify_ist">
					<view class="flex-box fs30 col1 lh30">
						<view class="flex-grow-1 pr30 m-ellipsis">{{item.ordering.order_item.title}}（{{item.ordering.order_item.sku_text}}）</view>
						<view>-1</view>
					</view>
					<view class="mt20 flex-box fs24 col5">
						<image :src="item.user.avatar" mode="aspectFill" class="user_img"></image>
						<view class="flex-grow-1 plr10 m-ellipsis">{{item.user.nickname}}</view>
						<view class="col89">{{item.verifytime_text}}</view>
					</view>
				</view>
			</view>
		</view>
		<u-popup :show="show" bgColor="transparent" mode="center" close="close">
			<view class="popup">
				<view class="fs30 col0">提示</view>
				<view class="col0 fs40 lh50 fwb mt80">确定“{{packageService?packageService.service_name:''}}（{{packageService?packageService.service_price_name:''}}）”</view>
				<view class="col0 fs40 lh50 fwb">核销次数-1</view>
				<view class="flex-box flex-center mt80">
					<view class="pop_btn1" @click="close()">取消</view>
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
				order: {
					shop:{
						name:'',
						address:''
					},
					package_service:[]
				},
				packageService: null
			}
		},
		onLoad(options) {
			this.token = options.scene;
			this.fetchPackage();
		},
		methods: {
			fetchPackage(){
				this.$core.post({url:'xiluxc.user_package/package_confirm',data:{token:this.token},success:(ret)=>{
					this.order = ret.data;
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
			
			onVerify(index){
				this.packageService = this.order.package_service[index];
				this.show = true;
			},
			//确认核销
			onSave(){
				let page = this;
				console.log(this.packageService);
				page.$core.post({url:'xiluxc.user_package/verifier_package',data:{token:page.token,user_package_service_id: page.packageService.id},success:(ret)=>{
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
							// uni.switchTab({
							// 	url: '/pages/profile/profile'
							// })
						}
					})
					return false;
				}
				});
				
			},
			close() {
				this.show = false
			}
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