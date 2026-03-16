<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item" v-for="(item,index) in packageList" :key="index">
					<view class="flex-box">
						<image :src="item.brand?item.brand.logo:item.shop.image_text" mode="aspectFill" class="user_img"></image>
						<view class="flex-grow-1 plr10 fs30 col1 m-ellipsis">{{item.brand?item.brand.brand_name:item.shop.name}}</view>
						<image v-if="!item.brand" @click="onLocation(item.shop)" src="@/static/icon/icon_address.png" mode="aspectFill" class="ico30"></image>
					</view>
					<view class="flex-box mt20">
						<image :src="item.package_image" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl30">
							<view class="m-ellipsis fs30 col1 fwb lh30"><text v-if="item.status_text" class="col10">({{item.status_text}})</text>{{item.package_name}}</view>
							<view class="fs24 lh24 col4 mt20"></view>
							<view class="mt15 flex-box">
								<view class="flex-grow-1 col10 fs24 lh30">¥<text class="fs30">{{item.pay_fee}}</text></view>
								<view @click.stop="onPackageDetail(item.id)" :hover-class="none" class="btn_view">查看券码</view>
							</view>
						</view>
					</view>
					<view @click="onPackageDetail(item.id)">
						<view class="mini_box">
							<view class="fs36 col4 lh36 fwb">{{item.total_num}}</view>
							<view class="mt10 col5 fs26 lh26">全部</view>
						</view>
						<view class="mini_box">
							<view class="fs36 col4 lh36 fwb">{{item.unuse_num}}</view>
							<view class="mt10 col5 fs26 lh26">待使用</view>
						</view>
						<view class="mini_box">
							<view class="fs36 col4 lh36 fwb">{{item.use_num}}</view>
							<view class="mt10 col5 fs26 lh26">已使用</view>
						</view>
						<view class="mini_box" @click.stop="onComboShop(item.package_id)">
							<view class="fs36 col4 lh36 fwb">{{item.shop_num}}</view>
							<view class="mt10 col5 fs26 lh26">可用门店</view>
						</view>
					</view>
				</view>
				
				<view class="nothing" v-if="packageMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{packageMore.text}}</view>
				</view>
				
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				packageList: [],
				packageMore: {page: 1}
			}
		},
		onLoad(options) {
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetch(){
				this.$util.fetch(this, 'xiluxc.user_package/lists', {
					pagesize: 10
				}, 'packageMore', 'packageList', 'data', data => {
						
				})
			},
			onComboShop(packageId){
				uni.navigateTo({
					url: '/pages/combo_stores_list/combo_stores_list?package_id='+packageId
				})
			},
			onPackageDetail(id){
				uni.navigateTo({
					url: "/pages/combo_detail/combo_detail?id="+id
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
		background: #FFFFFF;
		border-radius: 15rpx;
		padding: 30rpx;

		&+& {
			margin-top: 30rpx;
		}

		.cover {
			width: 150rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}

		.btn_view {
			width: 140rpx;
			height: 60rpx;
			line-height: 60rpx;
			text-align: center;
			font-size: 24rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 33rpx;
		}

		.user_img {
			width: 35rpx;
			height: 35rpx;
			border-radius: 50%;
		}
	}

	.mini_box {
		width: 142rpx;
		height: 125rpx;
		background: rgba(254, 75, 1, 0.1);
		border-radius: 20rpx;
		margin-top: 20rpx;
		margin-right: 20rpx;
		display: inline-block;
		vertical-align: top;
		padding-top: 25rpx;
		text-align: center;
		&:nth-of-type(4n){margin-right: 0;}
	}
</style>