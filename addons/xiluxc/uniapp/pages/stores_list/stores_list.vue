<template>
	<view>
		<view class="container">
			<image src="@/static/images/bg.png" mode="aspectFill" class="top_img" />
			<view class="page-head" style="z-index: 999;" :class="[openChoose?'bg-white':'']">
				<hx-navbar ref="hxnb" :config="config">
					<template slot="max" class="flex-box w100">
						<view class="flex-box" @click="chooseAddress">
							<view class="m-ellipsis fs34 fwb col1" style="max-width: 340rpx;">
								{{currentCity?(currentCity.pois?currentCity.pois.name:currentCity.name):"定位中"}}
							</view>
							<image src="@/static/icon/icon__address_arrow.png" mode="aspectFill" class="top_arrow">
							</image>
						</view>
						<image @click="search()" src="@/static/icon/icon_search.png" mode="aspectFill" class="search">
						</image>
					</template>
				</hx-navbar>
			</view>
			<view class="pr z2 pt196  pb30">

				<view class="pt30 flex-box flex-between plr30 fs26" :class="[openChoose?'fixed_tab':'']"
					:style="{'top':openChoose?`${barHeight}px`:0}">
					<view class="flex-box ml20" :class="op==0?'col4':'col5'" @click="openCal(0)">
						<view class="m-ellipsis maxw80">{{districtName?districtName:'全市'}}</view>
						<image src="@/static/icon/arrow_down_ac.png" v-if="op==0" mode="aspectFill" class="ico22 ml10">
						</image>
						<image src="@/static/icon/arrow_down.png" v-else mode="aspectFill" class="ico22 ml10"></image>
					</view>
					<view class="flex-box" :class="op==2?'col4':'col5'" @click="openCal(2)">
						<view>距离最近</view>
						<image src="@/static/icon/arrow_down_ac.png" v-if="op==2" mode="aspectFill" class="ico22 ml10">
						</image>
						<image src="@/static/icon/arrow_down.png" v-else mode="aspectFill" class="ico22 ml10"></image>
					</view>
					<view class="flex-box" :class="op==1?'col4':'col5'" @click="openCal(1)">
						<view>默认筛选</view>
						<image src="@/static/icon/arrow_down_ac.png" v-if="op==1" mode="aspectFill" class="ico22 ml10">
						</image>
						<image src="@/static/icon/arrow_down.png" v-else mode="aspectFill" class="ico22 ml10"></image>
					</view>
					<view class="flex-box mr20 col5" @click="openMap">
						<view>地图</view>
						<image src="@/static/icon/icon_address1.png" mode="aspectFill" class="ico22 ml10"></image>
					</view>
				</view>
				<view class="h64" v-if="openChoose"></view>

				<store-list :shop-list="shopList" :type="2"></store-list>

				<view class="nothing" v-if="shopMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchShop">{{shopMore.text}}</view>
				</view>

				<u-popup :show="openChoose" mode="top" :safeAreaInsetBottom="false" @close="closeCal"
					bgColor="transparent">
					<view class="popup" :style="{'marginTop':`calc(${barHeight}px + 94rpx)`}">
						<template v-if="op==0">
							<scroll-view scroll-y class="picker-view">
								<view class="item" :class="value==index?'col4 active':''"
									v-for="(item,index) in districts" :key="index" @click="bindDistrictChange(index)">
									{{item.name}}</view>
							</scroll-view>
							<view class="btn_nav flex-box">
								<view class="b1" @click="searchReset()">清除</view>
								<view class="b2 ml25" @click="searchConfirm()">确定</view>
							</view>
						</template>
						<template v-if="op==1">
							<view class="choose_item" :class="{ac:sortName=='sales'}"
								@click="searchOrderConfirm('sales','desc')">综合</view>
							<view class="choose_item" :class="{ac:sortName=='point'}"
								@click="searchOrderConfirm('point','desc')">评分</view>
							<view class="choose_item" :class="{ac:isBrand=='1'}" @click="searchOrderConfirm('brand')">
								只看品牌</view>
						</template>
					</view>
				</u-popup>
			</view>
		</view>

	</view>
</template>

