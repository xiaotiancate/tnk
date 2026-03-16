<template>
	<view>
		<view class="page-foot bg-white">
			<view class="ptb10 plr30"  @click="onSave">
				<view class="btn2">确定提交</view>
			</view>
		</view>
		<view class="container">
			<view class="p30">
				<textarea class="textarea" placeholder="请输入你的投诉" v-model="formData.content" placeholder-class="col1"></textarea>
				<view class="mt50 fs30 col5 pb20">上传图片</view>
				<view>
					<block v-if="formData.images.length>0">
						<view class="upload_nav" v-for="(img,index) in formData.images" :key="index">
							<image :src="img" mode="aspectFill" class="img"></image>
							<image @click="imgDel(index)" src="@/static/icon/icon_del.png" mode="aspectFill" class="del"></image>
						</view>
					</block>
					<view class="upload_nav" @click="chooseImage()">
						<image src="@/static/images/icon_upload.png" mode="aspectFill" class="img"></image>
						<!-- <image src="@/static/icon/icon_del.png" mode="aspectFill" class="del"></image> -->
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
				formData: {
					content: '',
					images: []
				},
			}
		},
		methods: {
			chooseImage() {
				let page = this;
				uni.chooseMedia({
					count: 1,
					mediaType: ['image'],
					sourceType: ['album', 'camera'],
					success(res) {
						res.tempFiles.forEach((item, index) => {
							// #ifdef H5
							page.$core.uploadFileH5({
								filePath: item.tempFilePath,
								success: (ret, response) => {
									page.formData.images.push(ret.data.url);
								}
							})
							//#endif
							//#ifdef MP-WEIXIN
							page.$core.uploadFile({
								filePath: item.tempFilePath,
								success: (ret, response) => {
									page.formData.images.push(ret.data.url);
								}
							})
							//#endif
						})
					}
				})
			},
			imgDel(index){
				this.formData.images.splice(index,1);
			},
			onSave(){
				let formData = this.formData;
				var rule = [
				    {
				        name: 'content',
				        nameChn: '内容 ',
				        rules: ['require'],
				        errorMsg: {
				            require: '投诉内容不得为空'
				        }
				    }
				];
				// 是否全部通过，返回Boolean		
				if (!validate.check(formData, rule)) {
				    uni.showToast({
				        title: validate.getError()[0],
				        icon: 'none'
				    });
				    return;
				}
				this.$core.post({url: 'xiluxc.user/advice',data: formData,success: ret => {
					 this.getOpenerEventChannel().emit("adviceSuccess",{})
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
	.textarea {
		width: 690rpx;
		height: 380rpx;
		background: #F7F9FC;
		border-radius: 20rpx;
		padding: 25rpx;
		font-size: 30rpx;
		color: #101010;
	}

	.upload_nav {
		width: 196rpx;
		height: 196rpx;
		border-radius: 15rpx;
		display: inline-block;
		vertical-align: top;
		margin-top: 20rpx;
		margin-right: 20rpx;
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

	.page-foot {
		box-shadow: 0rpx -1rpx 0rpx 0rpx #EEEEEE;
	}
</style>