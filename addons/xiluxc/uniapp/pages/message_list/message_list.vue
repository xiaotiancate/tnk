<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item flex-box" @click="onUserMessage('notice')">
					<image src="@/static/icon/icon_mess1.png" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs34 fwb col1 lh34 m-ellipsis">平台公告</view>
						<view class="fs30 col5 mt25 lh30 m-ellipsis">{{message.notice.message?message.notice.message.title:''}}</view>
					</view>
					<text class="dot" v-if="message.notice.total>0"></text>
				</view>
				<view class="item flex-box" @click="onUserMessage('service')">
					<image src="@/static/icon/icon_mess2.png" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs34 fwb col1 lh34 m-ellipsis">服务提醒</view>
						<view class="fs30 col5 mt25 lh30 m-ellipsis">{{message.service.message?message.service.message.content:''}}</view>
					</view>
					<text class="dot" v-if="message.service.total>0"></text>
				</view>
				<view class="item flex-box" @click="onUserMessage('order')">
					<image src="@/static/icon/icon_mess3.png" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs34 fwb col1 lh34 m-ellipsis">订单消息</view>
						<view class="fs30 col5 mt25 lh30 m-ellipsis">{{message.order.message?message.order.message.content:''}}</view>
					</view>
					<text class="dot" v-if="message.order.total>0"></text>
				</view>
				<!-- <view class="item flex-box">
					<image src="@/static/icon/icon_mess4.png" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 pl30">
						<view class="fs34 fwb col1 lh34 m-ellipsis">客服消息</view>
						<view class="fs30 col5 mt25 lh30 m-ellipsis">还有什么疑问吗？</view>
					</view>
					<text class="dot"></text>
				</view> -->
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				message:{
					notice:{
						total: 0,
						message: null
					},
					service:{
						total: 0,
						message: null
					},
					order:{
						total: 0,
						message: null
					},
				}
			}
		},
		onLoad() {
			this.fetchSummary();
		},
		onPullDownRefresh() {
			this.fetchSummary();
		},
		methods: {
			fetchSummary(){
				this.$core.get({url: 'xiluxc.message/summary',data: {},loading:false,success:(ret)=>{
					this.message = ret.data;
				}})
				uni.stopPullDownRefresh();
			},
			onUserMessage(type){
				uni.navigateTo({
					url:'/pages/platform_bulletin/platform_bulletin?type='+type,
					events: {
						readSuccess: data=>{
							this.fetchSummary();
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.item{
	width: 690rpx;
	height: 160rpx;
	background: #FFFFFF;
	border-radius: 20rpx;
	padding-left: 30rpx;
	padding-right: 30rpx;
	position: relative;
	margin-bottom: 30rpx;
	.cover{
		width: 100rpx;
		height: 100rpx;
		
	}
	.dot{
		position: absolute;
		top: 47rpx;
		right: 30rpx;
		width: 12rpx;
		height: 12rpx;
		background: #EB0000;
		border-radius: 50%;
	}
}
</style>
