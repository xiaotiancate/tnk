let requestCompleteHandlers = [];
let requestLoadingCount = 0; //显示loading的次数，用户判定何时hideLoading
module.exports = {
	/**
	 * Make request with session info
	 * @param obj Object url, method, data, success, fail
	 */
	request: function(obj) {
		let userinfo = this.getCache('userinfo'),
			currentCity = this.getCurrentCity();
		obj.header = obj.header || {};
		obj.header.cityid = currentCity.id;
		// #ifdef MP-WEIXIN
		obj.header.platform = 'wxmini';
		// #endif
		// #ifdef H5
		obj.header.platform = 'h5';
		// #endif
		if (userinfo) {
			obj.header.token = userinfo.token;
		}
		if (!/^http/.test(obj.url)) {
			obj.url = getApp({
				allowDefault: true
			}).globalData.apiBaseUri + (/^\//.test(obj.url) ? obj.url : '/' + obj.url);
		}
		let successCall = obj.success;
		let failCall = obj.fail;
		let that = this;
		obj.success = function(res) {
			if (typeof res.data !== 'object' || res.data.code !== 1) {
				// 如果响应内容非json 或code不为1， 视为失败
				if (res.data.code == 401) {
					that.logout();
					uni.navigateTo({
						url: '/pages/login/login'
					})
				} else {
					obj.fail(res);
				}
				return;
			}
			if (typeof successCall === 'function') {
				setTimeout(() => {
					successCall(res.data, res);
				}, 1);

			}
		};
		obj.fail = function(res) {
			setTimeout(() => {
				let showMsg = true;
				//console.log(typeof failCall)
				if (typeof failCall === 'function') {
					showMsg = failCall(res.data, res);
				}
				if (showMsg !== false) {
					uni.showToast({
						'title': (res.data ? res.data.msg || '网络错误' : '网络错误'),
						icon: "none"
					})
				}
			}, 1);
		};
		obj.complete = function(res) {
			if (obj.loading !== false) {
				if (--requestLoadingCount <= 0) {
					requestLoadingCount = 0;
					uni.hideLoading();
				}

			}
			for (let i in requestCompleteHandlers) {
				if (typeof requestCompleteHandlers[i] === 'function') {
					requestCompleteHandlers[i].apply(this, [res.data, res]);
				}
			}
		};
		if (obj.loading !== false) {
			//转菊花，遮蔽操作
			requestLoadingCount++;
			uni.showLoading({
				title: '',
				mask: true
			});
		}
		return uni.request(obj);
	},
	// 发起GET
	get: function(obj) {
		obj.method = 'GET';
		return this.request(obj);
	},
	// 发起POST
	post: function(obj) {
		obj.method = 'POST';
		return this.request(obj);
	},
	//
	onRequestComplete: function(fn) {
		for (let i in requestCompleteHandlers) {
			if (requestCompleteHandlers[i] === fn) {
				//已经有同一个实例, 退出
				return;
			}
		}
		requestCompleteHandlers.push(fn);
	},
	// uploadFile obj.filePath[required]
	// obj.header[optional], obj.success[optional], obj.fail[optional]
	// return res.data.data.url
	uploadFile: function(obj) {
		// obj = Object.assign({url: '/common/upload_qiniu', name: 'file'}, obj);
		let userinfo = this.getCache('userinfo');
		let header = {}
		if (userinfo) {
			header = userinfo.token ? {
				token: userinfo.token
			} : '';
		}
		let tempFilePath = obj.filePath;
		let successCb = obj.success;
		let failCb = obj.fail;
		let fileSystemManager = uni.getFileSystemManager();
		fileSystemManager.getFileInfo({
			filePath: tempFilePath,
			success: res => {
				if (getApp().globalData.uploadOssStatus) {
					let size = res.size;
					let md5 = res.digest;
					let nameParts = tempFilePath.split('.');
					let suffix = nameParts[nameParts.length - 1];
					this.post({
						url: getApp().globalData.apiBaseUri + '/xiluxc.common/params',
						data: {
							md5: md5,
							name: 'nouse.' + suffix
						},
						success: (ret, response) => {
							//拿到签名，直传alioss
							let key = ret.data.key; //存储路径
							uni.uploadFile({
								name: 'file',
								filePath: tempFilePath,
								formData: {
									key: ret.data.key,
									OSSAccessKeyId: ret.data.id,
									success_action_status: 200,
									policy: ret.data.policy,
									signature: ret.data.signature
								},
								url: getApp().globalData.alioss.endpoint,
								success: res => {
									if (res.statusCode !== 200) {
										if (typeof failCb === 'function') {
											res.data = {
												code: 0,
												msg: '上传阿里云OSS失败'
											};
											failCb(res.data, res);
										} else {
											uni.showToast({
												'title': "上传阿里云OSS失败",
												icon: "none"
											})
										}
										return;
									}
									res.data = {
										code: 1,
										data: {
											url: getApp().globalData
												.storageBaseUri + '/' +
												key
										}
									};
									successCb(res.data, res);
								},
								fail: (res) => {
									if (typeof failCb === 'function') {
										res.data = {
											code: 0,
											msg: '上传阿里云OSS失败'
										};
										failCb(res.data, res);
									} else {
										uni.showToast({
											'title': "上传阿里云OSS失败",
											icon: "none"
										})
									}
								}
							});
						},
						fail: (ret, response) => {
							if (typeof failCb === 'function') {
								return failCb(ret, response);
							}
						}
					});
				} else {
					uni.showLoading({
						title: '上传中'
					})
					uni.uploadFile({
						name: 'file',
						filePath: tempFilePath,
						formData: {

						},
						url: getApp().globalData.apiBaseUri + '/common/upload',
						header: header,
						success: res => {
							uni.hideLoading();
							if (res.statusCode !== 200) {
								if (typeof failCb === 'function') {
									res.data = {
										code: 0,
										msg: '上传失败'
									};
									failCb(res.data, res);
								} else {
									uni.showToast({
										'title': "上传失败",
										icon: "none"
									})
								}
								return;
							}
							let data = JSON.parse(res.data);
							if (data.code == 1) {
								data = {
									code: 1,
									data: {
										url: data.data.fullurl
									}
								};
								successCb(data, res);
							} else {
								uni.showToast({
									'title': "上传失败",
									icon: "none"
								})
							}
						},
						fail: (res) => {
							uni.hideLoading();
							if (typeof failCb === 'function') {
								res.data = {
									code: 0,
									msg: '上传失败'
								};
								failCb(res.data, res);
							} else {
								uni.showToast({
									'title': "上传失败",
									icon: "none"
								})
							}
						}
					});
				}
			},
			error: res => {
				console.log(res);
			}
		});
	},
	uploadFileH5(obj) {
		let userinfo = this.getCache('userinfo');
		let header = {}
		if (userinfo) {
			header = userinfo.token ? {
				token: userinfo.token
			} : '';
		}
		let tempFilePath = obj.filePath;
		let successCb = obj.success;
		let failCb = obj.fail;
		uni.getFileInfo({
			filePath: tempFilePath,
			success: res => {
				uni.showLoading({title: '上传中'})
				uni.uploadFile({
					name: 'file',
					filePath: tempFilePath,
					formData: {},
					url: getApp().globalData.apiBaseUri + '/common/upload',
					header: header,
					success: res => {
						uni.hideLoading();
						if (res.statusCode !== 200) {
							if (typeof failCb === 'function') {
								res.data = {
									code: 0,
									msg: '上传失败'
								};
								failCb(res.data, res);
							} else {
								uni.showToast({
									'title': "上传失败",
									icon: "none"
								})
							}
							return;
						}
						let data = JSON.parse(res.data);
						if (data.code == 1) {
							data = {
								code: 1,
								data: {
									url: data.data.fullurl
								}
							};
							successCb(data, res);
						} else {
							uni.showToast({
								'title': "上传失败",
								icon: "none"
							})
						}
					},
					fail: (res) => {
						uni.hideLoading();
						if (typeof failCb === 'function') {
							res.data = {
								code: 0,
								msg: '上传失败'
							};
							failCb(res.data, res);
						} else {
							uni.showToast({
								'title': "上传失败",
								icon: "none"
							})
						}
					}
				});
			},
			error: res => {
				console.log(res);
			}
		});
	},
	setUserinfo(user) {
		if (!user) {
			return false;
		}
		this.setCache('userinfo', user);
	},
	// 获取当前登录用户信息
	getUserinfo(forceLogin = false) {
		let userinfo = this.getCache('userinfo');
		if (!userinfo && forceLogin) {
			uni.navigateTo({
				url: '/pages/login/login',
			});
			return false;
		}
		return userinfo;
	},
	//登出
	logout() {
		this.removeCache('userinfo');
		uni.$emit(getApp().globalData.Event.loginOut, {})
	},
	//h5静默登录获取openid
	checkH5Openid(url,param=''){
		// #ifdef H5
		let openid = this.getCache('wx_openid');
		if(openid == '' || openid == 'undefined' || !openid){
			let targetUrl = location.origin+getApp().globalData.webDir + url;
			if(param){
				location.href = getApp().globalData.apiBaseUri+"/xiluxc.user/auth?target_url="+ encodeURIComponent(targetUrl+"?param="+param);
			}else{
				location.href = getApp().globalData.apiBaseUri+"/xiluxc.user/auth?target_url="+ encodeURIComponent(targetUrl);
			}
		 return false;
		}else{
			url += param ? '?param='+param : '';
			uni.navigateTo({
				url: '/'+url
			})
		}
		//#endif
	},
	//获取城市
	getCurrentCity() {
		return this.getCache("current_city", getApp().globalData.defaultCity);
	},
	//设置城市
	setCurrentCity(city) {
		this.setCache("current_city", city);
		uni.$emit(getApp().globalData.Event.CurrentCityChange, city)
	},
	//获取经纬度
	getLatLng(cb){
		var that = this;
		uni.getLocation({
			type: 'gcj02',
			success: function (res) {
				cb(res.latitude,res.longitude)
			},
			fail: function(res){
				
			}
		});
	},
	//获取定位
	getLocation(){
		var that = this;
		uni.getLocation({
			type: 'gcj02',
			success: function (res) {
				getApp().globalData.location.latitude = res.latitude;
				getApp().globalData.location.longitude = res.longitude;
				that.getCityByLat(res.latitude,res.longitude)
			},
			fail: function(res){
				that.setCurrentCity(that.getCurrentCity());
			}
		});
	},
	getCityByLat(lat,lng){
		this.post({url: 'xiluxc.common/get_city_by_lat',loading: false,data: {lat: lat,lng: lng},success: (ret, response) => {
				this.setCurrentCity(ret.data);
			},
			fail: (ret, response) => {
				// uni.showToast({
				// 	title: '城市未开放',
				// 	icon:'none'
				// });
				this.setCurrentCity(this.getCurrentCity());
				return false;
			}
		});
	},
	//获取地区
	getCurrentDistrict() {
		return this.getCache("current_district", getApp().globalData.defaultDistrict);
	},
	//设置地区
	setCurrentDistrict(district) {
		this.setCache("current_district", district);
		uni.$emit(getApp().globalData.Event.CurrentDistrictChange, district)
	},

	// 获取缓存数据
	getCache: function(key, defaultValue) {
		let timestampNow = +new Date() / 1e3,
			result = "";
		timestampNow = parseInt(timestampNow);
		try {
			(result = uni.getStorageSync(key + getApp().globalData.appid)).expire > timestampNow || 0 == result
				.expire ? result = result.value : (result = "",
					this.removeCache(key));
		} catch (e) {
			result = void 0 === defaultValue ? "" : defaultValue;
		}
		return result || defaultValue;
	},
	// 设置缓存数据
	setCache: function(key, value, expireInSeconds) {
		let timestampNow = +new Date() / 1e3,
			result = true,
			a = {
				expire: expireInSeconds ? timestampNow + parseInt(expireInSeconds) : 0,
				value: value
			};
		try {
			uni.setStorageSync(key + getApp().globalData.appid, a);
		} catch (e) {
			result = false;
		}
		return result;
	},
	// 移除缓存数据
	removeCache: function(key) {
		let result = true;
		try {
			uni.removeStorageSync(key + getApp().globalData.appid);
		} catch (e) {
			result = false;
		}
		return result;
	},
	//富文本处理
	richTextnew(html) {
		let newheml = html.replace(/<img[^>]*>/gi, function(match,capture) {
			match = match.replace(/style="[^"]+"/gi, "").replace(/style='[^']+'/gi, "");
			match = match.replace(/width="[^"]+"/gi, "").replace(/width='[^']+'/gi, "");
			match = match.replace(/height="[^"]+"/gi, "").replace(/height='[^']+'/gi, "");
			return match;
		});
		newheml = newheml.replace(/style="[^"]+"/gi, function(match,capture) {
			match = match.replace(/width:[^;]+;/gi, "max-width:100%;").replace(/width:[^;]+;/gi, "max-width:100%;");
			return match;
		});
		newheml = newheml.replace(/<br[^>]*\/>/gi, "");
		newheml = newheml.replace(/\<img/gi,'<img style="max-width:100%;height:auto;display:block;margin-top:0;margin-bottom:0;"');
		return newheml;
	},
	//支付
	payment(wxconfig,cb,failed) {
		//#ifdef MP-WEIXIN
		uni.requestPayment({
			provider: 'wxpay',
			timeStamp: wxconfig.timeStamp,
			nonceStr: wxconfig.nonceStr,
			package: wxconfig.package,
			signType: wxconfig.signType,
			paySign: wxconfig.paySign,
			success: function(res) {
				uni.showToast({
					title: '支付成功'
				});
				if(typeof cb == 'function') cb()
			},
			fail: function(err) {
				uni.showToast({
					title: '支付失败',
					icon: 'none'
				});
				if(typeof failed == 'function') failed()
			}
		});
		//#endif
	},
	// 静默获取openid, 然后从服务器拉取wx_account信息
	wxLogin(cb) {
		let that = this;
		function executeLogin(cb) {
			uni.login({
				provider: 'weixin',
				success: (auth) => {
					let code = auth.code;
					that.post({
						url: 'xiluxc.user/wxlogin',
						loading: true,
						data: {
							code: code,
							platform: 'wxmini',
						},
						success: (ret, response) => {
							let third = ret.data.third;
							that.setCache('wx_account', third);
							if (third.binding == 1) {
								that.setUserinfo(ret.data.userinfo);
								if(typeof cb == 'function'){
									cb(ret.data.userinfo)
								}
							}
						},
						fail: (ret, response) => {
							//失败，重试
							setTimeout(function() {
								executeLogin();
							}, 60000);
							return false;
						}
					});
				},
			});
		}
		uni.checkSession({
			success() {
				executeLogin(cb);
			},
			fail() {
				executeLogin(cb);
			}
		});
	},
};