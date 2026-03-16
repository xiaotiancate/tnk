<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="top_box flex-box">
					<view class="flex-grow-1">
						<view class="fs40 fwb colf lh40">{{total}}</view>
						<view class="mt20 fs24 colf_8 lh24">我的好友</view>
					</view>
					<view class="invite_btn" @click="onInvite()">邀请好友</view>
				</view>
				<view class="mt60 fs34 fwb col1 pb10">我的好友</view>
				<view class="item flex-box" v-for="(item,index) in teamList" :key="index">
					<image :src="item.user.avatar" mode="aspectFill" class="cover"></image>
					<view class="flex-grow-1 plr25">
						<view class="fs30 col0 lh30">{{item.user.nickname}}</view>
						<view class="mt20 fs24 lh24 col89">{{item.bindtime_text}}</view>
					</view>
					<view class="col4 fs24">
						<text>+¥</text>
						<text class="fs30">{{item.total_money}}</text>
					</view>
				</view>
				
				<view class="nothing" v-if="teamMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchTeam">{{teamMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				total: 0,
				teamList: [],
				teamMore: {page: 1}
			}
		},
		onLoad(options) {
			this.fetchTeam();
		},
		onReachBottom() {
			this.fetchTeam();
		},
		methods: {
			fetchTeam() {
				this.$util.fetch(this, 'xiluxc.user/myteam', {
					pagesize: 10
				}, 'teamMore', 'teamList', 'data', data => {
					this.total = data.total
				})
			},
			onInvite(){
				uni.navigateTo({
					url: '/pages/invite/invite'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.top_box {
		width: 690rpx;
		height: 170rpx;
		background: linear-gradient(180deg, #FF8202 0%, #FE4B01 100%);
		border-radius: 20rpx;
		z-index: 1;
		position: relative;
		padding-left: 40rpx;
		padding-right: 60rpx;

		&::after {
			content: '';
			position: absolute;
			z-index: -1;
			width: 650rpx;
			height: 160rpx;
			background: rgba(254, 76, 1, 0.2);
			border-radius: 20rpx;
			left: 20rpx;
			bottom: -22rpx;
		}
	}

	.invite_btn {
		width: 180rpx;
		height: 85rpx;
		line-height: 85rpx;
		text-align: center;
		font-size: 30rpx;
		color: #FE4B01;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 43rpx;
	}

	.item {
		width: 690rpx;
		height: 150rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		margin-top: 20rpx;
		padding: 0 30rpx;

		.cover {
			width: 90rpx;
			height: 90rpx;
			border-radius: 50%;
		}
	}
	.mt60{margin-top: 60rpx;}
</style>