<template>
	<view>
		<view class="page-foot">
			<view class="foot_nav flex-box plr30 fs24">
				<view class="col5">合计</view>
				<view class="pl10 col4 flex-grow-1">¥<text class="fs30">{{order.pay_price}}</text></view>
				<button class="pay_btn" :disabled="loading" :loading="loading" @click="createOrder()">立即支付</button>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="box1">
					<block v-if="param.type=='service'">
						<view class="tc pr col4  fs30">
							¥
							<text class="fs70 lh70 pr" v-if="order.is_shop_vip==1">{{order.data.service_price_choose.vip_price}}<text class="vip" >VIP价</text></text>
							<text class="fs70 lh70 pr" v-else>{{order.data.service_price_choose.salesprice}}</text>
						</view>
						<view class="mt20 fwb fs34 lh34 tc">{{order.data.service?order.data.service.name:''}}</view>
					</block>
					<block v-if="param.type=='package'">
						<view class="tc pr col4  fs30">
							¥<text class="fs70 lh70 pr">{{order.is_shop_vip==1?order.data.vip_price:order.data.salesprice}}<text class="vip" v-if="order.is_shop_vip==1">VIP价</text></text>
						</view>
						<view class="mt20 fwb fs34 lh34 tc">{{order.data.name ? order.data.name : ''}}</view>
					</block>
					<!-- 单个服务选项 -->
					<view class="mt60" v-if="param.type=='service' ">
						<view class="car_item flex-box flex-col" :class="[param.service_price_id==item.id?'active':'']"
							@click="changeServicePrice(item.id)" v-for="(item,index) in order.data.service_price">
							<view>{{item.title}}</view>
							<image src="@/static/icon/icon_true.png" v-if="param.service_price_id==item.id" mode="aspectFill"
								class="icon_true"></image>
						</view>
					</view>
					<!-- 单次购买 -->
					<view class="pb10 mt20 fs30 col89 tc" v-if="param.type=='service'">{{order.data.service_price_choose.title}}</view>
				</view>

				<view class="info_item flex-box fs30 mt30" v-if="param.type=='service'" @click="showCal=true">
					<view class="col5">预约日期</view>
					<view class="flex-grow-1 tr col1">{{param.appoint_date}}</view>
					<image src="@/static/icon/arrow_right.png" mode="aspectFill" class="ico30 ml10"></image>
				</view>
				<view class="info_item flex-box fs30 mt30" @click="showCoupon=true">
					<view class="col5">优惠券</view>
					<view class="flex-grow-1 tr col1">{{order.coupon?order.coupon.name:"请选择"}}</view>
					<image src="@/static/icon/arrow_right.png" mode="aspectFill" class="ico30 ml10"></image>
				</view>
				<!-- 套餐购买    -->
				<block v-if="param.type=='service' && order.shop_packages.length>0">
					<view class="info_item fs30 mt30" v-for="(item,index) in order.shop_packages">
						<view class="flex-box">
							<view class="col1 m-ellipsis">{{item.name}}</view>
							<view class="flex-grow-1 col4 fs24 plr20">¥<text class="fs30">{{order.is_shop_vip==1?item.vip_price:item.salesprice}}</text><text v-if="order.is_shop_vip==1">{{item.salesprice}}</text></view>
							<image @click="onChangePackage(index)" :src="'/static/icon/'+(item.checked==1?'choose_sc':'choose_uc')+'.png'" mode="widthFix" class="ico35 ml10"></image>
						</view>
						<view class="mt30 bt">
						<view class="time_item " v-for="(package_service,index) in item.package_service2">
							<view class="flex-box">
							<view class="flex-grow-1 m-ellipsis pr30">{{package_service.service?package_service.service.name:''}}</view>
							<view>{{package_service.use_count}}次</view>
								
							</view>
							<view class="fs24 col5 mt15">规格：{{package_service.service_price.title}}</view>
						</view>
						</view>
					</view>
				</block>
				<view class="info_item flex-box fs30 mt30" v-if="order.user_account && order.user_account.points>0">
					<view class="col1">积分抵扣</view>
					<view class="pl20 col4">{{order.user_account.points}}积分</view>
					<view class="flex-grow-1 tr col4 plr20">可抵扣¥{{order.points_fee}}</view>
					<image @click="onUsePoints()" :src="'/static/icon/'+(param.use_points_status?'choose_sc':'choose_uc')+'.png'" mode="aspectFill" class="ico35"></image>
				</view>
				<view class="pay_item">
					<view class="flex-box fs30">
						<view class="flex-grow-1 col5">支付方式</view>
						<view class="col1">{{payTypeText[payindex]}}</view>
					</view>
					<view class="pay_choose  mt30 fs24" :class="[payindex==3?'active':'']" @click="choosePay(3)" v-if="param.type=='service' && order.user_package">
						<view class="flex-box pt30">
							<image src="@/static/icon/icon_pay3.png" mode="aspectFill" class="ico35"></image>
							<view class="plr20 fs30 col1">{{order.user_package.package_name}}</view>

							<image src="@/static/icon/icon_true.png" v-if="payindex==3" mode="aspectFill"
								class="icon_true">
							</image>
						</view>
						<view class="pb40" v-if="payindex==3">
							<view class="fs24 col89 pb30 bb" v-for="(item,index) in order.user_package.package_service" v-if="item.service_price_id===param.service_price_id">
								<view class="flex-box mt25 col4 fs28">
									<view class="m-ellipsis flex-grow-1 pr30">{{item.service_name}}</view>
									<view>-1</view>
								</view>
								<view>规格：{{item.service_price_name}}</view>
							</view>
							<view class="mt30 fs24 col89">预约后卡内剩余次数</view>
							<view class="time_item" v-for="(item,index) in order.user_package.package_service">
								<view class="flex-box">
								<view class="flex-grow-1 m-ellipsis pr30">{{item.service_name}}</view>
								<view>{{item.stock}}次</view>
								</view>
								<view class="fs24 col5 mt15">规格：{{item.service_price_name}}</view>
							</view>
						</view>
					</view>
					<view class="pay_choose flex-box mt30 fs24" :class="[payindex==2?'active':'']" @click="choosePay(2)">
						<image src="@/static/icon/icon_pay1.png" mode="aspectFill" class="ico35"></image>
						<view class="plr20 fs30 col1">余额支付</view>
						<view class="col89">余额剩余</view>
						<view class="col4">¥{{order.user_shop_account?order.user_shop_account.money:'0.00'}}</view>
						<image src="@/static/icon/icon_true.png" v-if="payindex==2" mode="aspectFill" class="icon_true">
						</image>
					</view>
					<view class="pay_choose flex-box mt30 fs24" :class="[payindex==1?'active':'']"
						@click="choosePay(1)">
						<image src="@/static/icon/icon_pay2.png" mode="aspectFill" class="ico35"></image>
						<view class="plr20 fs30 col1">微信支付</view>

						<image src="@/static/icon/icon_true.png" v-if="payindex==1" mode="aspectFill" class="icon_true">
						</image>
					</view>
				</view>
				<block v-if="param.type=='service' && order.package_choosed">
					<view class="mt30 col1 fs30">套餐说明</view>
					<view class="mt20 fs24 col5 lh34"><rich-text :nodes="order.package_choosed.notice"></rich-text></view>
				</block>
				<block v-if="param.type=='package'">
					<view class="mt30 col1 fs30">套餐说明</view>
					<view class="mt20 fs24 col5 lh34"><rich-text :nodes="order.data.notice"></rich-text></view>
				</block>
			</view>
		</view>
		<u-popup :show="showCal" mode="bottom" :safeAreaInsetBottom="false" @close="closeCal" bgColor="transparent">
			<view class="cal_pop">
				<wu-calendar :insert="true" @change="calendarChange" color="#FE4B01" itemHeight="60" :showMonth="false"
					monthShowCurrentMonth="false" :todayDefaultStyle="false"></wu-calendar>
				<view class="cal_btn" @click="calendarConfirm">确定</view>
			</view>
		</u-popup>
		<u-popup :show="showCoupon" mode="bottom" :safeAreaInsetBottom="false" @close="closeCoupon"
			bgColor="transparent">
			<view class="coupon_pop">
				<view class="col1 fs30 tc">选择优惠券</view>
				<image src="@/static/icon/pop_close.png" mode="aspectFill" class="close" @click="closeCoupon"></image>
				<scroll-view scroll-y="true" class="pop_scroll">
					<view class="coupons_item" v-for="(coupon,index) in order.coupon_list">
						<image src="@/static/images/icon_coupons_large.png" mode="aspectFill" class="bg"></image>
						<view class="view flex-box">
							<view class="left pt40 tc">
								<view class="fs32 colf lh74">¥<text class="fs74 fwb lh74">{{coupon.money}}</text></view>
								<view class="mt10 fs24 colf_8 lh24">满{{coupon.at_least}}可用</view>
							</view>
							<view class="flex-grow-1 pl40 flex-box pr30">
								<view class="flex-grow-1 colf fs24 lh24">
									<view class="fs30 fwb lh30">{{coupon.name}}</view>
									<view class="mt15">限该店铺所有商品可用</view>
									<view class="mt10 colf_8">{{coupon.use_end_time_text}}到期</view>
								</view>
								<image @click="onChangeCoupon(index)" :src="'/static/icon/'+(coupon.checked?'choose_sc_w':'choose_uc_w')+'.png'" mode="aspectFill" class="ico35"></image>
								
							</view>
						</view>
					</view>
				</scroll-view>
				<view class="pop_btn" @click="onConfirm()">确定使用</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	var validate = require("../../xilu/validate.js");
	// #ifdef H5
	var jweixin = require('@/xilu/jweixin.js');
	// #endif
	export default {
		data() {
			return {
				param:{
					coupon_id: 0
				},
				order:{
					coupon_price:0,
					coupon_list:[],
					pay_price:0,
					total_price:0,
					data:{
						service_price:[],
						service_price_choose:{
							salesprice: '',
							vip_price: ''
						}
					},
					is_shop_vip: 0,
					shop_packages:[],
					package_choosed: null,
					user_shop_account: null,
					user_package: null,
					user_account: null,
					shop_branch_package:''
				},
				payTypeText:{1:'微信支付',2:'余额支付',3:'套餐支付'},
				payindex: 1,
				showCal: false,
				appoint_date: '',
				showCoupon: false,
				loading: false
			}
		},
		onLoad(options) {
			let param = options.param;
			this.param  = JSON.parse(decodeURIComponent(param));
			if(this.param.type == 'service'){
				let odate = new Date();
				const year = odate.getFullYear();
				const month = odate.getMonth() + 1; // 月份从0开始，所以要加1
				const day = odate.getDate();
				this.param.appoint_date = year + '-' + month + '-' + day;
			}
			this.param.use_points_status =  0;
			this.preOrder();
		},
		methods: {
			preOrder() {
				this.$core.post({url: 'xiluxc.order/pre_order',loading:true,data: this.param,success: ret => {
						this.order = ret.data;
						if(ret.data.coupon) this.param.coupon_id = ret.data.coupon.id;
						if(ret.data.data.service_price_choose) this.param.service_price_id = ret.data.data.service_price_choose.id;
					},fail: err => {
						uni.showModal({
							title: '提示',
							content: err.msg,
							showCancel:false,
							success(res) {
								if(res.confirm){
									uni.navigateBack({});
								}
							}
						})
						return false;
					}
				});
			},
			//切换规格
			changeServicePrice(id) {
				this.param.service_price_id = id;
				this.param.package_id = 0;
				this.preOrder();
			},
			//使用积分抵扣
			onUsePoints(){
				this.param.use_points_status = !this.param.use_points_status
				this.preOrder();
			},
			//切换套餐购买
			onChangePackage(index){
				let shop_packages = this.order.shop_packages;
				for(let i=0;i<shop_packages.length;i++){
					if(i == index){
						shop_packages[i].checked = !shop_packages[i].checked;
					}else{
						shop_packages[i].checked = 0;
					}
				}
				this.order.shop_packages = shop_packages;
				this.$forceUpdate();
				if(shop_packages[index].checked){
					this.param.package_id = shop_packages[index].id;
				}else{
					this.param.package_id = 0;
				}
				this.preOrder();
			},
			onChangeCoupon(index){
				for(let i=0;i<this.order.coupon_list.length;i++){
					this.order.coupon_list[i].checked = 0;
				}
				this.order.coupon_list[index].checked = 1;
				this.$forceUpdate();
			},
			onConfirm(){
				for(let i=0;i<this.order.coupon_list.length;i++){
					if(this.order.coupon_list[i].checked == 1){
						this.param.coupon_id = this.order.coupon_list[i].id;
						break;
					}
				}
				this.preOrder();
				this.closeCoupon();
			},
			closeCoupon() {
				this.showCoupon = false
			},
			//支付方式
			choosePay(index) {
				if(index == 2){
					let userShopAccount = this.order.user_shop_account;
					if(!userShopAccount || this.order.pay_price*100 > userShopAccount.money*100){
						uni.showToast({title: '余额不足',icon: 'none'})
						return;
					}
				}else if(index == 3){
					let userPackage = this.order.user_package;
					if(!userPackage){
						uni.showToast({title: '未找到可用套餐',icon: 'none'})
						return;
					}
				}
				this.payindex = index
			},
			calendarChange(e) {
				this.appoint_date = e.fulldate;
			},
			calendarConfirm() {
				this.param.appoint_date = this.appoint_date;
				this.closeCal()
			},
			closeCal() {
				this.showCal = false
			},
			//下单
			createOrder(){
				let order = this.param;
				// #ifdef H5
					order.platform = 'wxoffical';
				// #endif
				if(order.type == 'service'){
					var rule = [
						{name: 'appoint_date',nameChn: '日期',rules: ['require'],errorMsg: {required: '预约日期必须选择'}},
					];
					// 是否全部通过，返回Boolean
					if (!validate.check(order, rule)) {
					    uni.showToast({
					        title: validate.getError()[0],
					        icon: 'none'
					    });
					    return;
					}
				}
				
				this.loading = true;
				this.$core.post({url: 'xiluxc.order/create_order',data: order,loading: true,success: ret => {
					this.loading = false;
					//下单成功，前往收银台
					this.payment(ret.data);
					},
					fail: err => {
						this.loading = false;
						uni.showModal({
							title:'提示',
							content: err.msg,
							showCancel: err.code==3?true:false,
							success(res) {
								if(res.confirm){
									if(err.code==3){
										uni.redirectTo({
											url: '/pages/travel_order/travel_order'
										})
									}
								}
							}
						})
						return false;
					}
				});
			},
			payment(order){
				let page = this;
				//#ifdef MP-WEIXIN
				this.$core.post({url:'xiluxc.pay/pay',data:{type: 'order',pay_type: this.payindex,user_package_id: this.order.user_package?this.order.user_package.id:0,order_id:order.id,platform:'wxmini'},success:(ret)=>{
					if(this.payindex == 1){
						let wxconfig =  ret.data;
						this.$core.payment(wxconfig,function(){
							page.jumpTo(order);
						})
					}else if(this.payindex == 2){
						this.jumpTo(order);
					}else if(this.payindex == 3){
						this.jumpTo(order);
					}
					
				}});
				//#endif
				
				//#ifdef H5
					let openid = this.$core.getCache('wx_openid') || '';
					this.$core.post({url:'xiluxc.pay/pay',data:{openid:openid,type: 'order',pay_type: this.payindex,user_package_id: this.order.user_package?this.order.user_package.id:0,order_id:order.id,platform:'wxoffical'},success:(ret)=>{
						if(this.payindex == 1){
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
										page.jumpTo(order);
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
							
						}else if(this.payindex == 2){
							this.jumpTo(order);
						}else if(this.payindex == 3){
							this.jumpTo(order);
						}
						
						
					}});
				//#endif
				
			},
			
			jumpTo(order){
				let url ='/pages/order_list/order_list';
				if(order.type == 'package' && this.param.type=='package'){
					url = '/pages/my_combo_list/my_combo_list'
				}
				uni.showModal({
					title: '提示',
					content: '支付成功',
					showCancel: false,
					success() {
						uni.redirectTo({
							url: url
						})
					}
				})
			}

		}
	}
