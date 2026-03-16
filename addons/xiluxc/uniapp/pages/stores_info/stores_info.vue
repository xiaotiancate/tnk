<template>
	<view>
		<view class="container bg-f5">
			<view class="p30 bg-white">
				<swiper class="swiper" circular :autoplay="true" :interval="3000" :duration="1000">
					<swiper-item v-for="(img,index) in shop.images_text" :key="index">
						<image :src="img" mode="aspectFill" class="banner"></image>
					</swiper-item>
				</swiper>
				<view class="mt30 flex-box">
					<view class="flex-grow-1 pr30">
						<view class="fwb fs36 col1 lh36">{{shop.name}}</view>
						<view class="flex-box mt20">
							<!-- <image src="@/static/icon/icon_star.png" mode="widthFix" class="star"
								v-for="(item,index) in parseInt(shop.point)"></image> -->
								<htz-rate readonly v-model="shop.point" size="26" gutter="4" disHref="../../static/icon/icon_star_uc.png"
									checkedHref="../../static/icon/icon_star.png"></htz-rate>
							<view class="flex-grow-1 pl20 col2 fs24">{{shop.point}}分</view>
						</view>
					</view>
					<button class="kefu_btn" hover-class="none" open-type="contact">
						<image src="@/static/icon/icon_kefu.png" mode="aspectFill" class="ico40"></image>
						<view class="mt10">客服</view>
					</button>
				</view>
				<view class="mt25 flex-box">
					<view class="col89 fs26">营业时间</view>
					<view class="flex-grow-1 pl20 col3 fs26">{{shop.starttime}}-{{shop.endtime}}</view>
					<view class="col89 fs24">已售{{shop.sales}}</view>
				</view>
				<view v-if="shop.shop_tag.length>0">
					<view class="label" v-for="(tag,index) in shop.shop_tag" :key="index">{{tag.name}}</view>
				</view>
				<view class="box mt30 flex-box">
					<view class="flex-grow-1 pr30">
						<view class="fwb fs30 col1">{{shop.address}}</view>
						<view class="mt20 fs26 col89">距你{{shop.distance}}</view>
					</view>
					<view class="tc" @click="onLocation()">
						<image src="@/static/icon/icon_map.png" mode="aspectFill" class="ico40"></image>
						<view class="mt15 col89 fs24">导航</view>
					</view>
					<view class="tc ml50" @click="onCall()">
						<image src="@/static/icon/icon_phone.png" mode="aspectFill" class="ico40"></image>
						<view class="mt15 col89 fs24">电话</view>
					</view>
				</view>





			</view>
			<view class="p30">
				<view class="box1" v-if="shop.recharge.length>0">
					<view class="flex-box fs30 col1">
						<view class="fwb fs34 flex-grow-1">会员充值</view>
						<!-- <view class="pl35 flex-grow-1 fwb">优惠券</view> -->
						<view class="col4" @click="onRecharge()">立即充值</view>
					</view>
					<view class="pt5">
						<view class="pay_label" v-for="(item,index) in shop.recharge" :key="index">充{{item.money}}<text v-if="item.extra_money>0">送{{item.extra_money}}</text> </view>
					</view>
				</view>
				<view class="vip_box mt30" v-if="shop.vip.length>0" v-for="(item,index) in shop.vip" :key="index">
					<image src="@/static/images/vip_bg.png" mode="aspectFill" class="vip_bg"></image>
					<view class="vip_info flex-box">
						<view class="flex-grow-1 pr15">
							<view class="flex-box">
								<image src="@/static/icon/icon_vip.png" mode="aspectFill" class="ico36"></image>
								<view class="vip_level">{{item.name}}</view>
							</view>
							<view class="vip_tips mt15" v-if="shop.is_vip.status==1">{{shop.is_vip.expire_in_text}}过期</view>
							<view class="vip_tips mt15" v-else>购买会员店铺服务享85折</view>
						</view>
						<view class="vip_btn" @click="onVip(item.id)" v-if="shop.is_vip.status==0">¥{{item.salesprice}} 立即开通</view>
						<view class="vip_btn" @click="onUseShops(item.id)" v-else-if="shop.is_vip.status==1">可用门店</view>
						<view class="vip_btn" @click="onVip(item.id)" v-else-if="shop.is_vip.status==2">会员续费</view>
					</view>
				</view>
			</view>
			<block v-if="shop.coupons.length>0">
				<view class="mt30 col1 fwb fs30 pl30">优惠券</view>
				<scroll-view scroll-x="true" class="coupons_scroll">
					<view class="coupons_item" v-for="(coupon,index) in shop.coupons" :key="index">
						<image src="@/static/images/icon_coupons.png" mode="aspectFill" class="bg"></image>
						<navigator hover-class="none" class="coupons_item_info p20">
							<view class="fs24 colf lh24 m-ellipsis">{{coupon.name}}</view>
							<view class="flex-box mt15">
								<view class="flex-grow-1 colf fs22 lh34 pr10 m-ellipsis">¥<text class="fwb fs34">{{coupon.money}}</text>
								</view>
								<view class="mini_btn_white" v-if="coupon.is_receive_count==0" @click="onReceive(index)">领取</view>
								<view class="mini_btn_white" v-else>已领取</view>
							</view>
							<view class="mt45 colf_7 fs22 lh22 m-ellipsis">{{coupon.use_end_time_text}}前到期</view>
						</navigator>
					</view>
				</scroll-view>
			</block>
			<view class="p30">
				<block v-if="shop.shop_services.length>0">
					<view class="title mt10">店铺服务</view>
					<view class="stores_item flex-box" v-for="(item,index) in shop.shop_services" @click="onServiceDetail(item.shop_service.id)">
						<image :src="item.shop_service.image_text" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl25">
							<view class="fwb fs34 col1 lh34">{{item.service.name}}</view>
							<view class="fs24 col89 lh24 mt20">{{item.shop_service.sub_title}}</view>
							<view class="flex-box mt20 fs24">
								<block v-if="shop.is_vip.status !=1">
									<view class="col4">¥<text class="fs30">{{item.shop_service.salesprice}}</text></view>
									<view class="col7 plr20 flex-grow-1">VIP价¥{{item.shop_service.vip_price}}</view>
								</block>
								<block v-else>
									<view class="col4">¥<text class="fs30">{{item.shop_service.vip_price}}</text></view>
									<view class="col1 pl20 pr10 flex-grow-1 tdl">非VIP价¥{{item.shop_service.salesprice}}</view>
								</block>
								<view class="btn_pay">购买</view>
							</view>
						</view>
					</view>
				</block>
				<block v-if="shop.shop_package.length>0">
					<view class="title mt40">套餐服务</view>
					<view class="stores_item flex-box" v-for="(item,index) in shop.shop_package" @click="onPackageDetail(item.id)">
						<image :src="item.image_text" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl25">
							<view class="fwb fs34 col1 lh34">{{item.name}}</view>
							<view class="fs24 col89 lh24 mt20">{{item.sub_title}}</view>
							<view class="flex-box mt20 fs24">
								<block v-if="shop.is_vip.status !=1">
									<view class="col4">¥<text class="fs30">{{item.salesprice}}</text></view>
									<view class="col7 pl20 pr10 flex-grow-1">VIP价¥{{item.vip_price}}</view>
								</block>
								<block v-else>
									<view class="col4">¥<text class="fs30">{{item.vip_price}}</text></view>
									<view class="col1 pl20 pr10 flex-grow-1 tdl">非VIP价¥{{item.salesprice}}</view>
								</block>
								<view class="btn_pay">购买</view>
							</view>
						</view>
					</view>
				</block>
				<view class="flex-box mt40" v-if="commentList.length>0">
					<view class="title flex-grow-1">用户评价</view>
					<navigator :url="'/pages/appraise/appraise?id='+shopId" class="fs26 col89" hover-class="none">查看更多+</navigator>
				</view>
				<view class="appraise_item" v-for="(comment,index) in commentList" :key="index">
					<view class="flex-box">
						<image :src="comment.user.avatar" mode="aspectFill" class="user_img"></image>
						<view class="flex-grow-1 plr25">
							<view class="fs30 col1 lh30 m-ellipsis">{{comment.user.nickname}}</view>
							<view class="mt15 fs24 col89 lh24">{{comment.createtime_text}}</view>
						</view>
						<view class="flex-box">
							<htz-rate readonly v-model="comment.avg_star" size="26" gutter="4" disHref="../../static/icon/icon_star_uc.png"
								checkedHref="../../static/icon/icon_star.png"></htz-rate>
								<view class="col2 fs24 pl10">{{comment.avg_star}}分</view>
							<!-- <image src="@/static/icon/icon_star.png" v-for="(item,index) in parseInt(comment.avg_star)" mode="widthFix"
								class="star"></image> -->
						</view>
					</view>
					<view class="mt25 fs30 col1 lh42">{{comment.content}}</view>
					<view class="pt10" v-if="comment.images_text.length>0">
						<image :src="img" mode="aspectFill" class="cover"
							v-for="(img,index2) in comment.images_text" @click="imgPrev(index,index2)"></image>
							
					</view>
				</view>
				<view class="title mt40">店铺介绍</view>
				<view class="rich_box">
					<!-- 富文本 -->
					<u-parse :content="shop.description"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	const app =  getApp();
	import htzRate from '@/components/htz-rate/htz-rate.vue';
	export default {
		components: {
			htzRate,
		},
		data() {
			return {
				shopId: 0,
				shop: {
					name: '',
					starttime: '',
					endtime: '',
					point: 0,
					images_text: [],
					address: '',
					distance: '',
					shop_tag: [],
					recharge: [],
					coupons: [],
					shop_services: [],
					shop_package: [],
					vip:[],
					
				},
				commentList: []
			}
		},
		onLoad(options) {
			this.shopId = options.id || 0;
			this.fetchDetail();
			this.fetchComment()
		},
		onShareAppMessage() {
			return {
				title: this.shop.name,
				imageUrl: this.shop.image_text
			}
		},
		onShareTimeline() {
			return {
				title: this.shop.name,
				imageUrl: this.shop.image_text
			}
		},
		methods: {
			fetchDetail(){
				let currentCity = this.$core.getCurrentCity();
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude
				this.$core.get({url: 'xiluxc.shop/detail',data: {shop_id: this.shopId,lat: lat,lng:lng},loading: false,success: (ret) => {
						this.shop = ret.data;
					}
				})
			},
			fetchComment(){
				this.$core.get({url: 'xiluxc.comment',data: {shop_id: this.shopId,pagesize:2},loading: false,success: (ret) => {
						this.commentList = ret.data.data;
					}
				})
			},
			//导航
			onLocation(){
				uni.openLocation({
					latitude: Number(this.shop.lat),
					longitude: Number(this.shop.lng),
					name: this.shop.name,
					address: this.shop.address
				})
			},
			onCall(){
				let contactMobile = this.shop.concat_mobile;
				uni.makePhoneCall({
					phoneNumber: contactMobile
				})
			},
			//充值
			onRecharge(){
				// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/recharge/recharge?id=' + this.shop.id
				})
				// #endif
				// #ifdef H5
					this.$core.checkH5Openid('pages/recharge/recharge?id='+this.shop.id)
				// #endif
			},
			//会员
			onVip(id){
				// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/vip_member/vip_member?id='+id+'&shop_id='+this.shopId,
					events:{
						vipBuySuccess: data=>{
							this.fetchDetail();
						}
					}
				})
				// #endif
				// #ifdef H5
					this.$core.checkH5Openid('pages/vip_member/vip_member?id='+id+'&shop_id='+this.shopId)
				// #endif
				
			},
			//可用门店
			onUseShops(id){
				uni.navigateTo({
					url: '/pages/vip_stores_list/vip_stores_list?shop_vip_id='+id
				})
			},
			//领取优惠券
			onReceive(index){
				let coupons = this.shop.coupons;
				let coupon = coupons[index];
				if(!this.$core.getUserinfo(true)){
					return;
				}
				this.$core.post({url: 'xiluxc.coupon/receive',data: {coupon_id: coupon.id},success: ret => {
				        uni.showToast({
				        	title:'领取成功',
				        })
						coupons[index].is_receive_count = 1;
						this.shop.coupons = coupons;
				    },
				    fail: err => {
						uni.showModal({
							title: '提示',
							content: err.msg,
						})
						return false;
					}
				});
			},
			//服务详情
			onServiceDetail(id){
				uni.navigateTo({
					url: '/pages/service_detail/service_detail?id='+id+'&shop_id='+this.shopId
				});
			},
			//套餐详情
			onPackageDetail(id){
				uni.navigateTo({
					url: '/pages/package_detail/package_detail?id='+id+'&shop_id='+this.shopId
				});
			},
			imgPrev(index,index2){
				uni.previewImage({
					current: index2,
					urls: this.commentList[index].images_text
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.swiper {
		width: 100%;
		height: 350rpx;

		.banner {
			display: block;
			width: 100%;
			height: 100%;
			border-radius: 20rpx;
		}
	}

	.star {
		width: 26rpx;
		height: 26rpx;
	}

	.star+.star {
		margin-left: 4rpx;
	}

	.kefu_btn {

		text-align: center;
		border-radius: 0;
		color: #555;
		font-size: 24rpx;
		line-height: 24rpx;
	}

	.label {
		line-height: 50rpx;
		height: 50rpx;
		background: rgba(254, 75, 1, 0.1);
		border-radius: 6rpx;
		padding: 0 12rpx;
		display: inline-block;
		vertical-align: top;
		font-size: 24rpx;
		color: #FE4B01;
		margin-right: 20rpx;
		margin-top: 20rpx;
	}

	.box {
		width: 690rpx;
		min-height: 150rpx;
		background: #F5F7FB;
		border-radius: 15rpx;
		padding-left: 30rpx;
		padding-right: 40rpx;
		padding-top: 30rpx;
		padding-bottom: 30rpx;
	}

	.box1 {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 26rpx;
		padding: 30rpx;
	}

	.pay_label {
		display: inline-block;
		vertical-align: top;
		height: 58rpx;
		line-height: 58rpx;
		margin-top: 20rpx;
		margin-right: 20rpx;
		background: #FE4B01;
		border-radius: 10rpx;
		font-size: 26rpx;
		color: #FFFFFF;
		padding: 0 18rpx;
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
			font-size: 40rpx;
			color: #F4D8A9;
			line-height: 40rpx;
			font-weight: bold;
			padding-left: 15rpx;
		}

		&_tips {
			font-size: 28rpx;
			color: #B0A189;
		}

		&_btn {
			
			padding: 0 20rpx;
			line-height: 70rpx;
			text-align: center;
			font-size: 24rpx;
			color: #714000;
			background: #F3DAAE;
			border-radius: 35rpx;
		}
	}

	.coupons_scroll {
		width: 100%;
		height: 184rpx;
		margin-top: 30rpx;
		white-space: nowrap;

		.coupons_item {
			width: 254rpx;
			height: 184rpx;
			position: relative;
			margin-left: 30rpx;
			display: inline-block;
			vertical-align: top;

			&:last-child {
				margin-right: 30rpx;
			}

			.bg {
				width: 254rpx;
				height: 184rpx;
				position: absolute;
				z-index: 1;
				top: 0;
				left: 0;
			}

			&_info {
				width: 254rpx;
				height: 184rpx;
				position: relative;
				z-index: 2;
			}
		}
	}

	.stores_item {
		width: 690rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-top: 30rpx;

		.cover {
			width: 180rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}

		.btn_pay {
			width: 100rpx;
			height: 50rpx;
			line-height: 50rpx;
			text-align: center;
			font-size: 24rpx;
			color: #FFFFFF;
			background: #FE4B01;
			border-radius: 30rpx;
		}
	}

	.appraise_item {
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
		margin-top: 30rpx;
		padding: 30rpx;

		.user_img {
			width: 75rpx;
			height: 75rpx;
			border-radius: 50%;
		}

		.star {
			width: 26rpx;
			height: 26rpx;
			margin-left: 4rpx;
		}

		.cover {
			width: 200rpx;
			height: 200rpx;
			border-radius: 15rpx;
			margin-top: 15rpx;
		}

		.cover+.cover {
			margin-left: 15rpx;
		}
	}
	.rich_box{
		margin-top: 30rpx;
		width: 690rpx;
		padding: 25rpx 20rpx;
		font-size: 30rpx;
		color: #333333;
		background: #FFFFFF;
		border-radius: 15rpx;
	}
</style>