<template>
	<view>
		<view class="container">
			<view class="page-head">
				<hx-navbar ref="hxnb" :config="config"></hx-navbar>
			</view>
			<map class="map" scale="11" :latitude="latitude" :longitude="longitude" :markers="markers"
				@markertap="onShopDetail" @callouttap="onShopDetail"></map>
			<view class="top_nav">
				<view class="flex-box">
					<view class="search_nav flex-box">
						<input type="text" placeholder="请输入服务或门店名" confirm-type="search" v-model="query.q"
							@confirm="search" class="flex-grow-1 fs28 pr20 col0" placeholder-class="colb" />
						<image src="@/static/icon/icon_search.png" mode="aspectFill" class="ico28" @click="search">
						</image>
					</view>
				</view>
				<scroll-view scroll-x="true" class="mid_tab clearfix">
					<view class="welfare_item" :class="{ac:query.distance==item}" @click="bindDistanceChange(item)"
						v-for="(item,index) in nearbyOptions">{{item}}km内</view>
				</scroll-view>

			</view>

			<scroll-view scroll-x class="bottom_nav" v-if="shopList.length>0">
				<view class="stores_item p30" v-for="(shop,index) in shopList">
					<view class="flex-box" @click="onShopDetail(shop.id)">
						<view class="cover">
							<image :src="shop.image_text" mode="aspectFill" class="cover"></image>
							<view class="tips" v-if="shop.type==2">
								<image src="@/static/icon/icon_liansuo.png" mode="aspectFill" class="icon_tips">
								</image>
								<view class="tips_text"> 连锁</view>
							</view>
						</view>
						<view class="flex-grow-1 pl20">
							<view class="flex flex-align-baseline lh36 col89 fs24">
								<view class="fs36 fwb col1 flex-grow-1 pr20 m-ellipsis">{{shop.name}}</view>
								<view>已售{{shop.sales}}</view>
							</view>
							<view class="flex-box mt20">
								<htz-rate readonly v-model="shop.point" size="26" gutter="4"
									disHref="../../static/icon/icon_star_uc.png"
									checkedHref="../../static/icon/icon_star.png"></htz-rate>
								<view class="flex-grow-1 pl20 col2 fs24">{{shop.point}}分</view>
								<view class="col89 fs26">{{shop.distance}}</view>
							</view>
							<view class="mt20 flex-box">
								<view class="flex-grow-1 m-ellipsis pr20 col5 fs26">{{shop.address}}</view>
								<image src="@/static/icon/icon_address.png" mode="aspectFill" class="ico30"></image>
							</view>
						</view>

					</view>


				</view>
			</scroll-view>


		</view>
	</view>
</template>

