<template>
	<view>
		<view class="container">
			<image src="@/static/images/bg1.png" mode="aspectFill" class="top_img" />
			<view class="page-head">
				<hx-navbar ref="hxnb" :config="config">
					<template slot="max" class="flex-box w100">
						<view @click="chooseAddress">
							<text
								class="pl30 fs34 fwb col1">{{currentCity?(currentCity.pois?currentCity.pois.name:currentCity.name):"定位中"}}</text>
							<image src="@/static/icon/icon__address_arrow.png" mode="aspectFill" class="top_arrow">
							</image>
						</view>
						<image @click="search()" src="@/static/icon/icon_search.png" mode="aspectFill" class="search">
						</image>
					</template>
				</hx-navbar>
			</view>
			<view class="pr pt196 z2">
				<view class="box mlr30">
					<image src="@/static/images/icon_car.png" mode="aspectFill" class="box_bg"></image>
					<view class="fs26 col89 lh34">
						<view class="flex-box">
							<text class="fwb fs34 col1">我的爱车</text>
							<text class="pl20" v-if="!userCar">添加车辆信息享受服务</text>
							<view class="switch_btn ml20" @click="onChangeCar()" v-else>切换</view>
						</view>
					</view>
					<view class="btn_add mt30" v-if="!userCar" @click="onAddCar()">立即添加</view>
					<view class="mt25" v-if="userCar">
						<view class="fs26 lh26 col89">{{userCar.series.name}}</view>
						<view class="mt15 fs36 lh36 fwb col1">{{userCar.car_no}}</view>
					</view>
				</view>
				<view class="pt5 fs26 col5 table plr15">
					<view class="w20 tc mt35" v-for="(item,index) in navigationList" @click="navigation(item)">
						<image :src="item.icon_image_text" mode="aspectFill" class="ico80"></image>
						<view class="mt10">{{item.name}}</view>
					</view>
				</view>
				<view class="flex-box mt50 plr30">
					<view class="title flex-grow-1">附近门店</view>
					<navigator url="/pages/stores_list/stores_list" class="col89 fs24" hover-class="none">查看更多+
					</navigator>
				</view>
				<store-list :shop-list="shopList" :type="1"></store-list>

				<view class="flex-box mt50 plr30">
					<view class="title flex-grow-1">养车知识</view>
					<navigator open-type="switchTab" url="/pages/car_knowledge/car_knowledge" class="col89 fs24"
						hover-class="none">查看更多+</navigator>
				</view>
				<view class="pt30">
					<uv-waterfall ref="waterfall" v-model="newsList" :add-time="10" :left-gap="leftGap"
						:right-gap="rightGap" :column-gap="columnGap" @changeList="changeList">
						<!-- 第一列数据 -->
						<template v-slot:list1>
							<!-- 为了磨平部分平台的BUG，必须套一层view -->
							<view>
								<view @click="onNewsDetail(item.id)" v-for="(item, index) in list1" :key="item.id"
									class="waterfall_item">
									<image :src="item.image_text" mode="widthFix" class="img">
									</image>
									<view class="mt20 fs30 col1 m-ellipsis-l2">{{item.name}}</view>
								</view>
							</view>
						</template>
						<!-- 第二列数据 -->
						<template v-slot:list2>
							<!-- 为了磨平部分平台的BUG，必须套一层view -->
							<view>
								<view @click="onNewsDetail(item.id)" v-for="(item, index) in list2" :key="item.id"
									class="waterfall_item">
									<image :src="item.image_text" mode="widthFix" class="img">
									</image>
									<view class="mt20 fs30 col1 m-ellipsis-l2">{{item.name}}</view>

								</view>
							</view>
						</template>
					</uv-waterfall>

					<view class="nothing" v-if="newsMore.nothing">
						<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
						<text>暂无内容</text>
					</view>
					<view class="g-btn3-wrap" v-else>
						<view class="g-btn3" @click="fetchNews">{{newsMore.text}}</view>
					</view>
				</view>
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
					back: false,
					maxSlot: true,
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
				},
				currentCity: null,
				navigationList: [],
				shopList: [],
				newsList: [],
				newsMore: {
					page: 1
				},
				list1: [], // 瀑布流第一列数据
				list2: [], // 瀑布流第二列数据
				leftGap: 15,
				rightGap: 15,
				columnGap: 10,

				userCar: null
			}
		},
		onLoad() {
			let page = this;
			if (this.$core.getUserinfo()) {
				this.getUserCar();
			}
			this.$core.getLocation();
			uni.$on(app.globalData.Event.CurrentCityChange, function(currentCity) {
				page.currentCity = currentCity;
				page.refreshPage();
			})

			uni.$on("user_update", function() {
				page.getUserCar();
			})
		},
		computed: {
			imageStyle(item) {
				return item => {
					const v = uni.upx2px(750) - this.leftGap - this.rightGap - this.columnGap;
					const w = v / 2;
					const rate = w / item.w;
					const h = rate * item.h;
					return {
						width: w + 'px',
						height: h + 'px'
					}
				}
			}
		},
		onReachBottom() {
			this.fetchNews()
		},
		onPullDownRefresh() {
			this.getUserCar();
			this.refreshPage();
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onUnload() {
			uni.$off(app.globalData.Event.CurrentCityChange, this);
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		methods: {
			search() {
				uni.navigateTo({
					url: '/pages/search/search'
				})
			},
			onAddCar() {
				if (!this.$core.getUserinfo(true)) {
					return;
				}
				uni.navigateTo({
					url: '/pages/add_car/add_car',
					events: {
						setCarSuccess: data => {
							this.getUserCar();
						}
					}
				})
			},
			onChangeCar() {
				if (!this.$core.getUserinfo(true)) {
					return;
				}
				uni.navigateTo({
					url: '/pages/switch_my_car/switch_my_car',
					events: {
						setCarSuccess: data => {
							this.getUserCar();
						}
					}
				})
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
			refreshPage() {
				//金刚区
				this.$core.get({
					url: 'xiluxc.common/navication',
					data: {},
					loading: false,
					success: (ret) => {
						this.navigationList = ret.data;
					}
				})
				//附近门店
				let currentCity = this.currentCity;
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude
				this.$core.get({
					url: 'xiluxc.shop',
					data: {
						lat: lat,
						lng: lng,
						pagesize: 2,
						sort: "distance",
						order: 'asc'
					},
					loading: false,
					success: (ret) => {
						this.shopList = ret.data.data;
					}
				})
				//文章
				this.fetchNews();
				uni.stopPullDownRefresh();
			},
			//金刚区
			navigation(item) {
				if (item.type == '1') {
					if (item.jump_type == 'switchtab') {
						uni.switchTab({
							url: item.url
						})
					} else {

						uni.navigateTo({
							url: item.url
						})
					}
				} else if (item.type == '2') {
					location.href = item.url;
				} else if (item.type == '3') {
					uni.navigateToMiniProgram({
						appId: item.mini_appid,
						path: item.url
					})
				}
			},
			fetchNews() {
				this.$util.fetch(this, 'xiluxc.news', {
					pagesize: 10
				}, 'newsMore', 'newsList', 'data', data => {

				})
			},
			onNewsDetail(id) {
				uni.navigateTo({
					url: '/pages/car_knowledge_info/car_knowledge_info?id=' + id
				})
			},
			chooseAddress() {
				let page = this;
				let currentCity = this.currentCity;
				uni.chooseLocation({
					latitude: currentCity ? currentCity.pois.latitude : app.globalData.location.latitude,
					longitude: currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude,
					success(res) {
						page.$core.getCityByLat(res.latitude, res.longitude)
						// currentCity.pois = res;
						// page.$core.setCurrentCity(currentCity);
					},
					fail(res) {
						console.log(res)
					}
				})
			},
			changeList(e) {
				this[e.name].push(e.value);
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

	.box {
		width: 690rpx;

		background: #FFFFFF;
		box-shadow: 0px 0px 10rpx 0px rgba(184, 189, 202, 0.15);
		border-radius: 20rpx;
		position: relative;
		padding: 30rpx;

		.box_bg {
			width: 238rpx;
			height: 184rpx;
			position: absolute;
			right: 23rpx;
			top: 15rpx;
			z-index: 1;
		}

		.btn_add {
			width: 150rpx;
			height: 60rpx;
			line-height: 60rpx;
			text-align: center;
			background: linear-gradient(180deg, #F5603B 0%, #FA8C59 100%);
			border-radius: 40rpx;
			font-size: 24rpx;
			color: #FFFFFF;
		}
	}

	.w20 {
		width: 20%;
		display: inline-block;
		vertical-align: top;
	}


	.waterfall_item {
		width: 335rpx;
		margin-bottom: 40rpx;

		.img {
			width: 100%;
			border-radius: 20rpx;
		}
	}

	.switch_btn {
		width: 70rpx;
		height: 40rpx;
		line-height: 40rpx;
		text-align: center;
		font-size: 20rpx;
		color: #FFFFFF;
		background: linear-gradient(180deg, #F5603B 0%, #FA8C59 100%);
		border-radius: 40rpx;
	}
</style>