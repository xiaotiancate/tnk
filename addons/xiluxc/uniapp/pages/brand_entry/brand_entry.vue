<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav" @click="onSave()">
				<view class="bt1">确定提交</view>
			</view>
		</view>
		<view class="container">
			<image src="@/static/images/cover.png" mode="aspectFill" class="cover"></image>
			<view class="p30">
				<view class="pt25 flex-box">
					<view class="flex-grow-1 fwb fs34 col1">品牌入驻</view>
					<image src="@/static/icon/icon_tips.png" mode="aspectFill" class="ico30"></image>
				<view class="col89 fs30 pl10" @click="onSinglePage()">入驻说明</view>
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">联系人</view>
					<input type="text" class="flex-grow-1 tr" v-model="brand.concat_name" placeholder="请输入联系人" placeholder-class="col1"/>
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">联系电话</view>
					<input type="number" maxlength="11" class="flex-grow-1 tr" v-model="brand.mobile" placeholder="请输入联系电话" placeholder-class="col1"/>
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">品牌账号</view>
					<input type="number" maxlength="11" class="flex-grow-1 tr" v-model="brand.account_mobile" placeholder="请输入品牌账号" placeholder-class="col1"/>
				</view>
				<view class="inp_nav flex-box col1 fs30">
					<view class="col5 pr30">品牌名称</view>
					<input type="text" class="flex-grow-1 tr" v-model="brand.brand_name" placeholder="请输入品牌名称" placeholder-class="col1"/>
				</view>
				<view class="box fs30">
					<view class="col5 pb10">品牌logo</view>
					<view>
						<view class="upload_nav" @click="chooseImage()">
							<image :src="brand.logo ? brand.logo: '/static/images/icon_upload_logo.png'" mode="aspectFill" class="img"></image>
							<!-- <image src="@/static/icon/icon_del.png" mode="aspectFill" class="del"></image> -->
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
				brand:{
					brand_name: '',
					logo:'',
					account_mobile: '',
					concat_name: '',
					mobile: ''
				},
			}
		},
		methods: {
			onSinglePage(){
				let id = getApp().globalData.config.brand_agreement;
				uni.navigateTo({
					url: '/pages/rich_mp/rich_mp?id='+id
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
					    that.brand.logo = ret.data.url;
					  }
					});
					//#endif
					//#ifdef MP-WEIXIN
			        that.$core.uploadFile({
			          filePath: file.path,
			          success: (ret, response) => {
			            that.brand.logo = ret.data.url;
			          }
			        });
					//#endif
			      }
			    });
			},
			onSave(){
				let formData = this.brand;
				var rule = [
					{name: 'brand_name',nameChn: '品牌名',rules: ['require'],errorMsg: {require: '请填写品牌名'}},
					{name: 'logo',nameChn: '品牌logo',rules: ['require'],errorMsg: {require:"请上传品牌LOGO"}},
					{name: 'concat_name',nameChn: '联系人',rules: ['require'],errorMsg: {require:"请填写联系人"}},
					{name: 'mobile',nameChn: '手机号',rules: ['require'],errorMsg: {require:"请填写手机号"}},
				    
				];
				// 是否全部通过，返回Boolean
				if (!validate.check(formData, rule)) {
				    uni.showToast({
				        title: validate.getError()[0],
				        icon: 'none'
				    });
				    return;
				}
				this.$core.post({url: 'xiluxc.user/brand_auth',data: formData,success: ret => {
				    uni.showModal({
				    	title: '提示',
				    	content: '提交成功，请留意电话信息',
				    	showCancel: false,
				    	success() {
				    		uni.navigateBack({});
				    	}
				    })
				}})
			}
		}
	}
</script>

<style lang="scss" scoped>
.cover{
	width: 100%;
	height: 224rpx;
}
.inp_nav{
	width: 690rpx;
	height: 100rpx;
	background: #F5F8FC;
	border-radius: 50rpx;
	margin-top: 30rpx;
	padding-left: 35rpx;
	padding-right: 30rpx;
}
.foot_nav{
	width: 750rpx;
	box-shadow: 0rpx -1rpx 0rpx 0rpx #EEEEEE;
	padding: 10rpx 30rpx;
}
.bt1{
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
</style>
