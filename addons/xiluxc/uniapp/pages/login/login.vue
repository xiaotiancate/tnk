<template>
	<view>
		<!-- #ifdef H5 || APP-PLUS -->
		<view class="container">
			<view class="pr login-wrap flex-box flex-center flex-col">
				<view class="login-box mb40">
					<view class="input-box flex-box">
						<image class="mr30" src="/static/icon/icon_phone.png" mode="aspectFill"></image>
						<input class="flex-1 fs28" v-model="mobile" placeholder="请输入手机号" />
						<view class="col-red fs28 ptb10" @click="getCode()">{{codeText}}</view>
					</view>
					<view class="input-box flex-box">
						<image class="mr30" src="/static/icon/icon_code.png" mode="aspectFill"></image>
						<input class="flex-1 fs28" v-model="code" placeholder="请输入验证码" />
					</view>
					<view class="btn5" @click="mobilelogin()">登 录</view>
					<view class="fs26 col-6 tc">未注册用户，登录即创建账户</view>
				</view>
				<view class="foot-row flex-box flex-center fs26 col-6">
					<image @click="toggleAgree" v-if="isAgree" src="/static/icon/circle_selected.png" mode="aspectFill">
					</image>
					<image @click="toggleAgree" v-else src="/static/icon/circle_normal.png" mode="aspectFill"></image>
					<view>登录即同意</view>
					<!-- "agreement('user_agreement')" -->
					<view class="col-red" @click="agreePopOpen('user_agreement')">《用户协议》</view>
					<view>和</view>
					<view class="col-red" @click="agreePopOpen('secret_agreement')">《隐私注册》</view>
				</view>
			</view>
		</view>
		<!-- #endif -->

		<!-- #ifdef MP-WEIXIN -->
		<view class="container">
			<view class="authorize_content">
				<view class="header">
					<!-- <image src="/static/icon/authorize.png" mode="aspectFill"></image> -->
					<image class="head" :src="logo" mode=""></image>
				</view>
				<view class="title">请确认以下授权信息</view>
				<view class="info">·获取您的信息(手机号等)</view>
				<button hover-class="none" open-type="getPhoneNumber" class="btn1"
					@getphonenumber="getPhoneNumber">授权登录</button>
				<button hover-class="none" class="btn2" @click="closeLogin">暂不登录</button>
				<!-- <view class="user-rules">
					<u-checkbox v-model="agree" active-color="#0896e0">已阅读并同意以下条款</u-checkbox>
					<view class="rules">
						<text class="primary-color" @tap="goService">《用户服务协议》</text>
						<text>和</text>
						<text class="primary-color" @tap="goPrivacy">《用户隐私政策》</text>
					</view>
				</view> -->
			</view>
		</view>
		<!-- #endif -->

	</view>
</template>

