<template>
	<view>
		<view class="container bg-white">
			<view class="top_brand plr30 flex-box fs30 col1">
				<image :src="brand.image_text" mode="aspectFill" class="img"></image>
				<view class="flex-grow-1 pl25">{{brand.name}}</view>
			</view>
			<view class="plr30">
				<view class="item" v-for="(item,index) in list" :key="index" @click="onModels(item,item.id)">{{item.name}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				brandId: 0,
				brand:{image_text:'',name:''},
				list:[]
			}
		},
		onLoad(options) {
			this.brandId = options.brand_id
			this.fetchSeries();
		},
		methods: {
			fetchSeries(){
				this.$core.get({url: 'xiluxc.common/car_series',data: {brand_id: this.brandId},loading:false,success:(ret)=>{
					this.brand = ret.data.brand;
					this.list = ret.data.series;
				}})
			},
			onModels(item,id){
				if(!item.is_models){
					let data = {
						brand: this.brand,
						series: item,
						models: null
					}
					uni.$emit("onCarModels",data)
					uni.navigateBack({delta: 2})
					return
				}
				uni.navigateTo({
					url: '/pages/car_picker_3rd/car_picker_3rd?series_id='+id,
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
.top_brand{
	width: 750rpx;
	height: 100rpx;
	background: #F5F7FB;
	.img{
		width: 58rpx;
		height: 58rpx;
	}
}
.item{
	margin-top: 45rpx;
	font-size: 30rpx;
	color: #101010;
}
</style>
