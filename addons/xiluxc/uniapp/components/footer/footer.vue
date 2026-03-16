<template>
	<view class="m-footer" :style="'--backgroundColor:'+(backgroundColor?backgroundColor:'#fff')">
		<!-- <navigator v-for="(item,index) in (list)" :key="item.index" hover-class="none" class="m-footer-item" open-type="redirect" :url="item.pagePath" :data-index="index"></navigator> -->
		<view v-for="(item,index) in (list)" :key="index" class="m-footer-item"
			@click="jumpFooter(item.pagePath,item.needLogin)">
			<image class="foot-icon" :src="footState === index ? item.selectedIconPath : item.iconPath"></image>
			<view class="foot-text" :style="'color: '+(footState === index ? selectedColor : color)">
				{{item.text}}
			</view>
			<!-- <text class="foot-badge" v-if="index == 2 && num > 0">{{num}}</text> -->
		</view>
	</view>
</template>

<script>
	export default {
		name: "Footer",
		props: {
			identity: {
				default: 1,
				type: Number
			},
			footState: {
				default: 0,
				type: Number
			},
			num: {
				default: 0,
				type: Number
			},
		},
		data() {
			return {
				backgroundColor: "#FFFFFF",
				color: "#AAAAAA",
				selectedColor: "#FE4B01",
				list: [],
				list1: [{
						"pagePath": "/pages/index/index",
						"text": "首页",
						"iconPath": "/static/icon/icon_foot1_uc.png",
						"selectedIconPath": "/static/icon/icon_foot1_sc.png",
					},
					{
						"pagePath": "/pages/stores/stores",
						"text": "门店",
						"iconPath": "/static/icon/icon_foot2_uc.png",
						"selectedIconPath": "/static/icon/icon_foot2_sc.png",
					},
					{
						"pagePath": "/pages/car_knowledge/car_knowledge",
						"text": "知识",
						"iconPath": "/static/icon/icon_foot3_uc.png",
						"selectedIconPath": "/static/icon/icon_foot3_sc.png",
					}, {
						"pagePath": "/pages/message_list/message_list",
						"text": "消息",
						"iconPath": "/static/icon/icon_foot4_uc.png",
						"selectedIconPath": "/static/icon/icon_foot4_sc.png",
					}, {
						"pagePath": "/pages/profile/profile",
						"text": "我的",
						"iconPath": "/static/icon/icon_foot5_uc.png",
						"selectedIconPath": "/static/icon/icon_foot5_sc.png",
					},

				],
				list2: [],

			};
		},
		watch: {
			identity(newVal, old) {
				if (newVal == 1) {
					this.list = this.list1;
				} else if (newVal == 2) {
					this.list = this.list2;
				} else {
					this.list = this.list3;
				}
			}
		},
		created() {
			if (this.identity == 1) {
				this.list = this.list1;
			} else if (this.identity == 2) {
				this.list = this.list2;
			} else {
				this.list = this.list3;
			}
		},
		methods: {
			jumpFooter(url, needLogin) {
				if (needLogin) {
					if (!this.$core.getUserinfo(true)) {
						return false;
					}
				}
				uni.switchTab({
					url: url
				})
			}
		}
	}
</script>
<style scoped>
	/* components/u-foot/index.css */
	.m-footer {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 99;
		display: -webkit-flex;
		display: flex;
		-webkit-align-items: center;
		align-items: center;
		width: 100%;
		background-color: var(--backgroundColor);
		box-sizing: border-box;
		box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
	}

	.m-footer-border {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 1px;
		background-color: var(--borderStyle);
		-webkit-transform-origin: 0 0;
		transform-origin: 0 0;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		pointer-events: none;
		box-sizing: border-box;

	}

	.m-footer-item {
		-webkit-flex: 1;
		flex: 1;
		position: relative;
		padding: 15rpx 10rpx 12rpx;
		height: 98rpx;
	}

	.m-footer-item .foot-icon {
		display: block;
		margin: 0 auto 7rpx;
		width: 40rpx;
		height: 40rpx;
	}

	.m-footer-item .foot-text {
		text-align: center;
		font-size: 22rpx;
		line-height: 22rpx;
	}

	.m-footer-item .foot-badge {
		position: absolute;
		right: 50%;
		top: 10rpx;
		margin-right: -45rpx;
		box-sizing: border-box;
		padding: 0 3px;
		min-width: 16px;
		height: 14px;
		line-height: 12px;
		border: 1px solid #fff;
		border-radius: 99px;
		color: #fff;
		font-size: 11px;
		font-weight: 500;
		text-align: center;
		white-space: nowrap;
		background-color: #f44;
	}

	.m-footer-bg {
		display: block;
		width: 100vw;
		height: 130rpx;
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
	}

	.m-footer-bg image {
		display: block;
		width: 100%;
		height: 100%;
	}

	.m-footer-bg::after {
		content: '';
		display: block;
		width: 100%;
		height: 60rpx;
		background-color: #fff;
		position: absolute;
		left: 0;
		bottom: -17px;
	}


	@supports (bottom: constant(safe-area-inset-bottom)) or (bottom: env(safe-area-inset-bottom)) {
		.m-footer {
			padding-bottom: calc(34px/2);
			padding-bottom: calc(constant(safe-area-inset-bottom)/2);
			padding-bottom: calc(env(safe-area-inset-bottom)/2);
		}

		.m-footer-bg {
			bottom: calc(34px/2);
			bottom: calc(constant(safe-area-inset-bottom)/2);
			bottom: calc(env(safe-area-inset-bottom)/2);
		}
	}
</style>