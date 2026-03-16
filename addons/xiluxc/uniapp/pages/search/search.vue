<template>
	<view>
		<view class="container flex flex-col">
			<view class="page-head">
			<hx-navbar ref="hxnb" :config="config">
				<template slot="max" class="w100 flex-box">
					<view class="search_nav plr30 flex-box">
						<image src="@/static/icon/icon_search.png" mode="aspectFill" class="ico26"></image>
						<input type="text" confirm-type="search" placeholder="请输入搜索内容"@confirm="searchConfirm()" v-model="query.q" @focus="searchFocus()" class="flex-grow-1 pl10 fs24 col1" placeholder-class="col89" />
					</view>
				</template>
			</hx-navbar>
			</view>
			<view :style="{'paddingTop':`${barHeight}px`}"></view>
			<view class="flex-grow-1 p30" v-if="showSearch">
				<view class="fwb fs30 col1 lh30 pb10">历史搜索</view>
				<view>
					<view class="history_item" @click="searchClick(item)" v-for="(item,index) in searchHistoryList" :key="index">{{item}}</view>
				</view>
			</view>
			<view class="flex-grow-1 flex flex-col" v-if="!showSearch">
				<scroll-view class="tab_nav bg-white" scroll-x>
					<view class="tab_item" :class="[nindex==index?'active':'']" @click="chooseNav(index)"
						v-for="(item,index) in tab">{{item}}</view>
				</scroll-view>
				<scroll-view scroll-y="true" class="flex-grow-1" v-if="nindex==0">
					<store-list :shop-list="serviceList" :type="1"></store-list>
					<view class="nothing" v-if="serviceMore.nothing">
						<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
						<text>暂无内容</text>
					</view>
					<view class="g-btn3-wrap" v-else>
						<view class="g-btn3" @click="fetchServiceList()">{{serviceMore.text}}</view>
					</view>
					<view class="pt30"></view>
				</scroll-view>
				<scroll-view scroll-y="true" class="flex-grow-1" v-if="nindex==1">
					<store-list :shop-list="shopList" :type="1"></store-list>
					<view class="nothing" v-if="shopMore.nothing">
						<image src="/static/icon/icon_nothing.png" mode="aspectFit"></image>
						<text>暂无内容</text>
					</view>
					<view class="g-btn3-wrap" v-else>
						<view class="g-btn3" @click="fetchShopList()">{{shopMore.text}}</view>
					</view>
					<view class="pt30"></view>
				</scroll-view>
			</view>
		</view>
	</view>
</template>

