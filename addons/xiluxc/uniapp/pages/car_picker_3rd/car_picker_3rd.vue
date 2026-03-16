<template>
	<view>
		<view class="container bg-white">
			<view class="top_brand plr30 flex-box fs30 col1">
				<image :src="brand.image_text" mode="aspectFill" class="img"></image>
				<view class="flex-grow-1 pl25">{{brand.name}} <text class="pl30 col3">{{series.name}}</text></view>
			</view>
			<view class="plr30">
				<view class="item" v-for="(item,index) in list" :key="index" @click="onConfirm(item)">{{item.name}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				seriesId: 0,
				brand:{image_text:'',name:''},
				series:{name: ''},
				list:[]
			}
		},
		onLoad(options) {
			this.seriesId = options.series_id
			this.fetchModels();
		},
		methods: {
			fetchModels(){
				this.$core.get({url: 'xiluxc.common/car_models',data: {series_id: this.seriesId},loading:false,success:(ret)=>{
					this.brand = ret.data.brand;
					this.series = ret.data.series;
					this.list = ret.data.models;
				}})
			},
			onConfirm(models){
				let data = {
					brand: this.brand,
					series: this.series,
					models: models
				}
				uni.$emit("onCarModels",data)
				uni.navigateBack({delta: 3})
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
