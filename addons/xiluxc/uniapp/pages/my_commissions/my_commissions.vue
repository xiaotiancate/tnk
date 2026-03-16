<template>
	<view>
		<view class="container bg-f5">
			<image src="@/static/images/bg4.png" mode="aspectFill" class="top_img" />
			<view class="page-head">
				<hx-navbar ref="hxnb" :config="config">
				</hx-navbar>
			</view>
			<view class="pt205 pr z2 ">
				<view class="plr40 flex-box">
					<view class="flex-grow-1 pr20 colf fs24">
						<view class="lh40">¥<text class="fs40">{{account.total_money}}</text></view>
						<view class="colf_8 mt15">佣金总额</view>
					</view>
					<view class="btn7" @click="onWithdraw()">申请提现</view>
				</view>
				<view class="mt40 flex-box fs24 lh24 colf plr40 pb50">
					<view class="flex-grow-1 ">
						<view class="lh36">
							<text>¥</text>
							<text class="fs36">{{account.money}}</text>
						</view>
						<view class="colf_8 mt20">可提现佣金</view>
					</view>
					<view class="flex-grow-1">
						<view class="lh36">
							<text>¥</text>
							<text class="fs36">{{account.freeze_money}}</text>
						</view>
						<view class="colf_8 mt20">冻结中佣金</view>
					</view>

					<view>
						<view class="lh36">
							<text>¥</text>
							<text class="fs36">{{account.withdraw_money}}</text>
						</view>
						<view class="colf_8 mt20">已提现佣金</view>
					</view>
				</view>

				<view class="plr30 ptb40">
					<view class="fs34 fwb col1 pb10">佣金明细</view>
					<view class="item" v-for="(item,index) in moneyLogList" :key="index">
						<view class="flex-box lh30">
							<view class="fs30 col1 m-ellipsis pr30">{{item.memo}}</view>
							<view class="col89 fs24">{{item.createtime_text}}</view>
						</view>
						<view class="mt20 flex-box">
							<image :src="item.buyer?item.buyer.avatar:''" mode="aspectFill" class="cover"></image>
							<view class="plr15 flex-grow-1 m-ellipsis col5 fs30">{{item.buyer?item.buyer.nickname : ''}}</view>
							<view class="add fs24" v-if="item.money>0">
								<text>+¥</text>
								<text class="fs30">{{item.money_abs}}</text>
							</view>
							<view class="del fs24" v-else>
								<text>-¥</text>
								<text class="fs30">{{item.money_abs}}</text>
							</view>
						</view>
					</view>
					
					<view class="nothing" v-if="moneyLogMore.nothing">
						<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
						<text>暂无内容</text>
					</view>
					<view class="g-btn3-wrap" v-else>
						<view class="g-btn3" @click="fetch">{{moneyLogMore.text}}</view>
					</view>
					
				</view>


			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				config: {
					color: '#ffffff',
					title: '我的佣金',
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FE5D01', '#FE5D01']],
					statusBarFontColor: ['#ffffff', '#ffffff']
				},
				account:{
					total_money: '0.00',
					money: '0.00',
					withdraw_money: '0.00',
					freeze_money: '0.00'
				},
				moneyLogList: [],
				moneyLogMore: {page: 1}
			}
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		onLoad() {
			this.getAccount();
			this.fetch();
		},
		onReachBottom() {
			this.fetch();
		},
		methods: {
			getAccount(){
				this.$core.post({url: 'xiluxc.user/account',data: {},loading: false,success: ret => {
						this.account = ret.data;
					},fail: err => {
						console.log(err);
					}
				});
			},
			refresh(){
				this.moneyLogList = [];
				this.moneyLogMore = {page: 1};
				this.fetch();
			},
			fetch() {
				this.$util.fetch(this, 'xiluxc.user/money_log', {
					pagesize: 10,
					type: 2
				}, 'moneyLogMore', 'moneyLogList', 'data', data => {
					
				})
			},
			onWithdraw(){
				uni.navigateTo({
					url: '/pages/request_withdrawal/request_withdrawal',
					events:{
						withdrawSuccess: data=>{
							this.getAccount();
							this.refresh();
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.top_img {
		height: 470rpx;
		position: absolute;
		left: 0;
		right: 0;
		width: 100%;
		z-index: 1;
	}

	.pt205 {
		padding-top: 205rpx;
	}

	.item {
		width: 690rpx;

		background: #FFFFFF;
		border-radius: 20rpx;
		margin-top: 20rpx;
		padding: 30rpx;

		.add {
			color: #FE4B01;
		}

		.del {
			color: #01B99A;
		}

		.cover {
			width: 40rpx;
			height: 40rpx;
			border-radius: 50%;
		}
	}
</style>