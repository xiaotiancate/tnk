<template>
	<view>
		<view class="page-foot">
			<view class="ptb10 plr30" @click="bindScan()">
				<view class="btn2">扫码核销</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item" v-for="(order,index) in orderList" @click="onDetail(order.id)">
					<view class="flex-box fs30 lh30">
						<view class="col89">订单号</view>
						<view class="flex-grow-1 plr15 col1">{{order.order_no}}</view>
						<view class="col4 fs26">已核销</view>
					</view>
					<view class="flex-box mt25">
						<image :src="order.order_item.image" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl30">
							<view class="fwb fs30 col1 lh30 m-ellipsis">{{order.order_item.title}}</view>
							<view class="mt20 col4 fs24 lh24">{{order.order_item.sku_text}} -1</view>
							<view class="col4 fs24 lh30 mt45">¥<text class="fs30">{{order.pay_fee}}</text></view>
						</view>
					</view>
					<view class="flex-box mt25">
						<image :src="order.user.avatar" mode="aspectFill" class="user_img"></image>
						<view class="flex-grow-1 plr15 fs30 col5">{{order.user.nickname}}</view>
						<view class="fs24 col89 lh24">{{order.verifytime_text}}</view>
					</view>
				</view>
				
				<view class="nothing" v-if="orderMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{orderMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				orderList:[],
				orderMore:{page:1}
			}
		},
		onLoad() {
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetch(){
				this.$util.fetch(this, 'xiluxc.order/verifier_list', {pagesize:10}, 'orderMore', 'orderList', 'data', data=>{
				  
				})
			},
			onDetail(id){
				uni.navigateTo({
					url: '/pages/offset_info/offset_info?id='+id
				})
			},
			bindScan(){
				if(!this.$core.getUserinfo(true)){
					return true;
				}
				uni.scanCode({
					success: function (res) {
						console.log('条码类型：', res);
						uni.navigateTo({
							url: '/'+res.path
						})
					}
				});
			
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

		&+& {
			margin-top: 30rpx;
		}

		.cover {
			width: 150rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}

		.user_img {
			width: 40rpx;
			height: 40rpx;
			border-radius: 50%;
		}
	}
	.page-foot{
		background: transparent;
	}
</style>