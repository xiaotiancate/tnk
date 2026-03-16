<template>
	<view>
		<view class="page-foot">
			<view class="ptb10 plr30" @click="onConfirm()">
				<view class="btn2">确定切换</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item flex-box" v-for="(item,index) in carList">
					<view class="flex-grow-1 pr30 fs26 lh26 col89">
						<view>{{item.series.name}}</view>
						<view class="mt15 fs36 lh36 fwb col1">{{item.car_no}}</view>
					</view>
					<image @click="onChange(index)" :src="'/static/icon/'+(item.is_default==1?'choose_sc':'choose_uc')+'.png'" mode="aspectFill" class="ico34"></image>
				</view>
				
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				carList: [],
			}
		},
		onLoad() {
			this.fetchList();
		},
		methods: {
			fetchList(){
				this.$core.post({url: 'xiluxc.user_car/lists',data: {},success: ret => {
				    this.carList = ret.data;
				}})
			},
			onChange(index){
				for(let i=0;i<this.carList.length;i++){
					if(i==index){
						this.carList[i].is_default = 1;
					}else{
						this.carList[i].is_default = 0
					}
				}
			},
			onConfirm(){
				let page = this;
				let carId = 0;
				 this.carList.forEach(item=>{
					 if(item.is_default == 1){
						 carId = item.id;
					 }
				 })
				uni.showModal({
					title: '提示',
					content: '确定切换？',
					success(res) {
						if(res.confirm){
							page.$core.post({url: 'xiluxc.user_car/set_default',data: {user_car_id: carId},success: ret => {
								page.getOpenerEventChannel().emit("setCarSuccess",{})
								uni.navigateBack()
							    uni.showToast({
							    	title: '切换成功',
									icon:'none'
							    })
							}})
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
	
	background: #FFFFFF;
	box-shadow: 0rpx 0rpx 10rpx 0rpx rgba(184,189,202,0.15);
	border-radius: 20rpx;
	padding: 40rpx 30rpx;
	&+&{margin-top: 30rpx;}
}
.page-foot{
	background: #FFFFFF;
	box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
	border-radius: 30rpx 30rpx 0rpx 0rpx;
}
</style>
