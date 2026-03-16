<template>
	<view>
		<view class="page-foot">
			<view class="p30">
				<view class="flex-box pb30">
					<image @click="onAgree()" :src="'/static/icon/choose_'+(agree?'sc':'uc')+'.png'" mode="aspectFill" class="ico34"></image>
					<view class="flex-grow-1 pl15 fs28 col5">我已阅读并同意
						<text class="col11" @click="onVipAgreement()">《会员购买协议》</text>
					</view>
				</view>
				<view class="btn4" @click="onSubmit()">确认购买</view>
			</view>
		</view>
		<view class="container bg-f5">
			<image src="@/static/images/bg3.png" mode="aspectFill" class="top_img" />
			<hx-navbar ref="hxnb" :config="config">
			</hx-navbar>
			<view class="pr z2 pt10 plr30 pb30">
				<view class="vip_box">
					<image src="@/static/images/vip_bg3.png" mode="aspectFill" class="vip_bg"></image>
					
					<view class="vip_view">
						<view class="col8 fs40 lh40 fwb">{{vip.name}}</view>
						<view class="mt15 fs22 lh30 col8_4" v-if="vip.my_state>0">卡号 {{vip.user_shop_vip.vip_no}}</view>
						<view class="pt15 fs22 h30 lh30 col8_4" v-else> </view>
						<view class="mt60 flex-box">
							<view class="flex-grow-1 col8 fs24 lh50">¥<text class="fs50">{{vip.salesprice}}</text></view>
							<view class="btn3" @click="onUseShops(vip.id)">可用门店</view>
							<!-- <view class="btn3" v-else-if="vip.my_state==1">立即续费</view>
							<view class="btn3" v-else-if="vip.my_state==2">立即续期</view> -->
						</view>
					</view>
				</view>
				<view class="mt40 fs34 fwb col1 lh34 pb10">会员权益</view>
				<view class="box">
					<view class="item flex" >
						<!-- <image src="@/static/icon/icon_vip1.png" mode="aspectFill" class="ico40"></image> -->
						<view class="flex-grow-1 pl20  fs24 col5">
							<!-- 富文本 -->
							<u-parse :content="vip.privilege"></u-parse>
						</view>
					</view>
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
				config: {
					color: '#101010',
					title: 'VIP会员',
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
					statusBarFontColor: ['#000000', '#ffffff']
				},
				vipId: 0,
				shopId: 0,
				vip: {
					id: 0,
					name: '',
					privilege: ''
				},
				agree: false
			}
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		onLoad(options) {
			this.vipId = options.id || 0;
			this.shopId = options.shop_id || 0;
			this.fetchVip();
		},
		methods: {
			fetchVip(){
				this.$core.get({url: 'xiluxc.vip/detail',data: {vip_id: this.vipId},success: ret => {
						this.vip = ret.data;
				    },
				    fail: err => {
						uni.showModal({
							title: '提示',
							content: err.msg,
							showCancel:false,
							success() {
								uni.navigateBack()
							}
						})
						return false;
					}
				});
			},
			//可用门店
			onUseShops(id){
				uni.navigateTo({
					url: '/pages/vip_stores_list/vip_stores_list?shop_vip_id='+id
				})
			},
			onAgree(){
				this.agree = !this.agree;
			},
			onVipAgreement(){
				let vip = this.vip;
				uni.navigateTo({
					url: '/pages/vip_agreement/vip_agreement',
					success(res) {
						res.eventChannel.emit('vipAgreement', { data: vip })
					}
				})
			},
			onSubmit(){
				if(!this.agree){
					uni.showToast({title: '请同意协议',icon: 'none'})
					return;
				}
				let platform = 'wxmini'
				// #ifdef H5
					platform = 'wxoffical';
				// #endif
				this.$core.post({url: 'xiluxc.vip/create_order',data: {platform:platform,vip_id:this.vipId,shop_id: this.shopId},success: (ret) => {
						this.payment(ret.data);
					}
				})
			},
			payment(order){
				let page = this;
				//#ifdef MP-WEIXIN
				this.$core.post({url:'xiluxc.pay/pay',data:{pay_type:1,order_id:order.id,platform:'wxmini',type:'vip'},success:(ret)=>{
					let wxconfig =  ret.data;
					this.$core.payment(wxconfig,function(){
						page.getOpenerEventChannel().emit("vipBuySuccess",{});
						uni.navigateBack()
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
	.top_img {
		height: 100vh;
		position: absolute;
		left: 0;
		right: 0;
		width: 100%;
		z-index: 1;
	}

	.pt90 {
		padding-top: 90rpx;
	}

	.vip {
		&_box {
			width: 100%;
			height: 358rpx;
			position: relative;

		}

		&_icon {
			width: 242rpx;
			height: 242rpx;
			display: block;
			top: -78rpx;
			right: 9rpx;
			z-index: 2;
			position: absolute;
		}

		&_bg {
			width: 100%;
			height: 358rpx;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 1;
		}

		&_view {
			padding:118rpx 40rpx 40rpx;
			width: 100%;
			height: 358rpx;
			position: relative;
			z-index: 3;
		}
	}

	.mt60 {
		margin-top: 60rpx;
	}

	.box{
		width: 690rpx;
		background: rgba(255, 255, 255, 0.7);
		border-radius: 25rpx;
		margin-top: 30rpx;
		padding: 40rpx 40rpx 60rpx;
		.item+.item{
			margin-top: 50rpx;
		}
	}

	.page-foot {
		background: transparent;
	}
	@supports (bottom: constant(safe-area-inset-bottom)) or (bottom: env(safe-area-inset-bottom)) {
	 
	    .page-foot ~ .container{
	        padding-bottom: calc(200rpx + 68rpx);
	        padding-bottom: calc(constant(safe-area-inset-bottom) + 200rpx);
	        padding-bottom: calc(env(safe-area-inset-bottom) + 200rpx);
	    }
	}
	.h30{height: 45rpx;}
</style>