<script>
	export default {
		data() {
			return {
				logo: '',
				mobile: '',
				disabledCode: false,
				codeText: '验证码',
				code: '',
				agree: true,
				isAgree: true,
			}
		},
		onLoad() {
			//#ifdef MP-WEIXIN
			this.logo = getApp().globalData.config.logo;
			this.$core.wxLogin(function(data) {
				getApp().globalData.userinfo = data;
				uni.navigateBack({});
				uni.$emit("user_update", {});
				uni.showToast({
					title: '登录成功',
				});
			});
			// #endif
		},
		methods: {
			closeLogin() {
				uni.navigateBack({
					delta: 1
				})
			},
			agreePopOpen(type) {
				let id = type === 'user_agreement' ? getApp().globalData.config.user_agreement : getApp().globalData.config
					.privacy_agreement;
				uni.navigateTo({
					url: '/pages/rich_mp/rich_mp?id=' + id
				})
			},
			toggleAgree() {
				this.isAgree = !this.isAgree;
			},

			//获取验证码
			getCode() {
				if (this.disabledCode) return false;
				let mobile = this.mobile;
				if (!mobile) {
					uni.showToast({
						title: '手机号不得为空',
						icon: 'none'
					})
					return false
				}
				this.$core.post({
					url: 'sms/send',
					data: {
						mobile: mobile,
						event: 'mobilelogin'
					},
					success: (ret) => {
						this.timeCut();
					}
				});
			},
			// 倒计时
			timeCut() {
				if (this.disabledCode) return;
				this.disabledCode = true;
				let n = 60;
				this.codeText = n + 's';
				const run = setInterval(() => {
					n -= 1;
					if (n < 0) {
						clearInterval(run);
					}
					this.codeText = n + 's';
					if (this.codeText < 0 + 's') {
						this.disabledCode = false;
						this.codeText = '验证码';
					}
				}, 1000);
			},
			mobilelogin() {
				let that = this;
				if (!that.isAgree) {
					uni.showToast({
						title: '请同意协议',
						icon: 'none'
					})
					return false
				}
				let mobile = this.mobile;
				let code = this.code;
				if (!mobile || !code) {
					uni.showToast({
						title: '手机号/验证码必填',
						icon: 'none'
					})
					return false
				}
				let puserId = this.$core.getCache("puser_id") || 0;
				this.$core.post({
					url: 'xiluxc.user/mobilelogin',
					data: {
						puser_id: puserId,
						mobile: mobile,
						code: code
					},
					success: (ret) => {
						let userinfo = ret.data.userinfo;
						that.$core.setUserinfo(userinfo);
						uni.$emit("user_update", {});
						uni.navigateBack({
							delta: 1
						})
					}
				});
			},
			getPhoneNumber(e) {
				var that = this;
				let version = uni.getSystemInfoSync().SDKVersion;
				if (e.detail.errMsg == "getPhoneNumber:ok") {
					if (that.compareVersion(version, '2.21.2') >= 0) {
						that.phoneNumber(that, {
							code: e.detail.code
						})
					} else {
						uni.login({
							provider: 'weixin',
							success: (auth) => {
								that.phoneNumber(that, {
									iv: e.detail.iv,
									encryptedData: e.detail.encryptedData
								})
							},
							fail: () => {
								uni.showToast({
									'title': '微信登录授权失败',
									icon: "none"
								});
							}
						})
					}
				} else {
					console.log("用户点击了拒绝")
				}
			},

			phoneNumber(that, data) {
				let wxAccount = that.$core.getCache('wx_account');
				data['third_id'] = wxAccount.third_id;
				let puserId = this.$core.getCache("puser_id") || 0;
				data['puser_id'] = puserId;
				that.$core.post({
					url: 'xiluxc.user/get_mobile',
					data: data,
					success: (ret, response) => {
						wxAccount['bindind'] = 1;
						let userinfo = ret.data.userinfo;
						that.$core.setCache('wx_account', wxAccount);
						that.$core.setUserinfo(userinfo);
						getApp().globalData.userinfo = userinfo;
						uni.navigateBack({});
						uni.$emit("user_update", {});
						uni.showToast({
							title: '登录成功',
						});
					},
					fail: (ret, response) => {
						//失败，重试
						uni.showToast({
							'title': "获取失败",
							icon: "none"
						})
						return false;
					}
				});

			},
			//版本比较
			compareVersion(v1, v2) {
				v1 = v1.split('.')
				v2 = v2.split('.')
				const len = Math.max(v1.length, v2.length)

				while (v1.length < len) {
					v1.push('0')
				}
				while (v2.length < len) {
					v2.push('0')
				}

				for (let i = 0; i < len; i++) {
					const num1 = parseInt(v1[i])
					const num2 = parseInt(v2[i])

					if (num1 > num2) {
						return 1
					} else if (num1 < num2) {
						return -1
					}
				}

				return 0
			}
		}
	}
</script>

