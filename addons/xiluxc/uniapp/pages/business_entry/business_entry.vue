<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav" @click="onSave()">
				<view class="bt1">确定提交</view>
			</view>
		</view>
		<view class="container">
			<image src="@/static/images/cover1.png" mode="aspectFill" class="cover"></image>
			<view class="p30">
				<view class="pt25 flex-box">
					<view class="flex-grow-1 fwb fs34 col1">商家入驻 </view>
					<image src="@/static/icon/icon_tips.png" mode="aspectFill" class="ico30"></image>
					<view class="col89 fs30 pl10" @click="onSinglePage()">入驻说明</view>
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">店铺名称</view>
					<input type="text" class="flex-grow-1 tr" v-model="shop.name" placeholder="请输入店铺名称"
						placeholder-class="col1" />
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">法人姓名</view>
					<input type="text" class="flex-grow-1 tr" v-model="shop.legal_person" placeholder="请输入法人姓名"
						placeholder-class="col1" />
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">法人身份证</view>
					<input type="idcard" class="flex-grow-1 tr" v-model="shop.legal_idcard" placeholder="请输入法人身份证"
						placeholder-class="col1" />
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">店铺联系人姓名</view>
					<input type="text" class="flex-grow-1 tr" v-model="shop.connector" placeholder="请输入联系人姓名"
						placeholder-class="col1" />
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">店铺联系人电话</view>
					<input type="tel" maxlength="11" class="flex-grow-1 tr" v-model="shop.concat_mobile" placeholder="请输入联系人电话"
						placeholder-class="col1" />
				</view>
				
				<view class="inp_nav flex col1 fs30" @click="togglePopup('show',true)">
					<view class="col5 pr30">店铺属性</view>
					<view class="flex-grow-1 tr" v-if="propertySelected.length>0"><text v-for="(prop,index) in propertySelected">{{prop.name}}<block v-if="(index+1)<propertySelected.length">、</block></text></view>
					<view class="flex-grow-1 tr" v-else>请选择</view>
					<image src="@/static/icon/arrow_right.png" mode="aspectFill" class="ml10 ico30 mt8"></image>
				</view>
			
				<view class="inp_nav flex col1 fs30" @click="togglePopup('showService',true)">
					<view class="col5 pr30">服务类别</view>
					<view class="flex-grow-1 tr" v-if="serviceSelected.length>0"><text v-for="(serv,index) in serviceSelected">{{serv.name}}<block v-if="(index+1)<serviceSelected.length">、</block></text></view>
					<view class="flex-grow-1 tr" v-else>请选择</view>
					<image src="@/static/icon/arrow_right.png" mode="aspectFill" class="ml10 ico30 mt8"></image>
				</view>
			
				<view class="inp_nav flex-box col1 fs30 lh36">
					<view class="col5 pr30">店铺地址</view>
					<textarea @click="chooseAddress()" auto-height class="flex-grow-1 tr" v-model="shop.address" placeholder="请输入店铺地址" placeholder-class="col1"></textarea>
				</view>
				<view class="box fs30">
					<view class="col5 pb10">营业执照</view>
					<view>
						<view class="upload_nav" @click="chooseImage()">
							<image :src="shop.license_image?shop.license_image:'/static/images/icon_upload1.png'"
								mode="aspectFill" class="img"></image>
						</view>

					</view>
				</view>
				<view class="box fs30">
					<view class="col5 pb10">门店图片</view>
					<view>
						<view class="upload_nav" v-for="(img,index) in images" :key="index">
							<image :src="img" mode="aspectFill" class="img"></image>
							<image @click="bindDel(index)" src="@/static/icon/icon_del.png" mode="aspectFill"
								class="del"></image>
						</view>

						<view class="upload_nav" v-if="images.length<9" @click="chooseImages()">
							<image src="@/static/images/icon_upload1.png" mode="aspectFill" class="img"></image>
						</view>

					</view>
				</view>
				<view class="box fs30">
					<view class="col5">店铺介绍</view>
					<textarea class="textarea col1" placeholder="请输入店铺介绍" placeholder-class="col1"></textarea>
				</view>
			</view>

			<u-popup :show="show" mode="bottom" :safeAreaInsetBottom="false" @close="close" bgColor="transparent">
				<view class="popup">
					<view class="fs30 col1 tc">店铺属性</view>
					<image @click="close" src="@/static/icon/pop_close.png" mode="aspectFill" class="close"></image>
					<scroll-view scroll-y="true" class="pop_scroll">
						<view class="choose_item" :class="{active: item.checked}" v-for="(item,index) in propertyList" @click="onChooseProperty(index)">{{item.name}}</view>
					</scroll-view>
					<view class="pop_btn" @click="onConfirmProperty()">确定</view>
				</view>
			</u-popup>
			
			<u-popup :show="showService" mode="bottom" :safeAreaInsetBottom="false" @close="close" bgColor="transparent">
				<view class="popup">
					<view class="fs30 col1 tc">服务类别</view>
					<image @click="close" src="@/static/icon/pop_close.png" mode="aspectFill" class="close"></image>
					<scroll-view scroll-y="true" class="pop_scroll">
						<view class="choose_item" :class="{active: item.checked}" v-for="(item,index) in serviceList" @click="onChooseService(index)">{{item.name}}</view>
					</scroll-view>
					<view class="pop_btn" @click="onConfirmService()">确定</view>
				</view>
			</u-popup>

		</view>
	</view>
