<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="top_box flex-box">
					<view class="flex-grow-1">
						<view class="fs40 fwb colf lh40">{{userinfo.points || 0}}</view>
						<view class="mt20 fs24 colf_8 lh24">我的积分</view>
					</view>
					<image src="@/static/icon/icon_point.png" mode="aspectFill" class="ico80"></image>
				</view>
				<view class="fs34 col1 fwb pb10 mt60">积分明细</view>
				<view class="item" v-for="(log,index) in scoreList" :key="index">
					<view class="flex-box">
						<view class="flex-grow-1 fs30 col1">{{log.memo}}</view>
						<view class="fs24 col89">{{log.createtime_text}}</view>
					</view>
					<view class="flex-box mt30 fs24">
						<view class="add flex-grow-1" v-if="log.score>0">+<text class="fs30">{{log.score_abs}}</text></view>
						<view class="del flex-grow-1" v-else>-<text class="fs30">{{log.score_abs}}</text></view>
						<view class="col5">订单号{{log.extra_text.order_no}}</view>
					</view>
				</view>
				
				<view class="nothing" v-if="scoreMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchScoreLog">{{scoreMore.text}}</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				userinfo:{},
				scoreList: [],
				scoreMore: {page: 1}
			}
		},
		onLoad(options) {
			this.getUserinfo();
			this.fetchScoreLog();
		},
		onReachBottom() {
			this.fetchScoreLog();
		},
		methods: {
			fetchScoreLog() {
				this.$util.fetch(this, 'xiluxc.user/score_log', {
					pagesize: 10
				}, 'scoreMore', 'scoreList', 'data', data => {
					
				})
			},
			getUserinfo(){
				this.$core.post({url: 'xiluxc.user/info',data: {},loading: false,success: ret => {
						this.userinfo = ret.data;
					},fail: err => {
						console.log(err);
					}
				});
			},
		}
	}
</script>

<style lang="scss" scoped>
.top_box{
	width: 690rpx;
	height: 170rpx;
	background: linear-gradient( 180deg, #FF8202 0%, #FE4B01 100%);
	border-radius: 20rpx;
	z-index: 1;
	position: relative;
	padding-left:40rpx;
	padding-right: 60rpx;
	&::after{
		content: '';
		position: absolute;
		z-index: -1;
		width: 650rpx;
		height: 160rpx;
		background: rgba(254,76,1,0.2);
		border-radius: 20rpx;
		left: 20rpx;
		bottom: -22rpx;
	}
}
.item {
		margin-top: 20rpx;
		width: 690rpx;
		background: #FFFFFF;
		border-radius: 20rpx;
		padding: 30rpx;
	}
	.add{
		color: #FE4B01;
	}
	.del{
		color: #06B89B;
	}
	.mt60{margin-top: 60rpx;}
</style>