<style>
	.head {
		margin-right: 26rpx;
		width: 128rpx;
		height: 128rpx;
		border: 4rpx solid var(--normal);
		border-radius: 50%;
	}

	.authorize_content {
		padding: 120rpx 75rpx;
		box-sizing: border-box;
	}

	.authorize_content .header {
		width: 201rpx;
		height: 201rpx;
		/* border: 6rpx solid #fff; */
		/* box-shadow: 0px 3rpx 8rpx 0px rgba(213, 213, 213, 0.4); */
		border-radius: 50%;
		overflow: hidden;
		margin: 0 auto;
	}

	.authorize_content .header image {
		width: 100%;
		height: 100%;
		display: block;
	}

	.authorize_content .title {
		font-size: 32rpx;
		color: #333333;
		padding-top: 50rpx;
		margin-top: 40rpx;
		/* border-top: 1rpx solid #EDEDED; */
		text-align: center;
	}

	.authorize_content .info {
		font-size: 28rpx;
		color: #999999;
		padding-top: 30rpx;
		padding-bottom: 70rpx;
		text-align: center;
	}

	.authorize_content button {
		width: 600rpx;
		margin-bottom: 40rpx;
	}

	.container {
		min-height: calc(100vh - env(safe-area-inset-top) - var(--window-bottom) - var(--window-top));
		overflow: hidden;
		font-size: 28rpx;
		box-sizing: border-box;
	}

	.btn1 {
		height: 80rpx;
		border-radius: 50rpx;
		text-align: center;
		background-color: #FE4B01;
		color: #FFFFFF;
		font-size: 28rpx;
		line-height: 80rpx;
	}

	.btn2 {
		background-color: #D8D8D8;
		color: #FFFFFF;
		height: 80rpx;
		border-radius: 50rpx;
		text-align: center;
		font-size: 28rpx;
		line-height: 80rpx;
	}

	.user-rules {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		margin-top: 80rpx;
	}

	.primary-color {
		color: #0896e0;
	}

	.u-checkbox__label {
		color: #999999 !important;
	}

	.rules {
		margin-top: 20rpx;
	}

	.login-wrap {
		padding: 0 30rpx;
		height: calc(100vh - 44px - env(safe-area-inset-top));
	}

	.login-wrap .title {
		margin: 0 0 125rpx 50rpx;
		font-size: 50rpx;
		font-weight: bold;
		line-height: 56rpx;
	}

	.login-box {
		padding: 90rpx 50rpx 50rpx;
		width: 690rpx;
		height: 645rpx;
		background: #ffffff;
		box-shadow: 0 6rpx 29rpx 1rpx rgba(45, 45, 45, 0.14);
		border-radius: 20rpx;
	}

	.login-box .input-box {
		padding: 0 50rpx;
		height: 80rpx;
		border: 1rpx solid #cdcdcd;
		border-radius: 40rpx;
	}

	.login-box .input-box+.input-box {
		margin-top: 50rpx;
	}

	.login-box .input-box image {
		display: block;
		width: 40rpx;
		height: 40rpx;
	}

	.login-box .btn5 {
		margin: 125rpx auto 40rpx;
		width: 100%;
		height: 92rpx;
		font-size: 32rpx;
		font-weight: 500;
		color: #ffffff;
		line-height: 92rpx;
		text-align: center;
	}

	.foot-row image {
		margin: 0 20rpx 0 0;
		display: block;
		width: 38rpx;
		height: 38rpx;
		border-radius: 50%;
	}


	.agreePop {
		padding: 30rpx 0 0;
		width: 520rpx;
		background: #ffffff;
		border-radius: 20rpx;
		overflow: hidden;
	}

	.agreePop .content {
		margin: 0 auto 25rpx;
		padding: 0 30rpx;
		max-height: 500rpx;
		overflow-y: scroll;
	}

	.agreePop .btn {
		height: 90rpx;
		line-height: 90rpx;
	}
</style>