<script>
	import htzRate from '@/components/htz-rate/htz-rate.vue';
	export default {
		components: {
			htzRate
		},
		data() {
			return {
				config: {
					color: '#101010',
					back: true,
					title: '地图',
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#000', '#000']],
				},
				query: {
					sort: 'distance',
					order: 'asc',
					q: '',
					lat: 31.184614,
					lng: 121.302959,
					distance: 5,
					pagesize: 999
				},
				shopList: [],
				markers: [],
				latitude: '',
				longitude: '',
				nearbyOptions: [1, 3, 5, 10, 20],
			}
		},
		onLoad() {
			let page = this;
			//获取定位
			this.$core.getLatLng(function(latitude, longitude) {
				page.latitude = latitude;
				page.longitude = longitude;
				page.query.lat = latitude;
				page.query.lng = longitude;
				page.fetchShops();
			})
		},
		methods: {
			search() {
				this.fetchShops();
			},
			bindDistanceChange(distance) {
				this.query.distance = distance;
				this.fetchShops();
			},
			fetchShops: function() {
				let query = this.query;
				this.$core.get({
					url: 'xiluxc.shop',
					data: query,
					loading: true,
					success: ret => {
						let markers = [];
						for (let i = 0; i < ret.data.data.length; i++) {
							markers.push({
								id: ret.data.data[i].id,
								latitude: Number(ret.data.data[i].lat),
								longitude: Number(ret.data.data[i].lng),
								iconPath: '../../static/icon/location.png',
								width: 28,
								height: 19,
								callout: {
									content: ret.data.data[i].name, //文本
									color: '#000000', //文本颜色
									borderRadius: 3, //边框圆角
									borderWidth: 1, //边框宽度
									borderColor: '#ffffff', //边框颜色
									bgColor: '#ffffff', //背景色
									padding: 5, //文本边缘留白
									display: "ALWAYS",
									textAlign: 'center' //文本对齐方式。有效值: left, right, center
								}
							});
						}
						this.shopList = ret.data.data;
						this.markers = markers;
						this.$forceUpdate();
					}
				});
			},
			onShopDetail(e) {
				uni.navigateTo({
					url: '/pages/stores_info/stores_info?id=' + e.detail.markerId
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.map {
		width: 100%;
		height: 100vh;
		position: absolute;
		z-index: 1;
	}

	.w100 {
		width: 100%;
	}

	.top_nav {
		width: 690rpx;
		background: #FFFFFF;
		box-shadow: 0rpx 0rpx 20rpx 0rpx rgba(0, 0, 0, 0.03);
		border-radius: 6rpx;
		/* #ifndef MP */
		top: 142rpx;
		/* #endif */
		/* #ifdef MP */
		top: 186rpx;

		/* #endif */
		left: 30rpx;
		position: fixed;
		z-index: 99;
		padding: 25rpx 30rpx 30rpx;

		.switch {
			width: 102rpx;
			height: 70rpx;
			line-height: 70rpx;
			text-align: center;
			font-size: 28rpx;
			color: #666666;
			background: #E8EAEF;
			border-radius: 43rpx;

			&.ac {
				background: #FE4B01;
				color: #FFFFFF;
			}
		}

		.search_nav {
			width: 100%;
			height: 70rpx;
			background: #F7F8FB;
			border-radius: 43rpx;
			padding-left: 25rpx;
			padding-right: 35rpx;
		}
	}

	.pop_tab_nav {

		padding-top: 30rpx;
	}

	.pop_scroll {
		height: 400rpx;
		padding: 0 0 0 45rpx;
		position: relative;

		&.i {
			padding: 20rpx 0 0;
		}

		.pop_left {
			width: 200rpx;
			height: 100%;
			float: left;
			background: #fff;
		}

		.pop_left .item {
			width: 100%;
			height: 100rpx;
			line-height: 100rpx;
			padding-right: 20rpx;
			padding-left: 40rpx;
			color: #333333;
			position: relative;
			font-size: 28rpx;
		}

		.pop_left .item.active {
			color: #FE4B01;
		}

		.pop_left .item.active::after {
			content: '';
			position: absolute;
			z-index: 1;
			width: 5rpx;
			height: 80rpx;
			display: block;
			left: 10rpx;
			top: 50%;
			transform: translateY(-50%);
			background: #FE4B01;
			border-radius: 5rpx;
		}

		.pop_right {
			float: left;
			width: 430rpx;
			height: 100%;
			background: #F6F8FE;
		}

		.scroll_item {
			border: 1px solid #F5EEEE;
			width: 152rpx;
			height: 65rpx;
			line-height: calc(65rpx - 2px);
			text-align: center;
			color: #666666;
			font-size: 24rpx;
			border-radius: 8rpx;
			margin-right: 15rpx;
			margin-top: 20rpx;
			padding: 0 10rpx;
			display: inline-block;
			vertical-align: top;

			&.active {
				border: 1px solid #FE4B01;
				color: #FE4B01;
			}
		}
	}

	.pop_bottom {
		width: 100%;
		height: 98rpx;
		background: #FFFFFF;
		box-shadow: 0rpx -1rpx 0rpx 0rpx rgba(0, 0, 0, 0.05);
		padding-left: 45rpx;
		padding-right: 30rpx;

		.bt1 {
			width: 152rpx;
			height: 74rpx;
			background: rgba(254, 75, 1, .1);
			border-radius: 40rpx;
			font-size: 28rpx;
			color: #FE4B01;
			line-height: 74rpx;
			text-align: center;
		}

		.bt2 {
			margin-left: 35rpx;
			width: 100%;
			height: 74rpx;
			line-height: 74rpx;
			text-align: center;
			font-size: 28rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 40rpx;
		}
	}

	.city_item {

		font-size: 26rpx;
		color: #333333;
		line-height: 100rpx;

		&.active {
			font-weight: bold;
			color: #FE4B01;
		}
	}

	.bottom_nav {
		bottom: 40rpx;
		width: 100%;
		left: 0;
		position: fixed;
		z-index: 99;
		white-space: nowrap;

	}

	.mid_tab {
		height: 40rpx;
		margin-top: 30rpx;
		white-space: nowrap;
		width: 100%;
	}

	.welfare_item {
		font-size: 22rpx;
		color: #999;
		border-radius: 8rpx;
		line-height: 39rpx;
		background-color: #F7F7F7;
		margin-right: 20rpx;
		padding: 0 15rpx;
		box-sizing: border-box;
		margin-bottom: 10rpx;
		display: inline-block;
		vertical-align: top;

		&.ac {
			background: #FE4B01;
			color: #FFFFFF;
		}
	}

	.stores_item {
		margin-right: 30rpx;
		width: 630rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		border: 1px solid #EEEEEE;
		display: inline-block;
		vertical-align: top;

		&:first-child {
			margin-left: 30rpx;
		}

		.cover {
			width: 180rpx;
			height: 150rpx;
			border-radius: 15rpx;
			position: relative;

			.tips {
				position: absolute;
				left: -5rpx;
				top: -5rpx;
				z-index: 2;
				width: 48rpx;
				height: 31rpx;

				.icon_tips {
					width: 48rpx;
					height: 31rpx;
					position: absolute;
					top: 0;
					left: 0;
					z-index: 1;
				}

				.tips_text {
					width: 48rpx;
					height: 31rpx;
					position: relative;
					z-index: 2;
					font-size: 18rpx;
					color: #FFFFFF;
					line-height: 31rpx;
					text-align: center;
				}
			}
		}



		.star {
			width: 26rpx;
			height: 26rpx;
		}

		.star+.star {
			margin-left: 4rpx;
		}


	}
</style>