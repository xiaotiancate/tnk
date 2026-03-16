<template>
	<view>
		<view class="page-foot">
			<view class="plr30 ptb10" v-if="rechargeList.length>0" @click="onSubmit">
				<view class="btn2">立即充值</view>
			</view>
		</view>
		<view class="container">
			<view class="p30">
				<view class="item">
					<view class="view">
						<view class="stores_info flex-box">
							<image :src="shop.brand?shop.brand.logo:shop.shop.image_text" mode="aspectFill" class="cover"></image>
							<view class="flex-grow-1 plr10 fs30 col1">{{shop.brand?shop.brand.brand_name:shop.shop.name}}</view>
							<image v-if="!shop.brand" src="@/static/icon/icon_address2.png" mode="aspectFill" class="ico30"></image>
						</view>
						<view class="money_box flex-box">
							<view class="flex-grow-1 fs24">
								<view class="colf  lh40">
									<text>¥</text>
									<text class="fs40">{{shop.account.money || '0.00'}}</text>
								</view>
								<view class="mt15 colf_8">我的余额</view>
							</view>
							<view class="btn_pay" @click="onMoneyLog()">余额明细</view>
						</view>
					</view>
				</view>
				<view class="fwb fs34 col1 fwb mt50">余额充值</view>
				<view>
					<view class="money_item tc" :class="[nindex==index?'active':'']" @click="choose(index)"
						v-for="(item,index) in rechargeList">
						<view class="give" v-if="item.extra_money>0">送{{item.extra_money}}</view>
						<view class="fs24"><text>¥</text><text class="fs34">{{item.money}}</text></view>
					</view>
				</view>
				<view class="mt30 fs30 col1">充值说明</view>
				<view class="mt30 fs28 col5">
					<!-- 富文本 -->
				</view>
				<view class="mt50 fs34 fwb col1">支付方式</view>
				<view class="pay_method flex-box">
					<image src="@/static/icon/wepay.png" mode="aspectFill" class="ico35"></image>
					<view class="pl15 fs30 col1 flex-grow-1">微信支付</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	// #ifdef H5
	var jweixin = require('@/xilu/jweixin.js');
	// #endif
	export default {
		data() {
			return {
				shopId: 0,
				shop: {
					account: {
						money: '0.00'
					},
					shop:{
						name: '',
						image_text: ''
					},
					brand: null
				},
				rechargeList: [],
				nindex: 0
			}
		},
		onLoad(options) {
			this.shopId = options.id || 0;
			this.fetchShopAccount();
			this.fetchRecharge();
		},
		methods: {
			fetchShopAccount(){
				this.$core.post({url: 'xiluxc.user/shop_account',data: {shop_id: this.shopId},loading: false,success: (ret) => {
						this.shop = ret.data;
					}
				})
			},
			fetchRecharge(){
				this.$core.get({url: 'xiluxc.recharge',data: {shop_id: this.shopId},loading: false,success: (ret) => {
						this.rechargeList = ret.data;
					}
				})
			},
			//余额明细
			onMoneyLog(){
				uni.navigateTo({
					url: '/pages/balance_details/balance_details?shop_id='+this.shopId
				})
			},
			choose(index) {
				this.nindex = index
			},
			
			onSubmit(){
				let rechargeList = this.rechargeList;
				let platform = 'wxmini'
				// #ifdef H5
					platform = 'wxoffical';
				// #endif
				let rechargeId = rechargeList[this.nindex].id || 0
				this.$core.post({url: 'xiluxc.recharge/create_order',data: {shop_id: this.shopId,platform:platform,recharge_id:rechargeId},success: (ret) => {
						this.payment(ret.data);
					}
				})
			},
			payment(order){
				let page = this;
				//#ifdef MP-WEIXIN
				this.$core.post({url:'xiluxc.pay/pay',data:{pay_type:1,order_id:order.id,platform:'wxmini',type:'recharge'},success:(ret)=>{
					let wxconfig =  ret.data;
					this.$core.payment(wxconfig,function(){
						uni.$emit("shopRecharge",{});
						page.fetchShopAccount();
					})
				}});
				//#endif
				//#ifdef H5
					let openid = this.$core.getCache('wx_openid') || '';
					this.$core.post({url:'xiluxc.pay/pay',data:{openid:openid,pay_type:1,order_id:order.id,type:'recharge',platform:'wxoffical'},success:(ret)=>{
						let wxconfig =  ret.data;
						jweixin.config({
							debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
							appId:wxconfig.appId, // 必填，公众号的唯一标识
							timestamp:wxconfig.timeStamp, // 必填，生成签名的时间戳
							nonceStr:wxconfig.nonceStr, // 必填，生成签名的随机串
							signature:wxconfig.paySign, // 必填，签名，见附录1
							jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
						});
						jweixin.ready(function() {
							jweixin.checkJsApi({
								jsApiList: ['chooseWXPay'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
								success: function(res) {
									uni.hideLoading();
								},
								fail:function(res) {
									uni.hideLoading()
								}
							});
							jweixin.chooseWXPay({
								timestamp: wxconfig.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
								nonceStr: wxconfig.nonceStr, // 支付签名随机串，不长于 32 位
								package: wxconfig.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
								signType: wxconfig.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
								paySign:wxconfig.paySign, // 支付签名
								success:function(res) {
									uni.$emit("shopRecharge",{});
									page.fetchShopAccount();
								},
								cancel: function(r) {
								   uni.showToast({
									title:'取消支付了',
									icon:'none',
									mask:true
								   })
								},
								fail:function(res) {
									uni.hideLoading()
									uni.showToast({
										title:'支付失败了',
										icon:'none',
										mask:true
									})
								}
							});
						});
						jweixin.error(function(res) {
							// uni.showToast({
							// 	icon: 'none',
							// 	title: '支付失败了',
							// 	duration: 4000
							// });
						});
					}});
				//#endif
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

	.money_item {
		margin-top: 46rpx;
		display: inline-block;
		vertical-align: top;
		width: 216rpx;
		height: 100rpx;
		line-height: 100rpx;
		background: #FFE2D7;
		border-radius: 15rpx;
		margin-right: 20rpx;
		position: relative;
		color: #101010;

		.give {
			position: absolute;
			top: -16rpx;
			left: 0;
			min-width: 70rpx;
			height: 32rpx;
			line-height: 32rpx;
			padding: 0 5rpx;
			font-size: 18rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 18rpx 18rpx 18rpx 0rpx;
			z-index: 2;
		}

		&:nth-of-type(3n) {
			margin-right: 0;
		}

		&.active {
			color: #FFFFFF;
			background: #FE4B01;

			.give {
				color: #FE4B01;
				background: #FFE2D7;
			}
		}
	}

	.pay_method {
		width: 690rpx;
		height: 110rpx;
		background: #F5F8FC;
		border-radius: 20rpx;
		margin-top: 30rpx;
		padding: 0 30rpx;
	}
	.page-foot{
		box-shadow: 0rpx -1rpx 1rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
	}
</style>