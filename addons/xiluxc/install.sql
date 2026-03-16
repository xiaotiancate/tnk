-- ----------------------------
-- Table structure for __PREFIX__xiluxc_advice
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_advice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '建议',
  `images` varchar(2000) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片',
  `reply_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '回复状态:0=待处理,1=已处理',
  `reply_content` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `replytime` bigint(16) DEFAULT NULL COMMENT '回复时间',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '处理时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='投诉建议';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_aftersale
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_aftersale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `aftersale_type` enum('service','package') COLLATE utf8mb4_general_ci DEFAULT 'service' COMMENT '售后类型:service=服务,package=套餐',
  `order_no` char(32) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '售后订单号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID，服务时是申请人',
  `user_package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '套餐ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '套餐关联订单',
  `refund_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `reason` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '理由',
  `status` enum('-1','0','1') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '退款状态:-1=拒绝,0=申请退款,1=同意',
  `refuse_reason` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `agreetime` bigint(16) DEFAULT NULL COMMENT '同意申请时间',
  `refusetime` bigint(16) DEFAULT NULL COMMENT '拒绝时间',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_user_id` (`order_id`,`user_id`) USING BTREE,
  KEY `user_package_id` (`user_package_id`),
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='售后退款';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_area
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父id',
  `shortname` varchar(100) DEFAULT NULL COMMENT '简称',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `mergename` varchar(255) DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) DEFAULT NULL COMMENT '级别:1=省,2=市,3=区,4=街道',
  `pinyin` varchar(100) DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) DEFAULT NULL COMMENT '长途区号',
  `zip` varchar(100) DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
  `is_re` tinyint(1) unsigned DEFAULT '1' COMMENT '是否热门:1=否,2=是',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '上下架状态:normal=上架,hidden=下架',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46925 DEFAULT CHARSET=utf8mb4 COMMENT='地区表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_banner
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `minapp_url` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序链接',
  `thumb_image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `group` varchar(50) NOT NULL DEFAULT '' COMMENT '分组',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `show_count` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `deletetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='图片banner';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_car_brand
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_car_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌名',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌LOGO',
  `first_letter` varchar(2) NOT NULL DEFAULT '' COMMENT '首字母',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三方ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=568 DEFAULT CHARSET=utf8mb4 COMMENT='汽车品牌';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_car_models
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_car_models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `series_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '车系ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '车系名称',
  `year` varchar(10) NOT NULL DEFAULT '' COMMENT '年份',
  `peizhi` varchar(50) NOT NULL DEFAULT '' COMMENT '配置',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `models_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '型号ID',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `deletetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `series_id` (`series_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3925 DEFAULT CHARSET=utf8mb4 COMMENT='汽车型号';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_car_series
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_car_series` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '车系名',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌',
  `levelid` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '车系类型:1=微型车,2=小型车,3=紧凑型车,4=中型车,5=中大型车,6=大型车,7=跑车,8=MPV,11=微面,12=微卡,13=轻客,14=低端皮卡,15=高端皮卡,16=小型SUV,17=紧凑型SUV,18=中型SUV,19=中大型SUV,20=大型SUV,21=紧凑型MPV',
  `levelname` varchar(50) NOT NULL DEFAULT '' COMMENT '车系类型名称',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `series_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系列ID',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `deletetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `brand_id` (`brand_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1129 DEFAULT CHARSET=utf8mb4 COMMENT='汽车型号';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_config
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '变量名',
  `group` varchar(30) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '分组',
  `title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `visible` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '可见条件',
  `value` text COLLATE utf8mb4_general_ci COMMENT '变量值',
  `content` text COLLATE utf8mb4_general_ci COMMENT '变量字典数据',
  `rule` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='基础配置';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_coupon
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `use_start_time` bigint(16) DEFAULT NULL COMMENT '使用开始时间',
  `use_end_time` bigint(16) DEFAULT NULL COMMENT '使用结束时间',
  `max_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发行数量',
  `at_least` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '使用门槛',
  `type` enum('1','2') DEFAULT '1' COMMENT '类型:1=满减,2=折扣',
  `discount` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '折扣（折扣类型必填）',
  `money` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '减免金额',
  `freight_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '发放形式:1=手动领取,2=注册赠送',
  `range_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '使用类型:1=服务,2=套餐',
  `range_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '使用范围:0=部分,1=全部',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=下架,normal=上架',
  `receive_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取数量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='优惠券管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_coupon_items
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_coupon_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `target_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '参数',
  `createtime` bigint(16) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `coupon_target_id` (`coupon_id`,`target_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='优惠券使用范围';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_divide
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_divide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型:vip_order=会员卡,service_order=服务3=套餐',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单用户ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `ready_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已分金额',
  `shop_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销金额（门店获得金额）',
  `platform_rate` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '平台费率',
  `platform_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '平台收益',
  `first_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级用户',
  `first_rate` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '一级比例',
  `first_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
  `second_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级佣金',
  `second_rate` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '二级比例',
  `second_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `status` enum('0','1','2','3') NOT NULL DEFAULT '1' COMMENT '状态:0=失效,1=冻结中,2=部分退款,3=正常',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `unfreezetime` bigint(16) DEFAULT NULL COMMENT '解冻时间',
  `canceltime` bigint(16) DEFAULT NULL COMMENT '取消时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_order_type_id` (`shop_id`,`order_id`,`type`) USING BTREE,
  KEY `first_user_id` (`first_user_id`) USING BTREE,
  KEY `second_user_id` (`second_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COMMENT='分销基础信息';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_money_log
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1=余额,2=佣金,3=门店',
  `event` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '具体类型',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `divide_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '冻结金额ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `withdraw_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `memo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态:-1=作废,0=冻结,1=正常',
  `extra` varchar(600) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员余额变动表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_navigation
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '名称',
  `icon_image` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1=内链,2=外链,3=其他小程序',
  `mini_appid` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方APPID',
  `jump_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '跳转:1=navigate,2=switchtab',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'hidden' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='金刚区';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_news
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_news` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '封面图',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `content` longtext CHARACTER SET utf8mb4 COMMENT '内容',
  `weigh` int(10) unsigned DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `favorite_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='知识库管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_news_category
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='知识库分类';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_notice
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看人数',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='平台公告';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_order
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('service','package') NOT NULL DEFAULT 'service' COMMENT '订单类型:service=服务订单,package=套餐订单',
  `order_no` varchar(60) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_trade_no` varchar(40) NOT NULL DEFAULT '' COMMENT '商户交易单号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `pay_type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型:1=微信,2=余额,3=套餐',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
  `shop_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商家金额（扣除优惠券）',
  `points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `points_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分抵扣金额',
  `pay_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付总金额',
  `status` enum('closed','cancel','unpaid','paid') NOT NULL DEFAULT 'unpaid' COMMENT '订单状态:closed=交易关闭,cancel=已取消,unpaid=未支付,paid=已支付,completed=已完成,pending=待定',
  `appoint_date` bigint(16) DEFAULT NULL COMMENT '预约日期',
  `paid_time` bigint(16) DEFAULT NULL COMMENT '支付成功时间',
  `refund_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '申请退款状态:0=未申请,1=用户申请退款,-1=拒绝申请,2=退款成功',
  `coupon_discount_fee` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '优惠券抵扣金额',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券',
  `ext` varchar(2048) DEFAULT NULL COMMENT '附加信息',
  `remark` varchar(255) DEFAULT NULL COMMENT '用户备注',
  `platform` enum('h5','app','wxmini','wxoffical','pc') DEFAULT NULL COMMENT '平台:h5=H5,wxofficial=微信公众号,wxmini=微信小程序,app=App,pc=PC',
  `shop_id` int(10) unsigned DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌',
  `user_package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '使用的套餐ID',
  `comment_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '评价状态:0=带评价,1=已评价',
  `commenttime` bigint(16) DEFAULT NULL COMMENT '评价时间',
  `verify_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '核销状态:0=待核销,1=已核销',
  `verifytime` bigint(16) DEFAULT NULL COMMENT '核销时间',
  `trade_no` varchar(30) DEFAULT NULL COMMENT '交易单号',
  `order_ip` varchar(30) NOT NULL DEFAULT '' COMMENT '下单IP',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `order_sn` (`order_no`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COMMENT='订单管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_order_coupon
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_order_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `coupon_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券ID',
  `coupon_name` varchar(50) NOT NULL DEFAULT '0.00' COMMENT '优惠券名称',
  `coupon_money` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券金额',
  `at_least` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '门槛',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='订单优惠券';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_order_item
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_order_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `data_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品',
  `title` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `service_price_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务规格ID',
  `branch_service_id` int(10) NOT NULL DEFAULT '0' COMMENT '分店服务ID',
  `sku_text` varchar(50) NOT NULL DEFAULT '' COMMENT '规格名',
  `image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `salesprice` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品价格',
  `vip_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '会员价格',
  `discount_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠费用',
  `ext` text NOT NULL COMMENT '扩展数据',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `goods_id` (`data_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COMMENT='订单商品';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_order_log
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单类型:1=服务,2=套餐',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `aftersale_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '售后订单ID',
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '售后标题',
  `operate_type` enum('user','admin') COLLATE utf8mb4_general_ci DEFAULT 'user' COMMENT '操作人类型',
  `operate_id` int(10) unsigned DEFAULT '0' COMMENT '操作人id',
  `description` varchar(500) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `images` varchar(1500) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图片',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='订单日志';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_order_qrcode
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_order_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `qrcode` varchar(255) NOT NULL DEFAULT '' COMMENT '核销码',
  `verifier_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '核销状态:0=未核销,1=已核销',
  `verifytime` bigint(16) DEFAULT NULL COMMENT '核销时间',
  `verifier_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '核销人',
  `user_package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '套餐ID',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '券码',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT '核销token',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_verfier_id` (`order_id`,`verifier_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COMMENT='服务订单核销码';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_property
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='属性管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_recharge
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值金额',
  `extra_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '额外赠送',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='充值金额';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_recharge_order
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_recharge_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单用户',
  `platform` varchar(10) NOT NULL DEFAULT '1' COMMENT '下单平台:1=wxmini,2=app',
  `recharge_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额id',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '交易单号',
  `pay_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型:1=微信,2=支付宝,3=后台人工',
  `pay_fee` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额',
  `pay_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid' COMMENT '支付状态:unpaid=待支付,paid=已支付',
  `paytime` bigint(16) DEFAULT NULL COMMENT '支付时间',
  `trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '三方单号',
  `recharge_money` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '充值金额',
  `recharge_extra_money` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '充值赠送金额',
  `recharge_total_money` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '到账金额',
  `real_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '下单id',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `admin_id` int(10) NOT NULL COMMENT '管理员',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='充值订单';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_score_log
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '具体类型',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `divide_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '冻结金额ID',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `score` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态:-1=作废,0=冻结,1=正常',
  `extra` varchar(600) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员积分变动表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_service
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `distribution_one_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一级返佣比例',
  `distribution_two_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '二级返佣比例',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='服务管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_service_comment
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_service_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单',
  `order_item_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单服务ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务',
  `shop_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店服务',
  `service_price_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务规格',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `images` varchar(2000) NOT NULL DEFAULT '' COMMENT '图片',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '评价内容',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=上架',
  `avg_star` float(2,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '平均分',
  `service_star` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '服务评价',
  `comprehensive_star` tinyint(2) DEFAULT NULL COMMENT '综合评价',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '发布时间',
  `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `order_item_id` (`order_item_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='服务评价';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `connector` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人名称',
  `type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '类型:1=普通店,2=连锁店',
  `concat_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省id',
  `city_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '市id',
  `district_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区/县id',
  `address` varchar(50) NOT NULL DEFAULT '' COMMENT '详细地址',
  `lng` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `lat` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `legal_person` varchar(50) NOT NULL DEFAULT '' COMMENT '法人',
  `legal_idcard` varchar(20) NOT NULL DEFAULT '' COMMENT '法人身份证',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简单介绍',
  `license_image` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照',
  `images` varchar(2000) NOT NULL DEFAULT '' COMMENT '场馆图片',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '主图',
  `starttime` varchar(10) DEFAULT NULL COMMENT '营业时间',
  `endtime` varchar(10) DEFAULT NULL COMMENT '营业结束时间',
  `point` decimal(2,1) unsigned NOT NULL DEFAULT '5.0' COMMENT '评分',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `audit_status` enum('checked','passed','failed') NOT NULL DEFAULT 'checked' COMMENT '审核状态:checked=审核中,passed=通过,failed=已拒绝',
  `refuse_reason` varchar(255) DEFAULT '' COMMENT '拒绝理由',
  `audittime` bigint(16) DEFAULT NULL COMMENT '审核时间',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '归属的品牌账号ID',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `city_id` (`city_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='门店管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_account
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `vip_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '会员费率',
  `rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '服务费率',
  `total_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '累计佣金',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `withdraw_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '累计提现金额',
  `platform_message` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动消息',
  `system_message` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息',
  `user_message` int(10) unsigned DEFAULT '0' COMMENT '个人消息',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='门店账户表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_branch_package
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_branch_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `shop_package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店（品牌）套餐ID',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_service_id` (`shop_package_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='分店套餐';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_branch_service
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_branch_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务ID',
  `shop_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店（品牌）服务ID',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_service_id` (`shop_service_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COMMENT='分店服务';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_brand
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号ID',
  `brand_name` varchar(50) NOT NULL DEFAULT '' COMMENT '品牌名',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌LOGO',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '品牌介绍',
  `concat_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=显示',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `deletetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='品牌信息';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_package
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '套餐名称',
  `sub_title` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `vip_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `content` mediumtext NOT NULL COMMENT '详情',
  `notice` text NOT NULL COMMENT '套餐说明',
  `distribution_one_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一级返佣比例',
  `distribution_two_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '二级返佣比例',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='门店套餐';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_package_service
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_package_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '套餐ID',
  `shop_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店服务ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务ID',
  `service_price_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务价格ID',
  `use_count` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '包含次数',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `package_service_id` (`package_id`,`service_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COMMENT='门店套餐服务';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_property
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `property_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性ID',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `shop_property_id` (`shop_id`,`property_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='门店属性';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_service
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务ID',
  `sub_title` varchar(255) NOT NULL DEFAULT '' COMMENT '简单描述',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `vip_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价',
  `content` text NOT NULL COMMENT '详情',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_service_id` (`shop_id`,`service_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COMMENT='门店服务';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_service_price
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_service_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务ID',
  `shop_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店服务ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '项目名',
  `salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `vip_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE,
  KEY `service_id` (`service_id`) USING BTREE,
  KEY `shop_service_id` (`shop_service_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COMMENT='门店服务价格';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_tag
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `tag_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签ID',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `shop_tag_id` (`shop_id`,`tag_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='门店标签';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_user
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_user_id` (`shop_id`,`user_id`,`brand_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='门店会员表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_verifier
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_verifier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) DEFAULT '' COMMENT '用户名',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机号',
  `password` varchar(32) DEFAULT '' COMMENT '密码',
  `salt` varchar(30) DEFAULT '' COMMENT '密码盐',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'hidden' COMMENT '状态:hidden=隐藏,normal=正常',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `mobile` (`mobile`) USING BTREE,
  KEY `username` (`username`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='门店核销员';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_vip
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_vip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店ID',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `original_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价',
  `days` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '天数',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `privilege` text NOT NULL COMMENT '权益',
  `vip_agreement` mediumtext NOT NULL COMMENT '会员权益',
  `distribution_one_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '一级返佣比例',
  `distribution_two_rate` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '二级返佣比例',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='门店会员卡';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_shop_withdraw
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_shop_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1=支付宝,2=银行卡',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现用户',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现门店',
  `account` varchar(50) NOT NULL DEFAULT '' COMMENT '支付宝',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `bank_no` varchar(30) NOT NULL DEFAULT '' COMMENT '卡号',
  `bank` varchar(50) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_branch` varchar(80) NOT NULL DEFAULT '' COMMENT '开户支行',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `real_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账',
  `rate` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费率',
  `rate_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:1=审核中,2=处理中,3=已处理,4=已拒绝',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '拒绝理由',
  `certificate` varchar(255) NOT NULL DEFAULT '' COMMENT '打款凭证',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '提现订单号',
  `checktime` bigint(16) DEFAULT NULL COMMENT '审核时间',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='门店提现';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_singlepage
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_singlepage` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '名称',
  `content` text CHARACTER SET utf8mb4 COMMENT '内容',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `view_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `weigh` int(10) unsigned DEFAULT '0' COMMENT '权重',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
  `deletetime` bigint(16) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='单页管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_tag
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('news','shop') NOT NULL DEFAULT 'news' COMMENT '类型:news=知识库,shop=门店',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `status` enum('hidden','normal') CHARACTER SET utf8 NOT NULL DEFAULT 'normal' COMMENT '状态:hidden=隐藏,normal=正常',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='标签管理';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_third
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `platform` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方应用',
  `openid` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方唯一ID',
  `openname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方会员昵称',
  `avatar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `access_token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'RefreshToken',
  `unionid` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'UnionId',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
  `auth_userinfo` enum('0','1') COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT '授权用户信息',
  `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
  `logintime` bigint(16) unsigned DEFAULT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`,`platform`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='第三方登录表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `group_type` enum('1','2','3') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '身份:1=单店,2=总店,3=分店',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '归属账号',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='会员身份表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_account
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `first_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级',
  `second_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级',
  `bindtime` bigint(16) DEFAULT NULL COMMENT '绑定时间',
  `total_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总积分',
  `points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `total_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '累计佣金',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `withdraw_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '累计提现金额',
  `platform_message` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动消息',
  `system_message` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '系统消息',
  `user_message` int(10) unsigned DEFAULT '0' COMMENT '个人消息',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户账户表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_brand
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请人账号',
  `brand_name` varchar(50) NOT NULL DEFAULT '' COMMENT '品牌名',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌LOGO',
  `concat_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `account_mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '品牌账号',
  `status` enum('checked','passed','failed') NOT NULL DEFAULT 'checked' COMMENT '状态:checked=待审核,passed=通过,failed=驳回',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `weigh` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `deletetime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='品牌申请';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_car
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_car` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `car_no` varchar(20) NOT NULL DEFAULT '' COMMENT '车牌',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌',
  `series_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '车系',
  `models_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '具体车型',
  `register_time` bigint(16) DEFAULT NULL COMMENT '注册日期',
  `car_vin` varchar(20) NOT NULL DEFAULT '' COMMENT '车架号',
  `engine_number` varchar(20) NOT NULL DEFAULT '' COMMENT '发动机号',
  `car_belongs_to` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '归属:1=个人,2=公司',
  `use_nature` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '使用性质:1=非营运,2=营运',
  `is_default` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '默认:0=否,1=是',
  `createtime` bigint(16) DEFAULT NULL COMMENT '添加时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='用户车辆';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_coupon
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_coupon` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `coupon_id` int(10) NOT NULL DEFAULT '0' COMMENT '优惠券',
  `use_order_id` int(10) NOT NULL DEFAULT '0' COMMENT '使用订单',
  `shop_id` int(10) DEFAULT NULL,
  `use_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '使用状态:0=未使用,1=已使用',
  `use_time` bigint(16) DEFAULT NULL COMMENT '使用时间',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `coupon_id` (`coupon_id`) USING BTREE,
  KEY `use_time` (`use_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='用户优惠券';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_message
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型: 多样，见php class',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '读取状态:0=未读,1=已读',
  `read_time` bigint(16) DEFAULT NULL COMMENT '读取时间',
  `extra` varchar(500) NOT NULL DEFAULT '',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COMMENT='消息';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_notice
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `notice_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公告',
  `num` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_notice_id` (`user_id`,`notice_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='会员查看公告';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_package
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌',
  `package_id` int(10) NOT NULL COMMENT '套餐ID',
  `package_name` varchar(50) NOT NULL DEFAULT '' COMMENT '套餐名',
  `package_image` varchar(255) NOT NULL DEFAULT '' COMMENT '套餐图片',
  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单ID',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `pay_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '购买价格',
  `status` enum('ing','finished','apply_refund','refund') NOT NULL DEFAULT 'ing' COMMENT '套餐状态:ing=进行中,finished=已完成,apply_refund=退款申请中,refund=已退款',
  `qrcode` varchar(255) NOT NULL DEFAULT '' COMMENT '核销二维码',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '核销code',
  `token` varchar(255) NOT NULL DEFAULT '' COMMENT '核销token',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`) USING BTREE,
  KEY `user_shop_id` (`user_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='会员套餐';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_package_service
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_package_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_package_id` varchar(50) NOT NULL DEFAULT '' COMMENT '用户套餐ID',
  `package_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联套餐ID',
  `package_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联套餐服务ID',
  `service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务ID',
  `shop_service_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店服务ID',
  `service_price_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '服务价格ID',
  `service_image` varchar(255) NOT NULL DEFAULT '' COMMENT '服务图标',
  `service_name` varchar(50) NOT NULL COMMENT '服务名称',
  `service_price_name` varchar(50) NOT NULL DEFAULT '' COMMENT '服务规格',
  `salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '原价格',
  `vip_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员价格',
  `total_count` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '总次数',
  `stock` smallint(6) NOT NULL COMMENT '次数',
  `use_count` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '已使用次数',
  `status` enum('ing','finished') NOT NULL DEFAULT 'ing' COMMENT '状态:ing=使用中,finished=已完成',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_package_id` (`user_package_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_shop_account
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_shop_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `total_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '累计充值',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_shop_id` (`user_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='用户门店账户表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_user_shop_vip
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_user_shop_vip` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `vip_no` varchar(20) NOT NULL DEFAULT '' COMMENT '会员卡号',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `shop_vip_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店会员卡',
  `expire_in` bigint(16) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_shop_id` (`user_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='用户门店会员表';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_vip_order
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_vip_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单用户',
  `platform` varchar(10) NOT NULL DEFAULT '1' COMMENT '下单平台:1=wxmini,2=app',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `order_trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '交易单号',
  `pay_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '支付类型:1=微信,2=支付宝,3=后台人工',
  `pay_fee` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '支付金额',
  `pay_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid' COMMENT '支付状态:unpaid=待支付,paid=已支付',
  `paytime` bigint(16) DEFAULT NULL COMMENT '支付时间',
  `trade_no` varchar(32) NOT NULL DEFAULT '' COMMENT '三方单号',
  `vip_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员卡',
  `vip_salesprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '售价',
  `vip_name` varchar(50) NOT NULL DEFAULT '' COMMENT '会员卡名',
  `vip_days` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
  `vip_privilege` text NOT NULL COMMENT '会员卡权益',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '下单id',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `admin_id` int(10) NOT NULL COMMENT '管理员',
  `shop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店',
  `brand_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌ID',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='会员订单';

-- ----------------------------
-- Table structure for __PREFIX__xiluxc_withdraw
-- ----------------------------
CREATE TABLE IF NOT EXISTS `__PREFIX__xiluxc_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现用户',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `real_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际到账',
  `rate` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费率',
  `rate_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:1=审核中,2=处理中,3=已处理,4=已拒绝',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '拒绝理由',
  `certificate` varchar(255) NOT NULL DEFAULT '' COMMENT '打款凭证',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '提现订单号',
  `checktime` bigint(16) DEFAULT NULL COMMENT '审核时间',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='提现金额';

