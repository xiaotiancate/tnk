<template>
	<view>
		<view class="page-foot bg-white">
			<view class="ptb10 plr30" @click="onAdd()">
				<view class="btn5">添加爱车</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="item flex-box" v-for="(item,index) in carList">
					<view class="flex-grow-1">
						<view class="col89 fs26 lh26">{{item.series.name}}</view>
						<view class="mt15 col1 fs36 lh36 fwb">{{item.car_no}}</view>
					</view>
					<image src="@/static/icon/icon_edit.png" @click="onEdit(item)" mode="aspectFill" class="ico30"></image>
					<image src="@/static/icon/icon_del1.png" @click="onDel(item.id)" mode="aspectFill" class="ico30 ml30"></image>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				carList: []
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
			onAdd(){
				uni.navigateTo({
					url: '/pages/add_car/add_car',
					events:{
						setCarSuccess: data=>{
							this.fetchList();
						}
					}
				})
			},
			onEdit(item){
				uni.navigateTo({
					url: '/pages/add_car/add_car?data='+JSON.stringify(item),
					events:{
						setCarSuccess: data=>{
							this.fetchList();
						}
					},
				})
			},
			onDel(carId){
				let page = this;
				uni.showModal({
					title: '提示',
					content: '确定删除？',
					success(res) {
						if(res.confirm){
							page.$core.post({url: 'xiluxc.user_car/del',data: {user_car_id: carId},success: ret => {
							    uni.showToast({
							    	title: '删除成功',
									icon:'none'
							    })
								page.fetchList();
							}})
						}
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
.item{
	width: 690rpx;
	height: 157rpx;
	background: #FFFFFF;
	box-shadow: 0rpx 0rpx 10rpx 0rpx rgba(184,189,202,0.15);
	border-radius: 20rpx;
	padding: 0 30rpx;
	&+&{margin-top: 30rpx;}
}
</style>