</template>

<script>
	var validate = require("../../xilu/validate.js");
	const app = getApp();
	export default {
		data() {
			return {
				propertyList: [],
				serviceList: [],
				shop: {
					name: '',
					legal_person: '',
					legal_idcard: '',
					connector: '',
					concat_mobile: '',
					province_id: '',
					city_id: '',
					district_id: '',
					address: '',
					description: '',
					license_image: '',
					lat: '',
					lng: ''
				},
				propertySelected: [],
				serviceSelected: [],
				images: [],
				show: false,
				showService: false
			}
		},
		onLoad() {
			this.$util.getProperty(false).then(data=>{
				this.propertyList = data
			})
			this.$util.getService(false).then(data=>{
				this.serviceList = data
			})
		},
		methods: {
			onSinglePage(){
				let id = getApp().globalData.config.shop_agreement;
				uni.navigateTo({
					url: '/pages/rich_mp/rich_mp?id='+id
				})
			},
			togglePopup(field,status) {
				this[field] = status
			},
			close() {
				this.show = false;
				this.showService = false;
			},
			onChooseProperty(index){
				this.propertyList[index].checked = !this.propertyList[index].checked ? true : false;
				this.$forceUpdate();
			},
			onChooseService(index){
				this.serviceList[index].checked = !this.serviceList[index].checked ? true : false;
				this.$forceUpdate();
			},
			onConfirmProperty(){
				let propertySelected = [];
				this.propertyList.forEach(item=>{
					if(item.checked){
						propertySelected.push(item)
					}
				})
				if(propertySelected.length<=0){
					uni.showToast({
						title: '请选择至少一个属性',
						icon: 'none'
					})
					return;
				}
				this.propertySelected = propertySelected;
				this.close()
			},
			onConfirmService(){
				let serviceSelected = [];
				this.serviceList.forEach(item=>{
					if(item.checked){
						serviceSelected.push(item)
					}
				})
				if(serviceSelected.length<=0){
					uni.showToast({
						title: '请选择至少一个服务',
						icon: 'none'
					})
					return;
				}
				this.serviceSelected = serviceSelected;
				this.close()
			},
			
			chooseAddress() {
				let page = this;
				let currentCity = this.$core.getCurrentCity();
				uni.chooseLocation({
					latitude: currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude,
					longitude: currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude,
					success(res) {
						page.shop.lat = res.latitude;
						page.shop.lng = res.longitude;
						page.shop.address = res.address
					},
					fail(res) {
						console.log(res)
					}
				})
			},
			chooseImage() {
				let that = this;
				uni.chooseImage({
					count: 1,
					success: res => {
						let file = res.tempFiles[0];
						// #ifdef H5
						that.$core.uploadFileH5({
							filePath: file.path,
							success: (ret, response) => {
								that.shop.license_image = ret.data.url;
							}
						});
						//#endif
						//#ifdef MP-WEIXIN
						that.$core.uploadFile({
							filePath: file.path,
							success: (ret, response) => {
								that.shop.license_image = ret.data.url;
							}
						});
						//#endif
					}
				});
			},
			chooseImages() {
				let that = this;
				let images = that.images;
				uni.chooseImage({
					count: 9 - images.length,
					success: res => {
						let files = res.tempFiles;
						files.map(item => {
							// #ifdef H5
							that.$core.uploadFileH5({
								filePath: item.path,
								success: (ret, response) => {
									images.push(ret.data.url);
									that.images = images;
								}
							});
							// #endif

							// #ifdef MP-WEIXIN
							that.$core.uploadFile({
								filePath: item.path,
								success: (ret, response) => {
									images.push(ret.data.url);
									that.images = images;
								}
							});
							// #endif
						});
					}
				});
			},
			bindDel(index) {
				this.images.splice(index, 1);
			},
			onSave() {
				let formData = this.shop;
				let serviceIds = [];
				let propertyIds = [];
				this.propertySelected.forEach(item=>{
					propertyIds.push(item.id)
				})
				this.serviceSelected.forEach(item=>{
					serviceIds.push(item.id)
				})
				console.log(serviceIds)
				formData.service_ids = serviceIds;
				formData.property_ids = propertyIds;
				formData.images = this.images.join(",");
				var rule = [{
						name: 'name',
						nameChn: '店铺名',
						rules: ['require'],
						errorMsg: {
							require: '请填写店铺名'
						}
					},
					{
						name: 'legal_person',
						nameChn: '法人',
						rules: ['require'],
						errorMsg: {
							require: "请填写法人"
						}
					},
					{
						name: 'legal_idcard',
						nameChn: '法人身份证',
						rules: ['require'],
						errorMsg: {
							require: "请填写法人身份证"
						}
					},
					{
						name: 'connector',
						nameChn: '联系人',
						rules: ['require'],
						errorMsg: {
							require: "请填写联系人"
						}
					},
					{
						name: 'concat_mobile',
						nameChn: '手机号',
						rules: ['require'],
						errorMsg: {
							require: "请填写手机号"
						}
					},
					{
						name: 'address',
						nameChn: '门店地址',
						rules: ['require'],
						errorMsg: {
							require: "请选择门店地址"
						}
					},

				];
				// 是否全部通过，返回Boolean
				if (!validate.check(formData, rule)) {
					uni.showToast({
						title: validate.getError()[0],
						icon: 'none'
					});
					return;
				}
				this.$core.post({
					url: 'xiluxc.user/shop_auth',
					data: formData,
					success: ret => {
						uni.showModal({
							title: '提示',
							content: ret.msg,
							showCancel: false,
							success() {
								uni.navigateBack({});
							}
						})
					},
					fail: ret=>{
						uni.showModal({
							title: '提示',
							content: ret.msg,
							showCancel: false,
							success() {
								uni.navigateBack({});
							}
						})
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.cover {
		width: 100%;
		height: 224rpx;
	}

	.inp_nav {
		width: 690rpx;
		min-height: 100rpx;
		background: #F5F8FC;
		border-radius: 50rpx;
		margin-top: 30rpx;
		padding-left: 35rpx;
		padding-right: 30rpx;
		padding-top: 30rpx;
		padding-right: 30rpx;
		padding-bottom: 30rpx;
	}

	.foot_nav {
		width: 750rpx;
		box-shadow: 0rpx -1rpx 0rpx 0rpx #EEEEEE;
		padding: 10rpx 30rpx;
	}

	.bt1 {
		width: 690rpx;
		height: 100rpx;
		line-height: 100rpx;
		text-align: center;
		font-size: 28rpx;
		color: #FFFFFF;
		background: #F64137;
		border-radius: 50rpx;
	}

	.box {
		width: 690rpx;
		background: #F5F8FC;
		border-radius: 20rpx;
		padding: 35rpx 35rpx 30rpx;
		margin-top: 30rpx;
	}

	.upload_nav {
		width: 200rpx;
		height: 200rpx;
		border-radius: 15rpx;
		display: inline-block;
		vertical-align: top;
		margin-top: 20rpx;
		margin-right: 10rpx;
		position: relative;
		z-index: 1;

		.img {
			width: 100%;
			height: 100%;
			display: block;
		}

		.del {
			width: 35rpx;
			height: 35rpx;
			top: -18rpx;
			right: -11rpx;
			position: absolute;
			z-index: 2;
		}

		&:nth-of-type(3n) {
			margin-right: 0;
		}
	}

	.textarea {
		width: 100%;
		padding-top: 30rpx;
		height: 165rpx;
	}

	.popup {
		width: 750rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		position: relative;
		.close{
			width: 30rpx;
			height: 30rpx;
			position: absolute;
			top: 30rpx;
			right: 30rpx;
		}
		.pop_scroll{
			max-height: 490rpx;
			width: 100%;
			.choose_item{
				width: 210rpx;
				height: 85rpx;
				background: #F5F8FC;
				border-radius: 43rpx;
				display: inline-block;
				vertical-align: top;
				padding: 0 8rpx;
				line-height: 85rpx;
				font-size: 30rpx;
				color: #555555;
				margin-right: 30rpx;
				margin-top: 30rpx;
				text-align: center;
			}
			.choose_item.active{
				background: #F64137;
				color: #FFFFFF;
			}
			.choose_item:nth-of-type(3n){margin-right: 0;}
		}
		.pop_btn{
			width: 690rpx;
			height: 100rpx;
			line-height: 100rpx;
			text-align: center;
			font-size: 28rpx;
			color: #FFFFFF;
			background: #F64137;
			border-radius: 50rpx;
			margin-top: 40rpx;
		}
	}
	.mt8{margin-top: 6rpx;}
</style>