</script>

<style lang="scss" scoped>
	.box1 {
		width: 690rpx;
		padding: 50rpx 30rpx 40rpx;
		background: #FFFFFF;
		border-radius: 15rpx;


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
	}

	.mt60 {
		margin-top: 60rpx;
	}

	.info_item {
		width: 690rpx;

		background: #FFFFFF;
		border-radius: 15rpx;
		padding: 40rpx 30rpx;

	}

	.pay_item {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-top: 30rpx;
		padding: 40rpx 30rpx 30rpx;
	}

	.pay_choose {
		width: 630rpx;
		min-height: 100rpx;
		background: #F5F7FB;
		border-radius: 15rpx;
		border: 2rpx solid #F5F7FB;
		padding: 0 30rpx;
		position: relative;

		&.active {
			background: #FFFFFF;
			border: 2rpx solid #FE4B01;
		}

		.icon_true {
			width: 35rpx;
			height: 35rpx;
			position: absolute;
			bottom: -2rpx;
			right: -2rpx;
		}
	}

	.page-foot {
		box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		background: #FFFFFF;

		.foot_nav {
			height: 120rpx;
		}

		.pay_btn {
			width: 450rpx;
			height: 100rpx;
			line-height: 100rpx;
			text-align: center;
			font-size: 30rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 56rpx;
		}
	}

	.date_pop {
		width: 750rpx;
		height: 837rpx;
		background: #FFFFFF;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		padding-top: 35rpx;
		padding-bottom: 50rpx;
	}

	.cal_pop {
		width: 750rpx;

		background: #FFFFFF;
		border-radius: 30rpx 30rpx 0rpx 0rpx;

		.cal_btn {
			width: 690rpx;
			height: 100rpx;
			line-height: 100rpx;
			text-align: center;
			margin: 0 auto 40rpx;
			font-size: 30rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 56rpx;
		}
	}

	.time_item {
		margin-top: 30rpx;
		font-size: 28rpx;
		color: #333;
	}

	.vip {
		width: 65rpx;
		height: 30rpx;
		background: #F3DAAE;
		border-radius: 10rpx 35rpx 35rpx 0rpx;
		font-size: 20rpx;
		color: #714000;
		line-height: 30rpx;
		text-align: center;
		position: absolute;
		top: -10rpx;
		right: -70rpx;
	}

	.coupon_pop {
		width: 750rpx;
		background: #FFFFFF;
		border-radius: 20rpx 20rpx 0rpx 0rpx;
		padding: 35rpx 30rpx 35rpx;
		position: relative;
.pop_btn{
	width: 690rpx;
	height: 100rpx;
	line-height: 100rpx;
	text-align: center;
	margin-top: 20rpx;
	font-size: 30rpx;
	color: #FFFFFF;
	background: #FE4B01;
	border-radius: 56rpx;
}
		.close {
			width: 30rpx;
			height: 30rpx;
			top: 35rpx;
			right: 30rpx;
			position: absolute;
		}

		.pop_scroll {
			width: 100%;
			max-height: 660rpx;
			margin-top: 30rpx;

			.lh74 {
				line-height: 74rpx;
				height: 74rpx;
			}

			.fs74 {
				font-size: 74rpx;
			}

			.coupons_item+.coupons_item {
				margin-top: 30rpx;
			}

			.coupons_item {

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
	}
	.bt{border-top: 1px solid #EEEEEE;}
</style>