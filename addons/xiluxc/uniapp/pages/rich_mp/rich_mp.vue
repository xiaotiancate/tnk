<template>
	<view>
		<view class="container">
			<view class="ptb30 plr40">
				<view class="col1 fs40 fwb">{{article.name}}</view>
				<view class="mt30 col1 fs30">
					<!-- 富文本 -->
					<u-parse :content="article.content"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id: 0,
				article:{
					name: '',
					content: ''
				}
			}
		},
		onLoad(options) {
			this.id = options.id || 0
			this.fetchDetail();
		},
		methods: {
			fetchDetail(){
				this.$core.get({url: 'xiluxc.common/singlepage',data: {id: this.id},loading: false,success: ret => {
					this.article = ret.data;
					uni.setNavigationBarTitle({
						title: ret.data.name
					})
					},fail: err => {
						console.log(err);
					}
				});
			}
		}
	}
</script>

<style>

</style>
