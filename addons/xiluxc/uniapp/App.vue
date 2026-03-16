<script>
	export default {
		onLaunch: function() {

			let app = this;
			app.$core.onRequestComplete(function(ret, response) {
				if (ret && ret.code === 401) {
					if (app.$core.getUserinfo()) {
						app.$core.logout();
						uni.navigateTo({
							url: '/pages/login/login'
						});
						uni.showToast({
							title: '登录过期,请重新登录',
							icon: 'none'
						});
					}
				}
			});
			setTimeout(function() {
				// app初始化完成后再执行
				app.$core.get({
					url: 'xiluxc.common/init_config',
					data: {},
					loading: false,
					success: (ret) => {
						app.globalData.config = ret.data.config;
					}
				})
				//1.定位
			}, 1);

		},
		globalData: {
			//小程序配置的接口请求域名，为项目部署的服务器路径
			appid: '',
			apiBaseUri: "https://www.example.com/api",
			//web目录
            webDir:'/web/',
			////前端上传图片补全域名，如oss的https://xxxx.oss-cn-shanghai.aliyuncs.com，或https://your.site.com
			storageBaseUri: "",

			//阿里OSS的上传路径
			uploadOssStatus: false,
			alioss: {
				endpoint: ''
			},
			config: {
				logo: '',
				refund_reasons: [],
				apply_rule: '',
				user_agreement: 0,
				privacy_agreement: 0,
				shop_agreement: 0,
				brand_agreement: 0
			},
			Event: {
				CurrentCityChange: "currentCityChange",
				CurrentCityChange2: "currentCityChange2",
				loginOut: "loginOut",
			},
			location: {
				latitude: '',
				longitude: ''
			},
			defaultCity: {
				id: 104,
				name: '上海市',
				pois: null
			},
		},
		onShow: function(e) {
			if (e.query) {
				if(e.query.openid){
					this.$core.setCache('wx_openid', e.query.openid);
				}
				if (e.query.scene) {
					let scene = decodeURIComponent(e.query.scene);
					let options = {}
					for (var i = 0; i < scene.split('&').length; i++) {
						var arr = scene.split('&')[i].split('=');
						options[arr[0]] = arr[1];
					}
					if (options.uid != 'undefined' && options.uid > 0) {
						let a = {
							expire: 0,
							value: options.uid
						};
						try {
							uni.setStorageSync('puser_id' + this.globalData.appid, a);
						} catch (e) {
							//TODO handle the exception
							console.log(e);
						}

					}
				}

			}
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	@import "@/static/css/global.css";
	@import "@/uni_modules/uview-ui/index.scss";

	
</style>