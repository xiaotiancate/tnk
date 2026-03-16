<template>
	<view>
		<view class="container">
			<view class="ptb40 plr30">
				<view class="fs30 fwb">提现金额</view>
				<view class="inp_nav mt30 flex-box">
					<view class="fs36 fwb">¥</view>
					<input type="number" placeholder="请输入提现金额" v-model="withdraw.money" class="inp pl10" placeholder-class="col89" @input="onInput" />
				</view>
				<view class="mt30 flex-box col89 fs30">
					<view>可提现金额</view>
					<view class="col4">¥{{account.money}}</view>
				</view>
				<view class="btn2 mt100" @click="formSubmit()">确定提现</view>
				<view class="mt100 fs30 fwb col1">提现说明</view>
				<view class="mt30 fs28 col5">
					<!-- 富文本 -->
					<text>{{apply.apply_rule}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	var validate = require("../../xilu/validate.js");
	export default {
		data() {
			return {
				apply:{apply_rule:''},
				account:{
					id: 0,
					money: '0.00',
					total_money: '0.00',
					freeze_money: '0.00',
				},
				withdraw:{
					money:''
				}
			}
		},
		onLoad() {
			this.apply.apply_rule = getApp().globalData.config.apply_rule
			this.getAccount();
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
			onInput(e){
				let value = e.detail.value;
				if(value*100 > this.account.money*100){
					this.withdraw.money = this.account.money
				}
			},
			//提交
			formSubmit() {
			    let formData = this.withdraw;
				if(formData.money*100&&this.account.money*100<formData.money*100){
					uni.showToast({
						title:"提现金额大于可提现金额",
						icon:'none'
					});
					return false;
				}
			    var rule = [
					{name: 'money',nameChn: '金额',rules: ['require','gt:0'],errorMsg: {require: '提现金额错误',gt: '提现金额大于0'},},
					];
					
			    // 是否全部通过，返回Boolean
				if(!validate.check(formData,rule)){
					uni.showToast({title: validate.getError()[0],icon:'none'});
					return ;
				}
				this.$core.post({url:'xiluxc.user/withdraw',data:formData,success:ret=>{
					this.getOpenerEventChannel().emit('withdrawSuccess',ret.data);
					uni.navigateBack({});
					uni.showToast({title: ret.msg,icon:'none'});
				}})
			}
		}
	}
</script>

<style lang="scss" scoped>
.inp_nav{
	width: 690rpx;
	height: 100rpx;
	background: #F5F8FC;
	border-radius: 55rpx;
	padding: 0 40rpx;
	color: #101010;
	.inp{
		height: 100rpx;
		line-height: 100rpx;
		font-size: 30rpx;
	}
}
.mt100{margin-top: 100rpx;}
</style>