<script>
	const app = getApp();
	import htzRate from '@/components/htz-rate/htz-rate.vue';
	import storeList from '../../components/store-list/store-list.vue';
	export default {
		components: {
			htzRate,
			storeList
		},
		data() {
			return {
				config: {
					color: '#101010',
					back: true,
					maxSlot: true,
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
				},
				currentCity: null,

				serviceId: 0,

				districtId: 0,
				districtName: '',
				sortName: 'updatetime',
				sortOrder: 'desc',
				isBrand: 0,
				shopList: [],
				shopMore: {
					page: 1
				},
				openChoose: false,
				op: -1,
				statusBarHeight: null,
				barHeight: null,
				indicatorStyle: `height: 47px;line-height:47px;background: #F5F6F7;z-index:-1;`,
				districts: [],
				value: 0
			}
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		onLoad(options) {
			let page = this;
			page.statusBarHeight = uni.getSystemInfoSync().statusBarHeight;
			// 胶囊数据
			// #ifdef MP
			const {
				top,
				height
			} = wx.getMenuButtonBoundingClientRect();
			// #endif
			// 自定义导航栏高度 = 胶囊高度 + 胶囊的padding*2, 如果获取不到设置为38
			// this.barHeight = height ? height + (top - this.statusBarHeight) * 2 : 38;
			page.barHeight = 44 + page.statusBarHeight;
			if (options.service_id) {
				this.serviceId = options.service_id
			}
			this.currentCity = this.$core.getCurrentCity();
			page.refreshPage();
			uni.$on(app.globalData.Event.CurrentCityChange, function(currentCity) {
				page.currentCity = currentCity;
				page.districtId = 0;
				page.value = [0];
				page.districtName = '';
				page.refreshPage();
			})
		},
		onReachBottom() {
			this.fetchShop()
		},
		onPullDownRefresh() {
			this.refreshPage();
		},
		onUnload() {
			uni.$off(app.globalData.Event.CurrentCityChange, this);
		},
		methods: {
			search() {
				uni.navigateTo({
					url: '/pages/search/search'
				})
			},
			closeCal() {
				this.openChoose = false
			},
			openCal(index) {

				this.op = index
				if (index == 2) {
					this.sortName = 'distance';
					this.sortOrder = 'asc';
					this.refreshShop();
				} else {
					this.openChoose = true
				}
			},
			bindDistrictChange(index) {

				this.value = index
			},
			refreshPage() {
				//全市
				this.$core.get({
					url: 'xiluxc.common/districts',
					data: {
						city_id: this.currentCity.id
					},
					loading: false,
					success: (ret) => {
						this.districts = ret.data;
					}
				});
				//附近门店
				this.refreshShop();
				uni.stopPullDownRefresh();
			},

			searchReset() {
				this.districtId = 0;
				this.districtName = '';
				this.refreshShop();
				this.closeCal();
			},
			//搜索确认
			searchConfirm() {
				let value = this.value;
				this.districtId = this.districts[value].id;
				this.districtName = this.districts[value].name;
				this.refreshShop();
				this.closeCal();
			},
			searchOrderConfirm(sort, order) {
				if (sort == 'brand') {
					this.isBrand = 1;
				} else {
					this.isBrand = 0;
					this.sortName = sort;
					this.sortOrder = order;
				}
				this.refreshShop();
				this.closeCal();
			},
			//地图找店
			openMap() {
				uni.navigateTo({
					url: '/pages/map/map'
				})
			},
			refreshShop() {
				this.shopList = [];
				this.shopMore = {
					page: 1
				};
				this.fetchShop();
			},
			fetchShop() {
				let currentCity = this.currentCity;
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude

				this.$util.fetch(this, 'xiluxc.shop', {
					pagesize: 10,
					lat: lat,
					lng: lng,
					service_id: this.serviceId,
					district_id: this.districtId,
					sort: this.sortName,
					order: this.sortOrder,
					is_brand: this.isBrand,
				}, 'shopMore', 'shopList', 'data', data => {

				})
			},
			//门店详情
			onShopDetail(id) {
				uni.navigateTo({
					url: '/pages/stores_info/stores_info?id=' + id
				})
			},
			onServiceDetail(id, shopId) {
				uni.navigateTo({
					url: '/pages/service_detail/service_detail?id=' + id + '&shop_id=' + shopId
				})
			},
			//购买
			onBuy(serviceId, shopId) {
				if (!this.$core.getUserinfo(true)) {
					return;
				}
				let param = {
					type: 'service',
					service_id: serviceId,
					shop_id: shopId,
					service_price_id: 0
				};
				uni.navigateTo({
					url: '/pages/pay_order/pay_order?param=' + encodeURIComponent(JSON.stringify(param))
				})
			},
			onChangeService(index) {
				this.nindex = index;
				this.refreshShop();
			},
			chooseAddress() {
				let page = this;
				let currentCity = this.currentCity;
				uni.chooseLocation({
					latitude: currentCity ? currentCity.pois.latitude : app.globalData.location.latitude,
					longitude: currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude,
					success(res) {
						page.$core.getCityByLat(res.latitude, res.longitude)
					},
					fail(res) {
						console.log(res)
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.top_img {
		height: 585rpx;
		position: absolute;
		left: 0;
		right: 0;
		width: 100%;
		z-index: 1;
	}

	.top_arrow {
		width: 20rpx;
		height: 20rpx;
		margin-left: 10rpx;
	}

	.w100 {
		width: 100%;
		min-width: 100%;
	}

	.search {
		width: 34rpx;
		height: 34rpx;
		margin-left: auto;
		margin-right: 20rpx;
	}

	.pt196 {
		/* #ifdef H5 */
		padding-top: 152rpx;
		/* #endif */
		/* #ifndef H5 */
		padding-top: 196rpx;
		/* #endif */
	
	}
	
	.swiper {
		width: 100%;
		height: 270rpx;

		.banner {
			width: 100%;
			height: 270rpx;
			border-radius: 20rpx;
		}
	}

	.scroll {
		width: 100%;
		height: 120rpx;
		white-space: nowrap;

		.scroll_item {
			width: 140rpx;
			height: 120rpx;
			background: #F5F7FB;
			border-radius: 15rpx;
			display: inline-flex;
			align-items: center;
			vertical-align: top;
			margin-left: 20rpx;
			font-size: 24rpx;
			color: #2B2B2B;
			line-height: 24rpx;

			&.active {
				font-size: 24rpx;
				color: #FFFFFF;
				font-weight: bold;
				background: linear-gradient(180deg, #F5603B 0%, #FA8C59 100%);
			}

			&:first-child {
				margin-left: 30rpx;
			}

			&:last-child {
				margin-right: 30rpx;
			}

			image {
				width: 50rpx;
				height: 50rpx;
			}
		}
	}

	.w140 {
		width: 140rpx;
		margin-left: 20rpx;
	}

	.maxw80 {
		max-width: 80%;
	}



	.h64 {
		height: 64rpx;
	}

	.fixed_tab {
		position: fixed;
		padding-bottom: 30rpx;
		left: 0;
		width: 100%;
		z-index: 10076;
		background: #fff;
		border-bottom: 1px solid #EEEEEE;
	}

	.popup {
		width: 750rpx;
		background: #FFFFFF;

		.picker-view {
			height: 670rpx;
			width: 100%;
			background: #fff;
			line-height: 94rpx;
			text-align: center;

			.item {
				height: 94rpx;
				line-height: 94rpx;

				&.active {
					background: #F5F6F7;

				}
			}
		}

		.choose_item {
			padding: 30rpx 40rpx 40rpx;
			line-height: 30rpx;
			font-size: 30rpx;
			color: #101010;

			&.ac {
				font-weight: bold;
				font-size: 30rpx;
				color: #FE4B01;
			}
		}

		.btn_nav {
			width: 750rpx;
			height: 140rpx;
			background: #FFFFFF;
			padding: 0 40rpx;
			text-align: center;
			border-top: 1px solid #EEEEEE;

			.b1 {
				width: 220rpx;
				line-height: 85rpx;
				background: #F5F6F7;
				border-radius: 43rpx;
				font-size: 30rpx;
				color: #5E5F5F;
			}

			.b2 {
				width: 425rpx;
				line-height: 85rpx;
				background: #FE4B01;
				border-radius: 43rpx;
				font-size: 30rpx;
				color: #FFFFFF;
			}
		}
	}
</style>