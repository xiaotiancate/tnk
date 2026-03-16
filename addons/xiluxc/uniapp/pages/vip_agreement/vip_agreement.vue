<template>
	<view>
		<view class="container">
			<view class="ptb30 plr40">
				<view class="col1 fs40 fwb">会员购买协议</view>
				<view class="mt30 col1 fs30">
					<!-- 富文本 -->
					<u-parse :content="vip.content"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				vip:{
					name: '',
					content: ''
				}
			}
		},
		onLoad(options) {
			let page = this;
			this.getOpenerEventChannel().on("vipAgreement",function(data){
				page.vip = data.data;
			})
		}
	}
</script>

<style>

</style>
