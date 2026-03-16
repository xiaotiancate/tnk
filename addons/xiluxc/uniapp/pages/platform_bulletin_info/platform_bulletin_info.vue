<template>
	<view>
		<view class="container">
			<view class="p30">
				<view class="fs34 fwb col1">{{message.title}}</view>
				<view class="flex-box mt30 fs24 col89 pb40 bb">
					<view class="flex-grow-1 plr10 ">{{message.view_num}}人浏览</view>
					<view>{{message.createtime_text}}</view>
				</view>
				<view class="mt40">
					<!-- 富文本 -->
					<u-parse :content="message.content"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id: 0,
				message:{
					title:'',
					view_num: 0,
					content: '',
					createtime_text: ''
				}
			}
		},
		onLoad(options) {
			this.id = options.id;
			this.fetchDetail();
		},
		methods: {
			fetchDetail(){
				this.$core.get({url: 'xiluxc.message/notice_detail',data: {id: this.id},loading:false,success:(ret)=>{
					this.getOpenerEventChannel().emit("noticeDetail",{});
					this.message = ret.data;
				}})
			}
		}
	}
</script>

<style>

</style>