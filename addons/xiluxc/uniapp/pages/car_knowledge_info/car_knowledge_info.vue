<template>
	<view>
		<view class="container">
			<view class="p30">
				<view class="fs40 fwb col1">{{news.name}}</view>
				<view class="flex-box fs24 col89 ptb30 bb">
					<image src="@/static/icon/icon_view.png" mode="aspectFill" class="ico24"></image>
					<view class="flex-grow-1 plr10 ">{{news.view_num}}浏览</view>
					<image src="@/static/icon/icon_time.png" mode="aspectFill" class="ico24"></image>
					<view class="pl10">{{news.createtime_text}}</view>
				</view>
				<view class="mt30 fs32">
					<!-- 富文本 -->
					<u-parse :content="news.content"></u-parse>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				newsId: 0,
				news: {name: '',content: ''}
			}
		},
		onLoad(options) {
			this.newsId = options.id || 0
			this.fetchNewsDetail()
		},
		methods: {
			fetchNewsDetail(){
				this.$core.get({url: 'xiluxc.news/detail',data:{news_id: this.newsId},loading:false,success:(ret)=>{
					this.news = ret.data;
				}})
			}
		}
	}
</script>

<style>

</style>