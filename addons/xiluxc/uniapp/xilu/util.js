let core = require('./core.js');
const formatDatetime = function(date, fmt) { //author: meizz
  if(!date) {
    return "";
  }
  if(!(date instanceof Date)) {
    if(/^\d+$/.test(date)) {
      date = (date+"").length>10 ? date : date*1000;
      date = new Date(parseInt(date));
    }else {
      date = new Date(date.replace(/-/g,'/'));
    }
  }
  var o = {
    "m+": date.getMonth() + 1, //月份
    "d+": date.getDate(), //日
    "h+": date.getHours(), //小时
    "i+": date.getMinutes(), //分
    "s+": date.getSeconds(), //秒
    "q+": Math.floor((date.getMonth() + 3) / 3), //季度
    "S": date.getMilliseconds() //毫秒
  };
  var yearMatch = fmt.match(/(y+)/);
  if (yearMatch) {
    fmt = fmt.replace(yearMatch[0], (date.getFullYear() + "").substr(4 - yearMatch[0].length));
  }
  for (var k in o) {
    var match = fmt.match(new RegExp("(" + k + ")"));
    if (match)
      fmt = fmt.replace(match[0], (match[0].length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
  }
  return fmt;
};
/**
 * 加载更多按钮功能
 * page.data定义moreButton(可由moreButtonDataField更改) moreButton.text作为按钮文本, moreButton.page作为页码
 * @param pageInstance
 * @param url 接口地址
 * @param queryData querystring parameters
 * @param moreButtonDataField morebutton data在page.data中的字段名
 * @param listFieldName 渲染的列表数据再page.data中的字段名
 * @param retDataFieldName 接口返回的列表数据再http response ret.data中的字段名
 * @param dataFormatter function, 处理服务器返回的列表数据，返回处理过后的数组, this指向pageInstance
 */
const fetch = function(pageInstance, url,queryData, moreButtonDataField, listFieldName, retDataFieldName, dataFormatter) {
  let moreButtonData = pageInstance[moreButtonDataField] || {page:1};
  if(moreButtonData.loading || moreButtonData.nomore) {
    return;
 }
  queryData.page = moreButtonData.page;
  pageInstance[moreButtonDataField]['loading'] = true;
  pageInstance[moreButtonDataField]['text'] = '正在加载...';
  pageInstance.$forceUpdate();
  core.get({
    url: url, data: queryData,
	loading:false,
    success(ret, response) {
      let listData = retDataFieldName?ret.data[retDataFieldName]:ret.data;
      let listLength = listData.length;
      if(typeof dataFormatter === 'function') {
        //listData = dataFormatter.apply(pageInstance, [listData, ret.data]) || listData;
		dataFormatter(ret.data);
      }else if(dataFormatter==='unique') {
        function uniqueFormatter(listData) {
          let formattedList = [];
          out:  for(let i in listData) {
            let item = listData[i];
            for(let j in pageInstance[listFieldName]) {
              let originItem = pageInstance[listFieldName][j];
              if(originItem.id==item.id) {
                continue out;
              }
            }
            formattedList.push(item);
          }
          return formattedList;
        }
        listData = uniqueFormatter(listData);
      }
      if(Array.isArray(listData) && listLength>0) {
        pageInstance[listFieldName] = (pageInstance[listFieldName] || []).concat(listData);
        let pagesize = queryData.pagesize || 10;
        
        if(listLength<pagesize) {
		  pageInstance[moreButtonDataField]['page'] = moreButtonData.page + 1;
		  pageInstance[moreButtonDataField]['loading'] = false;
		  pageInstance[moreButtonDataField]['nomore'] = true;
		  pageInstance[moreButtonDataField]['text'] = '—— 我是有底线的 ——';
        }else {
			
			pageInstance[moreButtonDataField]['page'] = moreButtonData.page + 1;
			pageInstance[moreButtonDataField]['loading'] = false;
			pageInstance[moreButtonDataField]['text'] = '加载更多';
        }
      }else {
        let text = moreButtonData.page == 1 ? '暂无数据' : '—— 我是有底线的 ——';
        let nothing = moreButtonData.page == 1 ? true : false;
		pageInstance[moreButtonDataField]['nomore'] = true;
		pageInstance[moreButtonDataField]['text'] = text;
		pageInstance[moreButtonDataField]['nothing'] = nothing;
      }
	  pageInstance.$forceUpdate();
    },
    fail(ret, response) {
		pageInstance[moreButtonDataField]['loading'] = false;
		pageInstance[moreButtonDataField]['text'] = '加载更多';
    }
  })
};
const getRemoteDataCached = async function(options) {
  let data, url = options.url, cacheKey = options.cacheKey, useCache = options.useCache===undefined ? true : options.useCache,
    retDataKey = options.dataKey;
  if(useCache) {
    data = core.getCache(cacheKey);
  }
  if(!data) {
    data = await (new Promise(function(resolve, reject) {
      core.get({
        url: url,
        data: options.data || '',
        loading:false,
        success(ret) {
          resolve(retDataKey ? ret.data[retDataKey]:ret.data);
        },
        fail(ret) {
          reject(ret);
        }
      });
    }));
    core.setCache(cacheKey, data, 1800);
  }
  return data;
};

/**
 * 获取店铺属性
 * @param {boolean} useCache true则先从缓存中获取，否则直接取网络并放入缓存
 */
const getProperty = function (useCache=true) {
  let cacheKey = 'shop_property';
  return getRemoteDataCached({
    url: '/xiluxc.common/shop_property',
    data: {},
    cacheKey: cacheKey,
    useCache: useCache,
    dataKey: ''
  });
};
/**
 * 获取服务
 * @param {boolean} useCache true则先从缓存中获取，否则直接取网络并放入缓存
 */
const getService = function (useCache=true) {
  let cacheKey = 'service';
  return getRemoteDataCached({
    url: '/xiluxc.common/services',
    data: {},
    cacheKey: cacheKey,
    useCache: useCache,
    dataKey: ''
  });
};
/**
 * 获取未读消息
 * @param {boolean} useCache true则先从缓存中获取，否则直接取网络并放入缓存
 */
const getMessageCount = function (useCache=true) {
  let cacheKey = 'message_count';
  return getRemoteDataCached({
    url: '/xiluxc.message/unread',
    data: {},
    cacheKey: cacheKey,
    useCache: useCache,
    dataKey: ''
  });
};

module.exports = {
	formatDatetime:formatDatetime,
	fetch:fetch,
	getProperty: getProperty,
	getService: getService,
	getMessageCount:getMessageCount
};
