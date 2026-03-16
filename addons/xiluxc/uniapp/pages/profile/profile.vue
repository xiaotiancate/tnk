<template>
	<view>
		<view class="container bg-f5">
			<image src="@/static/images/bg2.png" mode="aspectFill" class="top_img" />
			<view class="page-head">
				<hx-navbar ref="hxnb" :config="config">
				</hx-navbar>
			</view>
			<view class="pr z2 pt205 plr30 pb30">
				<view class="flex-box plr10">

					<image :src="userinfo.avatar|| '../../static/images/user_null.png'" mode="aspectFill"
						class="user_img"></image>
					<view class="flex-grow-1 plr40 colf">
						<view class="m-ellipsis fs36 fwb lh36">{{userinfo.nickname || ''}}</view>
						<view class="flex-box mt30 lh30">
							<image src="@/static/icon/icon_phon_mini.png" mode="aspectFill" class="ico30"></image>
							<view class="flex-grow-1 pl10 fs30 colf_8">{{userinfo.mobile || ''}}</view>
						</view>
					</view>
					<view class="btn3_mini" @click="onJump('/pages/base_info/base_info',1)">基本信息</view>
				</view>

				<view class="flex-box mt50 colf pb40">
					<view class="flex-grow-1 tc" @click="onJump('/pages/my_combo_list/my_combo_list')">
						<view class="fs36 lh36 fwb">{{userinfo.package_count || 0}}</view>
						<view class="mt10 fs26 colf_8 lh26">我的套餐</view>
					</view>
					<view class="flex-grow-1 tc" @click="onJump('/pages/my_point/my_point',1)">
						<view class="fs36 lh36 fwb">{{userinfo.points || 0}}</view>
						<view class="mt10 fs26 colf_8 lh26">我的积分</view>
					</view>
					<view class="flex-grow-1 tc" @click="onJump('/pages/my_team/my_team',1)">
						<view class="fs36 lh36 fwb">{{userinfo.team_count || 0}}</view>
						<view class="mt10 fs26 colf_8 lh26">我的团队</view>
					</view>
					<view class="flex-grow-1 tc" @click="onJump('/pages/my_coupons_list/my_coupons_list',1)">
						<view class="fs36 lh36 fwb">{{userinfo.coupon_count||0}}</view>
						<view class="mt10 fs26 colf_8 lh26">优惠券</view>
					</view>
				</view>

				<view class="nbox1">
					<view>
						<view class="mini_box1 flex-box plr30" @click="onJump('/pages/my_balance/my_balance',1)">
							<view class="flex-grow-1">
								<view class="col1 fwb lh36 fs24">¥<text
										class="fs36 lh36">{{userinfo.total_shop_money || 0}}</text></view>
								<view class="mt10 fs24 col89">我的余额</view>
							</view>
							<view class="bt">充值</view>
						</view>
						<view class="mini_box2 flex-box plr30 ml30"
							@click="onJump('/pages/my_commissions/my_commissions',1)">
							<view class="flex-grow-1">
								<view class="col1 fwb lh36 fs24"><text
										class="fs36 lh36">{{userinfo.account.money || 0}}</text></view>
								<view class="mt10 fs24 col89">我的佣金</view>
							</view>
							<view class="bt">提现</view>
						</view>
					</view>
					<view class="vip_box mt20 mlrauto" v-if="userVip.length>0" v-for="(vip,index) in userVip"
						:key="index">
						<image src="@/static/images/vip_bg.png" mode="aspectFill" class="vip_bg"></image>
						<view class="vip_info flex-box">
							<view class="flex-grow-1 pr15">
								<view class="flex-box">
									<image :src="vip.brand?vip.brand.logo:vip.shop.image_text" mode="aspectFill"
										class="ico36"></image>
									<view class="vip_level">{{vip.brand?vip.brand.brand_name:vip.shop.name}}</view>
								</view>
								<view class="vip_tips mt15">有效期 {{vip.expire_in_text}}到期</view>
							</view>
							<view class="vip_btn"
								@click="onJump('/pages/vip_stores_list/vip_stores_list?shop_vip_id='+vip.shop_vip_id)">
								可用门店</view>
						</view>
					</view>
				</view>
				<view class="car_info" @click="onJump('/pages/car_list/car_list',1)">
					<view class="flex-box">
						<view class="flex-grow-1 fwb fs30 col1">我的爱车</view>
						<image src="@/static/icon/icon_add.png" mode="aspectFill" class="ico24"></image>
						<view class="ml5 fs24 col4" @click.stop="onAddCar()">添加</view>
					</view>
					<view class="mt25 col89 fs26" v-if="userCar">{{userCar.series.name}}</view>
					<view class="mt10 fs36 fwb col1 lh36" v-if="userCar">{{userCar.car_no}}</view>
				</view>
				<view class="box1 mt30">
					<view class="fs30 fwb col1 plr30">我的订单</view>

					<view class="item25" @click="onJump('/pages/order_list/order_list',1)">
						<image src="@/static/icon/icon5.png" mode="aspectFill" class="ico45"></image>
						<view class="mt20 col5 fs28">全部</view>
						<!-- <view class="dot">2</view> -->
					</view>
					<view class="item25" @click="onJump('/pages/order_list/order_list?state=unuse',1)">
						<image src="@/static/icon/icon6.png" mode="aspectFill" class="ico45"></image>
						<view class="mt20 col5 fs28">待服务</view>
						<view class="dot" v-if="userinfo.unuse_order_count>0">{{userinfo.unuse_order_count}}</view>
					</view>
					<view class="item25" @click="onJump('/pages/order_list/order_list?state=uncomment',1)">
						<image src="@/static/icon/icon7.png" mode="aspectFill" class="ico45"></image>
						<view class="mt20 col5 fs28">待评价</view>
						<view class="dot" v-if="userinfo.uncomment_order_count>0">{{userinfo.uncomment_order_count}}
						</view>
					</view>
					<view class="item25" @click="onJump('/pages/order_list/order_list?state=finished',1)">
						<image src="@/static/icon/icon8.png" mode="aspectFill" class="ico45"></image>
						<view class="mt20 col5 fs28">已完成</view>
						<view class="dot" v-if="userinfo.finished_order_count>0">{{userinfo.finished_order_count}}
						</view>
					</view>

				</view>
				<view class="box1 mt30">
					<view class="fs30 fwb col1 plr30">我的功能</view>
					<view class="item25" @click="onJump('/pages/brand_entry/brand_entry',1)">
						<image src="@/static/icon/icon20.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">品牌入驻</view>
					</view>
					<view class="item25" @click="onJump('/pages/business_entry/business_entry',1)">
						<image src="@/static/icon/icon21.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">商家入驻</view>
					</view>
					<view class="item25" @click="onJump('/pages/my_coupons_list/my_coupons_list',1)">
						<image src="@/static/icon/icon9.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">优惠券</view>
					</view>
					<view class="item25" @click="onJump('/pages/suggestions/suggestions',1)">
						<image src="@/static/icon/icon10.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">投诉建议</view>
					</view>
					<view class="item25" @click="onJump('/pages/invite/invite',1)">
						<image src="@/static/icon/icon11.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">邀请好友</view>
					</view>
					<view class="item25" @click="onJump('/pages/rate_list/rate_list',1)">
						<image src="@/static/icon/icon12.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">我的评价</view>
					</view>
					<view class="item25" v-if="userinfo.verifier_status" @click="bindScan()">
						<image src="@/static/icon/icon13.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">扫码核销</view>
					</view>
					<view class="item25" v-if="userinfo.verifier_status"
						@click="onJump('/pages/offset_list/offset_list',1)">
						<image src="@/static/icon/icon14.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">核销记录</view>
					</view>
					<!-- <view class="item25" v-if="userinfo.verifier_status">
						<image src="@/static/icon/icon15.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">预约记录</view>
					</view> -->
					<button class="item25" hover-class="none" open-type="contact">
						<image src="@/static/icon/icon16.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">在线客服</view>
					</button>
					<view class="item25" v-if="isLogin" @click="bindLogout">
						<image src="@/static/icon/icon22.png" mode="aspectFill" class="ico45"></image>
						<view class="mt15 col5 fs28">退出登陆</view>
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
				config: {
					color: '#101010',
					back: false,
					maxSlot: true,
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
					statusBarFontColor: ['#ffffff', '#000000']
				},
				isLogin:false,
				userinfo: {
					total_shop_money: 0,
					coupon_count: 0,
					collection_count: 0,
					user_message: 0,
					nickname: '',
					avatar: '',
					account: {
						money: '0.00'
					},
					package_count: 0,
					unuse_order_count: 0,
					uncomment_order_count: 0,
					finished_order_count: 0,
				},
				userVip: [],
				userCar: null
			}
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		onLoad() {
			let page = this;
			this.isLogin = this.$core.getUserinfo() ? true : false;
			this.statusBarHeight = getApp().globalData.statusBarHeight;
			if (this.$core.getUserinfo()) {
				this.getUserinfo();
				this.getUserVip();
				this.getUserCar();
			}
			uni.$on("user_update", function() {
				page.isLogin = true;
				page.getUserinfo();
				page.getUserVip();
				page.getUserCar();
			})
			uni.$on(app.globalData.Event.loginOut, function() {
				page.userinfo = {
					total_shop_money: 0,
					coupon_count: 0,
					collection_count: 0,
					user_message: 0,
					nickname: '',
					avatar: '',
					account: {
						money: '0.00'
					},
					package_count: 0,
					unuse_order_count: 0,
					uncomment_order_count: 0,
					finished_order_count: 0,
				};
				page.isLogin = false;
				page.userVip = [];
				page.userCar = null
			})
		},
		onPullDownRefresh() {
			this.getUserinfo();
			this.getUserVip();
			this.getUserCar();
			uni.stopPullDownRefresh();
		},
		onUnload() {
			uni.$off("user_update");
			uni.$off(app.globalData.Event.loginOut)
		},
		methods: {
			getUserinfo() {
				this.$core.post({
					url: 'xiluxc.user/info',
					data: {},
					loading: false,
					success: ret => {
						this.userinfo = ret.data;
					},
					fail: err => {
						console.log(err);
					}
				});
			},
			getUserVip() {
				this.$core.post({
					url: 'xiluxc.vip/myvip',
					data: {},
					loading: false,
					success: ret => {
						this.userVip = ret.data;
					},
					fail: err => {
						console.log(err);
					}
				});
			},
			getUserCar() {
				this.$core.post({
					url: 'xiluxc.user_car/mycar',
					data: {},
					loading: false,
					success: ret => {
						this.userCar = ret.data;
					},
					fail: err => {
						console.log(err);
					}
				});
			},
			onJump(url, needLogin) {
				if (needLogin) {
					if (!this.$core.getUserinfo(true)) {
						return true;
					}
				}
				uni.navigateTo({
					url: url
				})
			},
			onAddCar() {
				uni.navigateTo({
					url: '/pages/add_car/add_car'
				})
			},
			bindScan() {
				if (!this.$core.getUserinfo(true)) {
					return true;
				}
				uni.scanCode({
					success: function(res) {
						console.log('条码类型：', res);
						let page1 = "pages/to_offset/to_offset";
						let page2 = "pages/to_offset2/to_offset2";
						if (!res.path || (res.path.indexOf(page1) === -1 && res.path.indexOf(page2) === -1)) {
							uni.showModal({
								title: '提示',
								content: '二维码错误',
								showCancel: false
							})
							return;
						}
						uni.navigateTo({
							url: '/' + res.path
						})

					},
					fail: function(res) {
						console.log(res);
					}
				});

			},
			bindLogout(){
				let page = this;
				uni.showModal({
					title: '提示',
					content: "确认退出登录",
					success(res) {
						if(res.confirm){
							page.$core.logout();
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.top_img {
		height: 512rpx;
		position: absolute;
		left: 0;
		right: 0;
		width: 100%;
		z-index: 1;
		/* #ifdef H5 */
		top: -44rpx;
		/* #endif */
		/* #ifndef H5 */
		top: 0;
		/* #endif */
	}

	.pt205 {

		/* #ifdef H5 */
		padding-top: 160rpx;
		/* #endif */
		/* #ifndef H5 */
		padding-top: 205rpx;
		/* #endif */
	}

	.user_img {
		width: 140rpx;
		height: 140rpx;
		border: 6rpx solid rgba(255, 255, 255, 0.4);
		border-radius: 50%;
	}

	.vip {
		&_box {
			width: 690rpx;
			height: 150rpx;
			position: relative;
		}

		&_bg {
			width: 690rpx;
			height: 150rpx;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 1;
		}

		&_info {
			width: 690rpx;
			height: 150rpx;
			position: relative;
			z-index: 2;
			padding: 0 30rpx;
		}

		&_level {
			font-size: 36rpx;
			color: #F4D8A9;
			line-height: 36rpx;
			font-weight: bold;
			padding-left: 15rpx;
		}

		&_tips {
			font-size: 28rpx;
			color: #B0A189;
		}

		&_btn {
			padding: 0 20rpx;
			line-height: 60rpx;
			text-align: center;
			font-size: 24rpx;
			color: #714000;
			background: #F3DAAE;
			border-radius: 35rpx;
		}
	}

	.box1 {
		width: 690rpx;
		padding: 30rpx 0;
		background: #FFFFFF;
		border-radius: 20rpx;

		.mini_box1 {
			width: 300rpx;
			height: 135rpx;
			background: rgba(54, 115, 255, 0.1);
			border-radius: 20rpx;
			display: inline-flex;
			vertical-align: top;

			.bt {
				width: 90rpx;
				height: 50rpx;
				line-height: 50rpx;
				text-align: center;
				background: #3673FF;
				border-radius: 25rpx;
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}

		.mini_box2 {
			width: 300rpx;
			height: 135rpx;
			background: rgba(254, 75, 1, 0.1);
			border-radius: 20rpx;
			display: inline-flex;
			vertical-align: top;

			.bt {
				width: 90rpx;
				height: 50rpx;
				line-height: 50rpx;
				text-align: center;
				background: #FE4B01;
				border-radius: 25rpx;
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}
	}

	.nbox1 {
		padding-top: 30rpx;

		.mini_box1 {
			width: 330rpx;
			height: 135rpx;
			background: rgba(54, 115, 255, 0.1);
			border-radius: 20rpx;
			display: inline-flex;
			vertical-align: top;

			.bt {
				width: 90rpx;
				height: 50rpx;
				line-height: 50rpx;
				text-align: center;
				background: #3673FF;
				border-radius: 25rpx;
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}

		.mini_box2 {
			width: 330rpx;
			height: 135rpx;
			background: rgba(254, 75, 1, 0.1);
			border-radius: 20rpx;
			display: inline-flex;
			vertical-align: top;

			.bt {
				width: 90rpx;
				height: 50rpx;
				line-height: 50rpx;
				text-align: center;
				background: #FE4B01;
				border-radius: 25rpx;
				font-size: 24rpx;
				color: #FFFFFF;
			}
		}
	}

	.car_info {
		width: 690rpx;
		background: #FFFFFF;
		box-shadow: 0rpx 0rpx 10rpx 0rpx rgba(184, 189, 202, 0.15);
		border-radius: 20rpx;
		border: 2rpx solid #FE4B01;
		margin-top: 30rpx;
		padding: 30rpx;
	}

	.item25 {
		width: 25%;
		text-align: center;
		display: inline-block;
		position: relative;
		margin-top: 30rpx;
		line-height: 28rpx;

		.dot {
			width: 24rpx;
			height: 24rpx;
			background: #FE4B01;
			border-radius: 50%;
			line-height: 24rpx;
			font-size: 16rpx;
			color: #FFFFFF;
			top: -8rpx;
			right: 40rpx;
			position: absolute;
		}
	}

	.item20 {
		width: 20%;
		text-align: center;
		display: inline-block;
		position: relative;
		margin-top: 30rpx;
		line-height: 28rpx;

		.dot {
			width: 24rpx;
			height: 24rpx;
			background: #FE4B01;
			border-radius: 50%;
			line-height: 24rpx;
			font-size: 16rpx;
			color: #FFFFFF;
			top: -8rpx;
			right: 40rpx;
			position: absolute;
		}
	}
</style>