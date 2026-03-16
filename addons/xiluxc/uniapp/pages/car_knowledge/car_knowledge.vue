<template>
	<view>
		<view class="container bg-f0 flex flex-col">
			<scroll-view scroll-x="true" class="top_scroll">
				<view class="scroll_item" :class="[nindex==-1?'active':'']" @click="chooseNav(-1)">全部</view>
				<view class="scroll_item" :class="[nindex==index?'active':'']" @click="chooseNav(index)" v-for="(item,index) in categoryList">{{item.name}}</view>
			</scroll-view>
			<scroll-view scroll-y class="flex-grow-1">
				<uv-waterfall ref="waterfall" v-model="newsList" :add-time="10" :left-gap="leftGap" :right-gap="rightGap"
					:column-gap="columnGap" @changeList="changeList">
					<!-- 第一列数据 -->
					<template v-slot:list1>
						<!-- 为了磨平部分平台的BUG，必须套一层view -->
						<view>
							<view @click="onNewsDetail(item.id)" v-for="(item, index) in list1" :key="item.id"
								class="waterfall_item">
								<image :src="item.image_text" mode="widthFix" class="img">
								</image>
								<view class="mt20 fs30 col1 m-ellipsis-l2">{{item.name}}</view>
								<view class="flex-box mt20 fs24 col89">
									<image src="@/static/icon/icon_view.png" mode="aspectFill" class="ico24"></image>
									<view class="flex-grow-1 plr10 ">{{item.view_num}}浏览</view>
									<image src="@/static/icon/icon_time.png" mode="aspectFill" class="ico24"></image>
									<view class="pl10">{{item.createtime_text}}</view>
								</view>
							</view>
						</view>
					</template>
					<!-- 第二列数据 -->
					<template v-slot:list2>
						<!-- 为了磨平部分平台的BUG，必须套一层view -->
						<view>
							<view @click="onNewsDetail(item.id)" v-for="(item, index) in list2" :key="item.id" class="waterfall_item">
								<image :src="item.image_text" mode="widthFix" class="img">
								</image>
								<view class="mt20 fs30 col1 m-ellipsis-l2">{{item.name}}</view>
								<view class="flex-box mt20 fs24 col89">
									<image src="@/static/icon/icon_view.png" mode="aspectFill" class="ico24"></image>
									<view class="flex-grow-1 plr10 ">{{item.view_num}}浏览</view>
									<image src="@/static/icon/icon_time.png" mode="aspectFill" class="ico24"></image>
									<view class="pl10">{{item.createtime_text}}</view>
								</view>
							</view>
						</view>
					</template>
				</uv-waterfall>
				
				<view class="nothing" v-if="newsMore.nothing">
					<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
					<text>暂无内容</text>
				</view>
				<view class="g-btn3-wrap" v-else>
					<view class="g-btn3" @click="fetchNews">{{newsMore.text}}</view>
				</view>
				<view class="pt30"></view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				categoryList: [],
				nindex: -1,
				newsList: [],
				newsMore: {
					page: 1
				},
				list1: [], // 瀑布流第一列数据
				list2: [], // 瀑布流第二列数据
				leftGap: 15,
				rightGap: 15,
				columnGap: 10,
			}
		},
		computed: {
			imageStyle(item) {
				return item => {
					const v = uni.upx2px(750) - this.leftGap - this.rightGap - this.columnGap;
					const w = v / 2;
					const rate = w / item.w;
					const h = rate * item.h;
					return {
						width: w + 'px',
						height: h + 'px'
					}
				}
			}
		},
		onLoad() {
			this.refreshPage();
		},
		onReachBottom() {
			this.refreshNews();
		},
		onPullDownRefresh() {
			this.refreshPage();
		},
		methods: {
			refreshPage() {
				//分类
				this.$core.get({url: 'xiluxc.common/news_category',data: {},loading: false,success: (ret) => {
						this.categoryList = ret.data;
						//附近门店
						this.refreshNews();
					}
				})
				uni.stopPullDownRefresh();
			},
			chooseNav(index) {
				this.nindex = index;
				this.refreshNews();
			},
			refreshNews(){
				this.newsList = [];
				this.newsMore = {page: 1};
				this.$refs.waterfall.clear();
				this.list1 = [];
				this.list2 = [];
				this.fetchNews();
			},
			fetchNews() {
				let categoryId = this.nindex>=0 ? this.categoryList[this.nindex].id : 0;
				this.$util.fetch(this, 'xiluxc.news', {
					pagesize: 10,
					category_id: categoryId
				}, 'newsMore', 'newsList', 'data', data => {
			
				})
			},
			onNewsDetail(id) {
				uni.navigateTo({
					url: '/pages/car_knowledge_info/car_knowledge_info?id=' + id
				})
			},
			changeList(e) {
				this[e.name].push(e.value);
			},
		}
	}
</script>

<style lang="scss" scoped>
	.container {
	
		min-height: auto;
		/* #ifdef MP*/
		height: 100vh;
		/* #endif*/
		/* #ifndef MP */
		height: calc(100vh - 44px - 50px);
		/* #endif */
	}

	.top_scroll {
		background: #F0F2F5;
		height: 96rpx;
		white-space: nowrap;

		.scroll_item {
			height: 96rpx;
			line-height: 96rpx;
			display: inline-block;
			vertical-align: top;
			position: relative;
			font-size: 30rpx;
			color: #333333;
			margin-left: 76rpx;

			&:first-child {
				margin-left: 30rpx;
			}

			&:last-child {
				margin-right: 30rpx;
			}

			&.active {
				font-size: 36rpx;
				color: #101010;
				font-weight: bold;
			}

			&.active::after {
				content: '';
				width: 25rpx;
				height: 6rpx;
				background: #FE4B01;
				border-radius: 3rpx;
				position: absolute;
				bottom: 4rpx;
				left: 50%;
				transform: translateX(-50%);
			}
		}
	}

	.waterfall_item {
		width: 335rpx;
		margin-top: 30rpx;

		.img {
			width: 100%;
			border-radius: 20rpx;
		}
	}
</style>