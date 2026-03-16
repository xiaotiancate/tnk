<template>
	<view>
		<view class="container plr30">
			<scroll-view class="scroll-list" :scroll-into-view="scrollViewId" scroll-y="true" scroll-with-animation
				:style="{height:winHeight + 'px'}">
				<view v-for="(item,index) in contactList" :key="index"
					:id="item.first_letter == '#' ? 'indexed-list-YZ' :'indexed-list-' + item.first_letter">
					<view class="fs34 fwb col1 mt40">{{item.first_letter}}</view>
					<view class="flex-box mt30" v-for="(v,i) in item.brands" @click="onSeries(v.id)">
					<image :src="v.image_text" mode="aspectFill" class="img"></image>
					<view class="letter-title flex-grow-1">{{v.name}}</view>						
					</view>
				</view>
				<view class="pt40"></view>
			</scroll-view>
			<view class="right-menu tc">
				<view v-for="(i,index) in Letters" :key="index" @click="jumper(i,index)"
					:class="jumperIndex == i?'letter-item active':'letter-item'">{{i}}</view>
			</view>

		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				jumperIndex: 'A',
				contactList: [],
				scrollViewId: '',
				winHeight: 0,
				itemHeight: 0,
				Letters: [],

			}
		},
		onLoad() {
			this.fetchBrand()
			let winHeight = uni.getSystemInfoSync().windowHeight;
			this.itemHeight = winHeight / 26;
			this.winHeight = winHeight;
		},
		methods: {
			jumper(event, i) {
				this.jumperIndex = event;
				let len = this.contactList[i].brands.length;
				if (event == '#') {
					this.scrollViewId = 'indexed-list-YZ';
					return
				}
				if (len > 0) {
					console.log(111);
					this.scrollViewId = 'indexed-list-' + event;
				}
			},
			fetchBrand(){
				this.$core.get({url: 'xiluxc.common/car_brand',data: {},loading:false,success:(ret)=>{
					this.Letters = ret.data.letters;
					this.contactList = ret.data.brands;
				}})
			},
			onSeries(id){
				uni.navigateTo({
					url: '/pages/car_picker_2nd/car_picker_2nd?brand_id='+id,
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.img {
		width: 58rpx;
		height: 58rpx;
		border-radius: 50%;
	}

	.letter-title {
		padding-left: 25rpx;
		font-size: 30rpx;
		color: #101010;
	}

	.right-menu {
		position: fixed;
		right: 1px;
		top: 50%;
		transform: translateY(-50%);
		z-index: 2;

		.letter-item {
			font-size: 28rpx;
			color: #898989;
			line-height: 40rpx;
		}
	}
</style>