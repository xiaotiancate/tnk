<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item" v-for="(item,index) in noticeList" :key="index" @click="onNoticeDetail(index,item)">
					<text class="dot" v-if="item.read==0"></text>
					<view class="fwb col1 fs30">{{item.title}}</view>
					<view class="m-ellipsis-l2 mt20 fs28 col5 lh38"><rich-text :nodes="item.content"></rich-text></view>
					<view class="flex-box mt25">
						<view class="flex-grow-1 col89 fs24">{{item.createtime_text}}</view>
						<view class="more">查看详情</view>
					</view>
				</view>

				<view class="nothing" v-if="noticeMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetch">{{noticeMore.text}}</view>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				type: '',
				noticeList: [],
				noticeMore: {
					page: 1
				}
			}
		},
		onLoad(options) {
			this.type = options.type;
			let title = '';
			if (options.type == 'service') {
				title = '服务消息'
			} else if (options.type == 'order') {
				title = '订单消息'
			} else if (options.type == 'notice') {
				title = '平台公告'
			}
			uni.setNavigationBarTitle({
				title: title
			})
			this.fetch()
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			fetch() {
				let url = '';
				if (this.type == 'service') {
					url = 'xiluxc.message/personal_list'
				} else if (this.type == 'order') {
					url = 'xiluxc.message/personal_list'
				} else if (this.type == 'notice') {
					url = 'xiluxc.message/notice_list'
				}
				this.$util.fetch(this, url, {
					pagesize: 10,
					type: this.type
				}, 'noticeMore', 'noticeList', 'data', data => {

				})
			},
			onNoticeDetail(index,message) {
				if (this.type == 'notice') {
					uni.navigateTo({
						url: '/pages/platform_bulletin_info/platform_bulletin_info?id=' + message.id,
						events:{
							noticeDetail:data=>{
								this.noticeList[index].read = 1;
								this.getOpenerEventChannel().emit("readSuccess",{})
								this.$forceUpdate();
							}
						}
					})
				} else {
					let url = '';
					if (message.type == 2) {
						url = '/pages/my_combo_list/my_combo_list'
					} else {
						url = '/pages/order_size/order_size?id=' + message.extra.order_id
					}
					uni.navigateTo({
						url: '/pages/order_size/order_size?id=' + message.extra.order_id
					})
					this.$core.post({
						url: 'xiluxc.message/set_read',
						data: {
							message_id: message.id
						},
						loading: false,
						success: ret => {
							this.noticeList[index].read = 1;
							this.getOpenerEventChannel().emit("readSuccess",{})
							this.$forceUpdate();
						},
						fail: err => {
							console.log(err);
						}
					});
				}

			}
		}
	}
</script>

<style lang="scss" scoped>
	.item {
		width: 690rpx;
		padding: 30rpx;
		position: relative;
		background: #FFFFFF;
		border-radius: 20rpx;

		.dot {
			position: absolute;
			top: 20rpx;
			right: 20rpx;
			width: 12rpx;
			height: 12rpx;
			background: #EB0000;
			border-radius: 50%;
		}

		&+& {
			margin-top: 30rpx;
		}

		.more {
			font-size: 24rpx;
			color: #007AFF;
		}
	}
</style>