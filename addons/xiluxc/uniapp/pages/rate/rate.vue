<template>
	<view>
		<view class="page-foot">
			<view class="foot_nav" @click="onSave()">
				<view class="btn2">确定提交</view>
			</view>
		</view>
		<view class="container bg-f5">
			<view class="p30">
				<view class="box1">
					<view class="fwb fs30 col1">{{order.shop.name}}</view>
					<view class="mt25 flex-box">
						<image :src="order.order_item.image" mode="aspectFill" class="cover"></image>
						<view class="flex-grow-1 pl30">
							<view class="m-ellipsis fs30 col1 fwb lh30">{{order.order_item.title}}</view>
							<view class="fs24 lh24 col4 mt20">预约时间 {{order.appoint_date_text}}</view>
							<view class="col10 mt45 fs24 lh30">¥<text class="fs30">{{order.pay_fee}}</text></view>
						</view>
					</view>

				</view>
				<view class="box2">
					<view class="fs30 fwb">评价订单</view>
					<view class="flex-box mt40 fs30 col5">
						<view class="flex-grow-1">服务评价</view>
						<htz-rate v-model="comment.service_star" size="36" gutter="7" disHref="../../static/icon/icon_star_uc.png"
							checkedHref="../../static/icon/icon_star.png"></htz-rate>
					</view>
					<view class="flex-box mt45 fs30 col5">
						<view class="flex-grow-1">综合评价</view>
						<htz-rate v-model="comment.comprehensive_star" size="36" gutter="7" disHref="../../static/icon/icon_star_uc.png"
							checkedHref="../../static/icon/icon_star.png"></htz-rate>
					</view>
					<textarea placeholder="请输入你要评价的内容" v-model="comment.content" class="textarea" placeholder-class="cola"></textarea>
					<view class="mt50 col5 fs30">上传照片</view>
					<view>
						<view class="upload_nav" v-for="(img,index) in images" :key="index">
							<image :src="img" mode="aspectFill" class="img"></image>
							<image @click="bindDel(index)" src="@/static/icon/icon_del.png" mode="aspectFill" class="del"></image>
						</view>
						<view class="upload_nav" v-if="images.length<9" @click="chooseImages()">
							<image src="@/static/images/icon_upload.png" mode="aspectFill" class="img"></image>
							
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import htzRate from '@/components/htz-rate/htz-rate.vue';
	var validate = require("../../xilu/validate.js");
	export default {
		components: {
			htzRate,
		},
		data() {
			return {
				orderId: 0,
				order: {
					shop:{
						name:'',
						address:''
					},
					order_item:{
						sku_text:'',
						title:'',
					},
					qrcode:{
						qrcode:'',
						code: ''
					},
					appoint_date_text:''
				},
				images: [],
				comment:{
					service_star: 5,
					comprehensive_star: 5,
					content: ''
				},
				
			}
		},
		onLoad(options) {
			this.orderId = options.id;
			this.fetchDetail();
		},
		methods: {
			fetchDetail(){
				this.$core.get({url:'xiluxc.order/detail',data:{order_id: this.orderId},success:(ret)=>{
					this.order = ret.data;
				}});
			},
			chooseImages(){
				let that = this;
				let images = that.images;
				uni.chooseImage({
					count: 9-images.length,
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
			bindDel(index){
				this.images.splice(index,1);
			},
			onSave(){
				let formData = this.comment;
				formData.order_id = this.order.id;
				formData.images = this.images.length>0?this.images.join(','):'';
				var rule = [
					{name: 'content',nameChn: '评价内容',rules: ['require','max:200'],errorMsg: {require: '请填写评价内容',max: '评价内容最多200'}},
				];
				// 是否全部通过，返回Boolean
				if (!validate.check(formData, rule)) {
				    uni.showToast({
				        title: validate.getError()[0],
				        icon: 'none'
				    });
				    return;
				}
				this.$core.post({url: 'xiluxc.comment/add_comment',data: formData,success: ret => {
				    this.getOpenerEventChannel().emit("commentSuccess",{})
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
	.box1 {
		width: 690rpx;
		padding: 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;

		.cover {
			width: 150rpx;
			height: 150rpx;
			border-radius: 15rpx;
		}
	}

	.box2 {
		width: 690rpx;
		padding: 35rpx 30rpx 30rpx;
		background: #FFFFFF;
		border-radius: 15rpx;
	}

	.textarea {
		margin-top: 50rpx;
		width: 630rpx;
		height: 246rpx;
		background: #F5F7FB;
		border-radius: 15rpx;
		padding: 30rpx;
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
	.page-foot{
		
		background: #FFFFFF;
		box-shadow: inset 0rpx 1rpx 0rpx 0rpx #EEEEEE;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		.foot_nav{
			padding:10rpx 30rpx;
		}
	}
</style>