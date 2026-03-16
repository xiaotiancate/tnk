<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<navigator hover-class="none" :url="'/pages/stores_info/stores_info?id='+item.id" class="item flex-box" v-for="(item,index) in shopList">
					<image :src="item.image_text" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl20">
						<view class="flex flex-align-baseline lh36 col1 fs36">
							<view class="flex-grow-1 m-ellipsis pr10 fwb">{{item.name}}</view>
							<view class="fs24 col89">已售{{item.sales}}</view>
						</view>
						<view class="flex-box mt20 lh26 fs26 col2">
							<image src="@/static/icon/icon_star.png" v-for="(item,index) in parseInt(item.point)" mode="widthFix"
								class="star"></image>
							<view class="fs24 pl15">{{item.point}}分</view>
							<view class="flex-grow-1 tr col89">{{item.distance}}</view>
						</view>
						<view class="mt20 flex-box fs26 lh26 col5">
							<view class="flex-grow-1 pr20 m-ellipsis">{{item.address}}</view>
							<image @click.stop="onLocation(item)" src="@/static/icon/icon_address.png" mode="aspectFill" class="ico30"></image>
						</view>
					</view>
				</navigator>
				<view class="nothing" v-if="shopMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无可用门店</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{shopMore.text}}</view>
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
				shopVipId: 0,
				shopList:[],
				shopMore:{page: 1}
			}
		},
		onLoad(options) {
			this.shopVipId = options.shop_vip_id;
			this.fetch()
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetch() {
				let currentCity = this.$core.getCurrentCity();
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude
				this.$util.fetch(this, 'xiluxc.vip/vip_shops', {
					shop_vip_id: this.shopVipId,
					lat: lat,
					lng:lng,
					pagesize: 10
				}, 'shopMore', 'shopList', 'data', data => {
					
				})
			},
			//导航
			onLocation(shop){
				uni.openLocation({
					latitude: Number(shop.lat),
					longitude: Number(shop.lng),
					name: shop.name,
					address: shop.address
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.item {
		width: 690rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		border: 1px solid #EEEEEE;
&+&{
	margin-top: 30rpx;
}
		.cover {
			width: 180rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}

		.star {
			width: 26rpx;
			height: 26rpx;
			margin-right: 4rpx;
			display: block;
		}
	}
</style>