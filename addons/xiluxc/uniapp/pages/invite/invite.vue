<template>
	<view>
		<view class="container bg-f5">
			<view class="p30">
				<image :src="path" mode="widthFix" class="poster"></image>
				<!-- #ifndef H5 -->
				<view class="flex-box mt45 flex-center">
					<view class="bt1" @click="save()">保存图片</view>
					<button open-type="share" class="bt2 ml40">分享好友</button>
				</view>
				<!-- #endif -->
				

				<l-painter ref="painter" hidden isCanvasToTempFilePath @success="path = $event">
					<l-painter-view css="position: relative;width:690rpx;height:1168rpx">
						<l-painter-image :src="poster.poster_img"
							css="width: 690rpx; height: 1104rpx;object-fit: contain; object-position: 50% 50%;" />
						<l-painter-view
							css="width: 630rpx;height: 190rpx;background: #FFFFFF;box-shadow: 0rpx 4rpx 20rpx 5rpx rgba(183,189,202,0.05);border-radius: 20rpx;position: absolute;bottom:0;left:0;display:flex;align-items:center;padding:0 30rpx;right:0;">
							<l-painter-image :src="poster.avatar"
								css="width: 116rpx; height: 116rpx;object-fit: cover; object-position: 50% 50%;border-radius: 50%;" />
							<l-painter-view css="width:320rpx;padding:0 30rpx;">
								<l-painter-text :text="poster.nickname"
									css="font-weight: bold;font-size: 34rpx;color: #101010;line-clamp:1;display:block;" />
								<l-painter-text :text="poster.copywriting"
									css="margin-top:25rpx;font-size: 24rpx;color: #898989;line-clamp:1;display:block;" />

							</l-painter-view>
							<l-painter-image :src="poster.qrcode"
								css="width: 127rpx; height: 127rpx;object-fit: cover; object-position: 50% 50%;" />
						</l-painter-view>
					</l-painter-view>

				</l-painter>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		pathToBase64,
		base64ToPath
	} from '../../xilu/base64.js'
	export default {
		data() {
			return {
				poster: {
					nickname: '',
					copywriting: '',
					poster_img: '',
					avatar: '',
					qrcode: ''
				},
				path: ''
			}
		},
		onLoad() {
			this.sharePoster();
		},
		methods: {
			//弹窗内容
			sharePoster() {
				this.$core.post({
					url: 'xiluxc.user/poster',
					data: {},
					success: ret => {
						this.poster = ret.data;
						// #ifdef H5
						pathToBase64(ret.data.poster_img).then(base64 => {
							this.poster.poster_img = base64
						})
						pathToBase64(ret.data.avatar).then(base64 => {
							this.poster.avatar = base64
						})
						
						// #endif
						
					},
					fail: err => {
						console.log(err);
					}
				});
			},
			save() {
				this.$refs.painter.canvasToTempFilePathSync({
					fileType: "jpg",
					// 如果返回的是base64是无法使用 saveImageToPhotosAlbum，需要设置 pathType为url
					pathType: 'url',
					quality: 1,
					success: (res) => {
						console.log(res.tempFilePath);
						// 非H5 保存到相册
						// H5 提示用户长按图另存
						uni.saveImageToPhotosAlbum({
							filePath: res.tempFilePath,
							success: function() {
								console.log('save success');
							}
						});
					},
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.poster {
		width: 100%;
	}

	.bt1 {
		width: 250rpx;
		height: 85rpx;
		line-height: 85rpx;
		text-align: center;
		font-size: 28rpx;
		color: #FE4B01;
		background: rgba(254, 75, 1, .1);
		border-radius: 43rpx;
	}

	.bt2 {
		width: 250rpx;
		height: 85rpx;
		line-height: 85rpx;
		text-align: center;
		font-size: 28rpx;
		color: #FFFFFF;
		background: #FE4B01;
		border-radius: 43rpx;
	}
</style>