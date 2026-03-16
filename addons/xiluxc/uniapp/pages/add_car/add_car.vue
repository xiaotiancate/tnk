<template>
	<view>

		<view class="container bg-f5">

			<image src="@/static/images/bg.png" mode="aspectFill" class="top_img" />
			<hx-navbar ref="hxnb" :config="config">
			</hx-navbar>
			<view class="p30 pr z2">
				<view class="page-foot bg-white">
					<view class="ptb10 plr30" @click="bindSave()">
						<view class="btn5">提交</view>
					</view>
				</view>
				<view class="box">
					<view class="fs36 fwb col1 lh36 mb30 need">车牌号码</view>
					<car-number-input @numberInputResult="numberInputResult" :defaultStr="car.car_no"></car-number-input>
					<view class="input_nav flex-box bb" @click="onBrands()">
						<view class="left need">品牌车系</view>
						<view class="fs30 flex-grow-1" :class="!brand?'cola':'col1'">
							{{brand?brand.name+' - '+series.name:"请选择"}}</view>
						<image src="@/static/icon/arrow_right1.png" mode="aspectFill" class="ico24 ml20"></image>
					</view>


					<view class="input_nav flex-box bb" @click="onBrands()">
						<view class="left">具体车型</view>
						<view class="fs30 flex-grow-1" :class="!models?'cola':'col1'">{{models?models.name:"请选择"}}
						</view>
						<image src="@/static/icon/arrow_right1.png" mode="aspectFill" class="ico24 ml20"></image>
					</view>

					<picker mode="date" @change="bindDateChange" :value="car.register_time">
						<view class="input_nav flex-box bb">
							<view class="left">注册日期</view>
							<view class="fs30 flex-grow-1" :class="car.register_time==''?'cola':'col1'">{{car.register_time==''?'请选择':car.register_time}}</view>
							<image src="@/static/icon/arrow_right1.png" mode="aspectFill" class="ico24 ml20"></image>
						</view>
					</picker>
					<view class="input_nav flex-box bb">
						<view class="left">车架号</view>
						<input type="text" class="flex-grow-1 fs30 col1" :placeholder-class="cola" placeholder="请填写" v-model="car.car_vin" />
					</view>
					<view class="input_nav flex-box bb">
						<view class="left">发动机号</view>
						<input type="text" class="flex-grow-1 fs30 col1" :placeholder-class="cola" placeholder="请填写" v-model="car.engine_number" />
					</view>
					<view class="input_nav flex-box bb">
						<view class="left">车辆归属</view>
						<view class="flex-grow-1 flex-box">
							<view class="flex-grow-1 flex-box" v-for="(item,index) in belongsTo" :key="index" @click="onChangeBelongs(item.id)">
								<image :src="'/static/icon/'+(item.id==car.car_belongs_to?'choose_sc':'choose_uc')+'.png'" mode="aspectFill" class="ico34"></image>
								<view class="pl15 fs30 col1">{{item.name}}</view>
							</view>
						</view>
					</view>
					<view class="input_nav flex-box bb">
						<view class="left">使用性质</view>
						<view class="flex-grow-1 flex-box">
							<view class="flex-grow-1 flex-box" v-for="(nature,index) in useNature" :key="nature.id" @click="onUseNature(nature.id)">
								<image :src="'/static/icon/'+(nature.id==car.use_nature?'choose_sc':'choose_uc')+'.png'" mode="aspectFill" class="ico34"></image>
								<view class="pl15 fs30 col1">{{nature.name}}{{nature.id}}</view>
							</view>
						</view>
					</view>
					<view class="input_nav flex-box">
						<view class="left">默认</view>
						<view class="flex-grow-1 flex-box" @click="onDefault()">
							<view class="flex-grow-1 flex-box">
								<image :src="'/static/icon/'+(car.is_default==1?'choose_sc':'choose_uc')+'.png'" mode="aspectFill" class="ico34"></image>
								<view class="pl15 fs30 col1">设为默认</view>
							</view>
						</view>
					</view>
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
				config: {
					color: '#101010',
					title: '添加爱车',
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [0, ['#FFFFFF', '#FFFFFF']],
					slideBackgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
					statusBarFontColor: ['#000000', '#ffffff']
				},

				brand: null,
				series: null,
				models: null,
				belongsTo: [
					{id:1,name:'个人'},
					{id:2,name:'公司'}
				],
				useNature:[
					{id:1,name:'非营运'},
					{id:2,name:'营运'}
				],
				car:{
					car_no: '',
					register_time:'',
					car_belongs_to:1,
					use_nature: 1,
					is_default: 0
				},
			}
		},
		onPageScroll(e) {
			// 重点，用到滑动切换必须加上
			this.$refs.hxnb.pageScroll(e);
		},
		onLoad(options) {
			let page = this;
			if(options.data){
				page.car = JSON.parse(options.data);
				page.brand = page.car.brand;
				page.series = page.car.series;
				page.models = page.car.models;
			}
			uni.$on("onCarModels", function(data) {
				page.brand = data.brand;
				page.series = data.series;
				page.models = data.models;
			})
		},
		onUnload() {
			uni.$off("onCarModels");
		},
		methods: {
			numberInputResult(e){
				this.car.car_no = e;
			},
			onBrands() {
				uni.navigateTo({
					url: '/pages/car_picker_1st/car_picker_1st',
				})
			},
			bindDateChange: function(e) {
				this.car.register_time = e.detail.value;
			},
			onChangeBelongs(id){
				this.car.car_belongs_to = id;
			},
			onUseNature(id){
				this.car.use_nature = id;
			},
			onDefault(){
				this.car.is_default = !this.car.is_default;
			},
			bindSave(){
				let formData = this.car;
				formData.brand_id = this.brand ? this.brand.id : 0;
				formData.series_id = this.brand ? this.series.id : 0;
				formData.models_id = this.models ? this.models.id : 0;
				var rule = [
					{name: 'car_no',nameChn: '车牌号',rules: ['require'],errorMsg: {require: '请填写车牌号'}},
					{name: 'brand_id',nameChn: '品牌车系',rules: ['gt:0'],errorMsg: {gt:"请选择品牌车系"}},
					{name: 'series_id',nameChn: '品牌车系',rules: ['gt:0'],errorMsg: {gt:"请选择品牌车系"}},
				    
				];
				// 是否全部通过，返回Boolean
				if (!validate.check(formData, rule)) {
				    uni.showToast({
				        title: validate.getError()[0],
				        icon: 'none'
				    });
				    return;
				}
				this.$core.post({url: 'xiluxc.user_car/set_car',data: formData,success: ret => {
				    this.getOpenerEventChannel().emit("setCarSuccess",{})
				    uni.navigateBack({});
				    uni.showToast({
				        title: '提交成功',
				        icon: 'none'
				    });
				}})
			}

		}
	}
</script>

<style lang="scss" scoped>
	.top_img {
		height: 585rpx;
		position: absolute;
		left: 0;
		right: 0;
		width: 100%;
		z-index: 1;
	}

	.box {
		width: 690rpx;
		padding: 36rpx 30rpx 0;
		background: #FFFFFF;
		border-radius: 20rpx;
	}

	.input_nav {
		height: 120rpx;
		font-size: 30rpx;
		color: #101010;

		.left {
			width: 180rpx;
		}
	}

	.input_nav.bb {
		border-bottom: 1px solid #EEEEEE;
	}

	.page-foot {
		box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		z-index: 3;

	}

	.need {
		&::after {
			content: '*';
			color: #FE4B01;
			font-size: 30rpx;
		}
	}

	.cola {
		color: #aaa;
	}

	@supports (bottom: constant(safe-area-inset-bottom)) or (bottom: env(safe-area-inset-bottom)) {
		.container {
			padding-bottom: calc(130rpx + 68rpx);
			padding-bottom: calc(constant(safe-area-inset-bottom) + 130rpx);
			padding-bottom: calc(env(safe-area-inset-bottom) + 130rpx);
		}

	}
</style>