<template>
	<view>
		<view class="container bg-f5 flex flex-col">
			<scroll-view scroll-x="true" class="tab_nav">
				<view class="tab_item" :class="[query.state==item.id?'active':'']" @click="chooseNav(item.id)"
					v-for="(item,index) in tab">{{item.name}}</view>
			</scroll-view>
			<scroll-view scroll-y="true" class="flex-grow-1 plr30 pt30" @scrolltolower="fetch">
				<view class="item" v-for="(order,index) in orderList" >
					<view class="flex-box">
						<view class="flex-grow-1 fwb fs30 col1 pr30">{{order.shop?order.shop.name:'门店已注销'}}</view>
						<view class="fs26" :class="{col4:order.state !=-1}">{{order.state_text}}</view>
<!-- 						<view class="col5 fs26">已完成</view> -->
					</view>
					<view class="mt25 flex-box"  @click="onDetail(order.id)">
						<image :src="order.order_item.image" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl30">
							<view class="m-ellipsis fs30 col1 fwb lh30">{{order.order_item.title}}（{{order.order_item.sku_text}}）</view>
							<view class="fs24 lh24 col4 mt20">预约时间 {{order.appoint_date_text}}</view>
							<view class="mt15 flex-box">
								<view class="flex-grow-1 col10 fs24 lh30">¥<text class="fs30">{{order.pay_fee}}</text></view>
							<view class="btn_cancel mr20" v-if="order.state==0" @click.stop="onRefund(index)">取消</view>
							<view v-if="order.state == 0" class="btn_view">查看券码</view>
							<view v-if="order.state == 1" @click.stop="onComment(index)" class="btn_view">评价</view>
							</view>
						</view>
					</view>
				</view>
				
				<view class="nothing" v-if="orderMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{orderMore.text}}</view>
				</view>
				<view class="p30"></view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				tab: [{id:'all',name:'全部'}, {id:'unuse',name: '待使用'}, {id: 'uncomment',name: '待评价'}, {id: 'finished',name: '已完成'}],
				query: {pagesize: 10, state:'all'},
				orderList:[],
				orderMore: {page: 1}
			}
		},
		onLoad(options) {
			this.query.state = options.state || 'all'
			this.refresh()
		},
		onReachBottom() {
			this.fetch()
		},
		methods: {
			chooseNav(id) {
				this.query.state = id;
				this.refresh();
			},
			refresh(){
				this.orderList = [];
				this.orderMore = {page: 1};
				this.fetch();
			},
			fetch() {
				this.$util.fetch(this, 'xiluxc.order/lists', this.query, 'orderMore', 'orderList', 'data', data => {
					
				})
			},
			onDetail(id){
				uni.navigateTo({
					url: '/pages/order_size/order_size?id='+id
				})
			},
			onComment(index){
				let orderList = this.orderList;
				let order = orderList[index];
				uni.navigateTo({
					url: '/pages/rate/rate?id='+order.id,
					events:{
						commentSuccess: data=>{
							this.refresh();
						}
					},
				})
			},
			onRefund(index){
				let page = this;
				let orderList = this.orderList;
				let order = orderList[index];
				uni.showModal({
					title: '提示',
					content:"确定要取消服务？",
					success(res) {
						if(res.confirm){
							page.$core.post({url:'xiluxc.order/aftersale',data:{id: order.id},success:(ret)=>{
								uni.showToast({
									title: ret.msg,
									icon: 'none'
								})
								page.orderList[index] = ret.data;
								page.$forceUpdate();
							},
							fail:ret=>{
								uni.showModal({
									title: '提示',
									content: ret.msg,
									showCancel:false
								})
								return false;
							}});
						}
					}
				})
				
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
	
		min-height: auto;
		/* #ifdef MP*/
		height: 100vh;
		/* #endif*/
		/* #ifndef MP */
		height: calc(100vh - 44px);
		/* #endif */
	}

	.tab_nav {
		height: 96rpx;
		white-space: nowrap;

		.tab_item {
			line-height: 96rpx;
			height: 96rpx;
			position: relative;
			margin-left: 116rpx;
			font-size: 30rpx;
			color: #333333;
			display: inline-block;
			vertical-align: top;

			&:first-child {
				margin-left: 30rpx;
			}

			&:last-child {
				margin-right: 0;
			}

			&.active {
				font-weight: bold;
				font-size: 36rpx;
				color: #101010;
			}

			&.active::after {
				content: '';
				width: 25rpx;
				height: 6rpx;
				background: #FE4B01;
				border-radius: 3rpx;
				position: absolute;
				bottom: 4rpx;
				left: 50%;
				transform: translateX(-50%);
			}
		}
	}

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
	}
	.btn_view{
		width: 140rpx;
		height: 60rpx;
		line-height: 60rpx;
		text-align: center;
		font-size: 24rpx;
		color: #FFFFFF;
		background: #FE4B01;
		border-radius: 33rpx;
	}
	.btn_cancel{
		width: 140rpx;
		height: 60rpx;
		line-height: calc(60rpx - 2px);
		text-align: center;
		font-size: 24rpx;
		border-radius: 33rpx;
		color: #999;
		border: 1px solid #999;
	}
</style>