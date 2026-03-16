<template>
	<view>
		<view class="page-foot bg-white">
			<view class="foot_nav" @click="bindSave()">
				<view class="bt1">确定提交</view>
			</view>
		</view>
		<view class="container">
			<view class="pt40 plr30">
				<!-- #ifdef MP-WEIXIN -->
				<button class="upload_nav" open-type="chooseAvatar" @chooseavatar="onChooseAvatar">
					<image :src="userinfo.avatar" mode="aspectFill" class="img"></image>
					<view class="mouble flex-box flex-center flex-col">
						<view>更换</view>
						<view>头像</view>
					</view>
				</button>
				
				<view class="inp_item flex-box">
					<view>昵称</view>
					<input type="nickname" placeholder="请输入" class="flex-grow-1 tr pl30 col3" placeholder-class="col3" @nicknamereview="onNickname" v-model="userinfo.nickname" />
				</view>
				<!-- #endif -->
				<!-- #ifdef H5 || APP-PLUS -->
				<button class="upload_nav"  @click="chooseImage" open-type="chooseAvatar" @chooseavatar="onChooseAvatar">
					<image :src="userinfo.avatar" mode="aspectFill" class="img"></image>
					<view class="mouble flex-box flex-center flex-col">
						<view>更换</view>
						<view>头像</view>
					</view>
				</button>
				<view class="inp_item flex-box">
					<view>昵称</view>
					<input type="text" placeholder="请输入" class="flex-grow-1 tr pl30 col3" placeholder-class="col3" v-model="userinfo.nickname" />
				</view>
				<!-- #endif -->
				
				
				<view class="inp_item flex-box">
					<view>手机号码</view>
					<input type="text" placeholder="请输入" class="flex-grow-1 tr pl30 col3" placeholder-class="col3" disabled :value="userinfo.mobile"/>
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
				userinfo: {
				    avatar: '',
				    nickname: '',
				    mobile: ''
				},
			}
		},
		onLoad() {
			this.getUserinfo()
		},
		methods: {
			getUserinfo() {
			    this.$core.get({
			        url: 'xiluxc.user/profile',
			        data: {},
			        loading: false,
			        success: ret => {
			            this.userinfo = ret.data;
			        },
			        fail: err => {
			            console.log(err);
			        }
			    });
			},
			onChooseAvatar(e) {
				var that = this
				//#ifdef MP-WEIXIN
				that.$core.uploadFile({
					filePath: e.detail.avatarUrl,
					success: (ret, response) => {
						that.userinfo.avatar = ret.data.url;
					}
				});
				//#endif
			},
			onNickname(e){
				if(!e.detail.pass){
					uni.showToast({title: '昵称不合法',icon: 'none'})
					this.userinfo.nickname = '';
				}
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
					    that.userinfo.avatar = ret.data.url;
					  }
					});
					//#endif
					//#ifdef MP-WEIXIN
			        that.$core.uploadFile({
			          filePath: file.path,
			          success: (ret, response) => {
			            that.userinfo.avatar = ret.data.url;
			          }
			        });
					//#endif
			      }
			    });
			},
			
			//提交
			bindSave() {
			    let formData = {
			        avatar: this.userinfo.avatar,
			        nickname: this.userinfo.nickname,
					gender: this.userinfo.gender
			    };
			
			    var rule = [{
			            name: 'avatar',
			            nameChn: '头像',
			            rules: ['require'],
			            errorMsg: {
			                require: '请上传头像'
			            }
			        },
			        {
			            name: 'nickname',
			            nameChn: '昵称',
			            rules: ['require'],
			            errorMsg: {
			                require: '请填写昵称'
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
			    this.$core.post({url: 'xiluxc.user/profile',data: formData,success: ret => {
			        let userinfo = this.$core.getUserinfo();
			        userinfo.avatar = ret.data.avatar;
			        userinfo.nickname = ret.data.nickname;
			        uni.$emit("user_update", {})
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
	.upload_nav {
		display: block;
		width: 160rpx;
		height: 160rpx;
		position: relative;
		border-radius: 50%;
		margin-left: auto;
		margin-right: auto;

		.mouble {
			width: 100%;
			height: 100%;
			position: absolute;
			top: 0;
			left: 0;
			border-radius: 50%;
			background: rgba(0, 0, 0, 0.4);
			font-size: 30rpx;
			color: #FFFFFF;
			line-height: 42rpx;
		}

		.img {
			width: 100%;
			height: 100%;
			border-radius: 50%;
			display: block;
		}
	}
	.inp_item{
		height: 130rpx;
		border-bottom: 1px solid #E5E5E5;
		padding:0 10rpx;
		font-size: 30rpx;
		color: #555555;
	}
	.page-foot{
		box-shadow: 0rpx -1rpx 0rpx 0rpx #EEEEEE;
	}
	.foot_nav{
		padding: 8rpx 30rpx;
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
	}
</style>