<script>
	import storeList from '../../components/store-list/store-list.vue';
	const app = getApp();
	export default {
		components: {
			storeList
		},
		data() {
			return {
				config: {
					color: '#101010',
					back: true,
					maxSlot: true,
					//背景颜色;参数一：透明度（0-1）;参数二：背景颜色（array则为线性渐变，string为单色背景）
					backgroundColor: [1, ['#FFFFFF', '#FFFFFF']],
				},
				tab: ['服务', '门店'],
				nindex: 0,
				statusBarHeight: null,
				barHeight: null,
				
				showSearch:true,
				currentCity: null,
				query:{q:''},
				searchHistoryList: [],
				
				serviceList: [],
				serviceMore: {page: 1},
				shopList:[],
				shopMore:{page:1},
				
			}
		},
		onLoad() {
			this.statusBarHeight = uni.getSystemInfoSync().statusBarHeight;
			// 胶囊数据
			// #ifdef MP
			const {
				top,
				height
			} = wx.getMenuButtonBoundingClientRect();
			// #endif
			// 自定义导航栏高度 = 胶囊高度 + 胶囊的padding*2, 如果获取不到设置为38
			// this.barHeight = height ? height + (top - this.statusBarHeight) * 2 : 38;
			this.barHeight = 44 + this.statusBarHeight;
			
				this.searchHistoryList = uni.getStorageSync('search_history') || [];
		},
		onReachBottom() {
			
		},
		methods: {
			//搜索
			searchConfirm() {
				let q = this.query.q.trim();
				if (q == '') {
					uni.showToast({title:"请输入搜索内容",icon:'none'});
					return;
				}
				//搜索列表存缓存
				let searchHistory = this.searchHistoryList;
				let index = searchHistory.indexOf(q);
				if (index !== -1) {
					searchHistory.splice(index, 1);
				}
				searchHistory.unshift(q.toString());
				uni.setStorageSync('search_history', searchHistory);
				this.searchHistoryList = searchHistory;
				this.showSearch = false;
				this.refresh();
			},
			searchFocus(){
				this.showSearch = true;
			},
			searchClick(e){
				this.query.q = e;
				this.searchConfirm();
			},
			chooseNav(index) {
				this.nindex = index
			},
			refresh(){
				this.serviceList = [];
				this.serviceMore = {page:1};
				this.shopList = [];
				this.shopMore = {page:1};
				this.fetchServiceList();
				this.fetchShopList();
			},
			//服务
			fetchServiceList() {
				let query = this.query;
				query.pagesize = 10;
				this.$util.fetch(this, 'xiluxc.shop/service_lists', query, 'serviceMore', 'serviceList', 'data', data => {

				})
			},
			//门店
			fetchShopList() {
				let query = this.query;
				query.pagesize = 10;
				let currentCity = this.$core.getCurrentCity();
				let lat = currentCity.pois ? currentCity.pois.latitude : app.globalData.location.latitude;
				let lng = currentCity.pois ? currentCity.pois.longitude : app.globalData.location.longitude
				query.lat = lat;
				query.lng = lng;
				this.$util.fetch(this, 'xiluxc.shop/index', query, 'shopMore', 'shopList', 'data', data=>{
					
				})
			},
			//门店详情
			onShopDetail(id) {
				uni.navigateTo({
					url: '/pages/stores_info/stores_info?id=' + id
				})
			},
			onServiceDetail(id,shopId){
				uni.navigateTo({
					url: '/pages/service_detail/service_detail?id='+id+'&shop_id='+shopId
				})
			},
			//购买
			onBuy(serviceId,shopId){
				let param = {
					type: 'service',
					shop_id: shopId,
					service_id: serviceId,
					service_price_id: 0
				};
				uni.navigateTo({
					url: '/pages/pay_order/pay_order?param='+encodeURIComponent(JSON.stringify(param))
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		height: 100vh;
		min-height: auto;
		
	}

	.w100 {
		width: 100%;
	}

	.search_nav {
		width: 100%;
		height: 70rpx;
		background: #F5F7FB;
		border-radius: 35rpx;
	}

	.history_item {
		height: 56rpx;
		line-height: 56rpx;
		margin-top: 20rpx;
		margin-right: 30rpx;
		background: #F5F8FC;
		border-radius: 28rpx;
		padding: 0 22rpx;
		font-size: 26rpx;
		color: #555555;
		display: inline-block;
		vertical-align: top;
	}

	.tab_nav {
		height: 94rpx;
		white-space: nowrap;

		.tab_item {
			height: 94rpx;
			line-height: 94rpx;
			display: inline-block;
			vertical-align: top;
			font-size: 30rpx;
			color: #898989;
			margin-right: 50rpx;

			&.active {
				font-weight: bold;
				font-size: 34rpx;
				color: #101010;
			}

			&:first-child {
				margin-left: 30rpx;
			}

			&:last-child {
				margin-right: 30rpx;
			}
		}
	}

	
	/* #ifndef MP */
	::v-deep .hx-navbar {
		&__content {
			&__main {
				&_center {
					padding-left: 80rpx;
				}
			}
		}
	}
	.search_nav {
		width: 90%;
		height: 70rpx;
		background: #F5F7FB;
		border-radius: 35rpx;
		margin-top: 5px;
	}
	/* #endif */
</style>