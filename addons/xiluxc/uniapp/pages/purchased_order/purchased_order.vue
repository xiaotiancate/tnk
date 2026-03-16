<template>
	<view>
		<view class="page-foot">
			<view class="foot_nav flex-box plr30 fs24">
				<view class="pay_btn">确定预约</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="box1">
					<view class="tc col4  fs30">
						¥<text class="fs70 lh70 pr">35<text class="vip">VIP价</text></text>
					</view>
					<view class="mt20 fwb fs34 lh34 tc">特惠洗车</view>
				</view>

					<view class="info_item flex-box fs30 mt30" @click="showCoupon=true">
						<view class="col5">优惠券</view>
						<view class="flex-grow-1 tr col1">请选择</view>
						<image src="@/static/icon/arrow_right.png" mode="aspectFill" class="ico30 ml10"></image>
					</view>
				
				<view class="pay_item">
					<view class="flex-box fs30">
						<view class="flex-grow-1 col5">支付方式</view>
						<view class="col1">微信支付</view>
					</view>
					<view class="pay_choose  mt30 fs24" :class="[payindex==2?'active':'']" @click="choosePay(2)">
						<view class="flex-box pt30">
							<image src="@/static/icon/icon_pay3.png" mode="aspectFill" class="ico35"></image>
							<view class="plr20 fs30 col1">全年套餐服务</view>
				
							<image src="@/static/icon/icon_true.png" v-if="payindex==2" mode="aspectFill"
								class="icon_true">
							</image>
						</view>
						<view class="pb40">
							<view class="fs26 col5 mt20 pb30 bb">
								<view>规格：5座小型轿车</view>
								<view class="flex-box mt30 col4 fs28">
									<view class="m-ellipsis flex-grow-1 pr30">新能源与燃油精致洗车</view>
									<view>-1</view>
								</view>
							</view>
							<view class="mt30 fs24 col89">预约后卡内剩余次数</view>
							<view class="time_item flex-box" v-for="(item,index) in 3">
								<view class="flex-grow-1 m-ellipsis pr30">全品类车型新能源与燃油精致洗车</view>
								<view>10次</view>
							</view>
						</view>
					</view>
					<view class="pay_choose flex-box mt30 fs24" :class="[payindex==0?'active':'']"
						@click="choosePay(0)">
						<image src="@/static/icon/icon_pay1.png" mode="aspectFill" class="ico35"></image>
						<view class="plr20 fs30 col1">余额支付</view>
						<view class="col89">余额剩余</view>
						<view class="col4">¥1220</view>
						<image src="@/static/icon/icon_true.png" v-if="payindex==0" mode="aspectFill" class="icon_true">
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

				<view class="mt30 col1 fs30">套餐说明</view>
				<view class="mt20 fs24 col5 lh34">后台编辑套餐说明后台编辑套餐说明后台编辑套餐说明后台编辑套餐说明后台编辑套餐说明</view>
			</view>
		</view>
		<u-popup :show="showCal" mode="bottom" :safeAreaInsetBottom="false" @close="closeCal" bgColor="transparent">
			<view class="cal_pop">
				<wu-calendar :insert="true" @change="calendarChange" color="#FE4B01" itemHeight="60" :showMonth="false"
					monthShowCurrentMonth="false" :todayDefaultStyle="false"></wu-calendar>
				<view class="cal_btn" @click="closeCal">确定</view>
			</view>
		</u-popup>
		<u-popup :show="showCoupon" mode="bottom" :safeAreaInsetBottom="false" @close="closeCoupon"
			bgColor="transparent">
			<view class="coupon_pop">
				<view class="col1 fs30 tc">选择优惠券</view>
				<image src="@/static/icon/pop_close.png" mode="aspectFill" class="close" @click="closeCoupon"></image>
				<scroll-view scroll-y="true" class="pop_scroll">
					<view class="coupons_item" v-for="(item,index) in 4">
						<image src="@/static/images/icon_coupons_large.png" mode="aspectFill" class="bg"></image>
						<view class="view flex-box">
							<view class="left pt40 tc">
								<view class="fs32 colf lh74">¥<text class="fs74 fwb lh74">15</text></view>
								<view class="mt10 fs24 colf_8 lh24">满100可用</view>
							</view>
							<view class="flex-grow-1 pl40 flex-box pr30">
								<view class="flex-grow-1 colf fs24 lh24">
									<view class="fs30 fwb lh30">商品优惠券</view>
									<view class="mt15">限该店铺所有商品可用</view>
									<view class="mt10 colf_8">2024.12.31 23:59到期</view>
								</view>
								<view class="btn6">去使用</view>
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				carList: [{
						title: '5座小型轿车'
					},
					{
						title: 'SUV/MPV'
					}, {
						title: '8座商务车'
					}, {
						title: '24座大巴车'
					},
				],
				nindex: 0,
				payindex: 2,
				showCal: false,
				showCoupon: false
			}
		},
		methods: {
			closeCoupon() {
				this.showCoupon = false
			},
			chooseNav(index) {
				this.nindex = index
			},
			choosePay(index) {
				this.payindex = index
			},
			calendarConfirm(e) {
				console.log(e);
				this.closeCal()
			},
			calendarChange(e) {
				console.log(e);
			},

			closeCal() {
				this.showCal = false
			}

		}
	}
</script>

<style lang="scss" scoped>
	.box1 {
		width: 690rpx;
		padding: 50rpx 30rpx 50rpx;
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
			width: 100%;
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
		color: #555555;
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
</style>