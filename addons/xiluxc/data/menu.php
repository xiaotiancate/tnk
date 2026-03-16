<?php

$menu = array (
    0 =>
        array(
            'name' => 'xiluxc',
            'title' => '西陆洗车',
            'icon' => 'fa fa-folder',
            'ismenu' => 1,
            'sublist' =>
                array (
                    0 =>
                        array (
                            'name' => 'xiluxc/dashboard',
                            'title' => '控制台',
                            'icon'  => 'fa fa-dashboard',
                            'ismenu' => 1,
                            'weigh'	  => '6000',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/dashboard/index',
                                            'title' => '查看',
                                        ),
                                ),
                        ),
                    1 =>
                        array (
                            'name' => 'xiluxc/current',
                            'title' => '通用配置',
                            'icon' => 'fa fa-list',
                            'ismenu' => 1,
                            'weigh'	  => '5500',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/current/area',
                                            'title' => '地区管理',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/current/area/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/current/config',
                                            'title' => '基础配置',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/current/config/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/current/config/config',
                                                            'title' => '配置',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/current/tag',
                                            'title' => '标签管理',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/current/tag/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/current/tag/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/current/tag/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/current/tag/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/current/tag/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'name' => 'xiluxc/current/property',
                                            'title' => '属性管理',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/current/property/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/current/property/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/current/property/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/current/property/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/current/property/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    4 =>
                                        array (
                                            'name' => 'xiluxc/current/singlepage',
                                            'title' => '单页文章',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/current/singlepage/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    2 =>
                        array (
                            'name' => 'xiluxc/brand',
                            'title' => '会员管理',
                            'icon' => 'fa fa-list',
                            'ismenu' => 1,
                            'weigh'	  => '5000',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/brand/user',
                                            'title' => '会员管理',
                                            'icon' => 'fa fa-users',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/package',
                                                            'title' => '套餐',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user/uservip',
                                                            'title' => '会员卡',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/brand/user_brand',
                                            'title' => '品牌申请',
                                            'icon' => 'fa fa-tree',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/user_brand/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/brand/brand_user',
                                            'title' => '品牌管理',
                                            'icon' => 'fa fa-newspaper-o',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/brand_user/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/brand_user/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/brand_user/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/brand_user/del',
                                                            'title' => '删除',
                                                        ),
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'name' => 'xiluxc/brand/shop',
                                            'title' => '门店管理',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop/audit',
                                                            'title' => '审核',
                                                        ),
                                                ),
                                        ),
                                    4 =>
                                        array (
                                            'name' => 'xiluxc/brand/shop_service',
                                            'title' => '门店服务',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_service/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_service/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_service/del',
                                                            'title' => '删除',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_service/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    5 =>
                                        array (
                                            'name' => 'xiluxc/brand/package',
                                            'title' => '门店套餐',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/package/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/package/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/package/del',
                                                            'title' => '删除',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/package/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    6 =>
                                        array (
                                            'name' => 'xiluxc/brand/recharge',
                                            'title' => '充值套餐',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/recharge/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/recharge/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/recharge/del',
                                                            'title' => '删除',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/recharge/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    7 =>
                                        array (
                                            'name' => 'xiluxc/brand/vip',
                                            'title' => '门店会员卡',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/vip/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/vip/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/vip/del',
                                                            'title' => '删除',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/vip/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    8 =>
                                        array (
                                            'name' => 'xiluxc/brand/shop_verifier',
                                            'title' => '门店核销员',
                                            'icon' => 'fa fa-user',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_verifier/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_verifier/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_verifier/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_verifier/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/brand/shop_verifier/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    3 =>
                        array (
                            'name' => 'xiluxc/service',
                            'title' => '服务管理',
                            'icon' => 'fa fa-list',
                            'weigh'	  => '4500',
                            'ismenu' => 1,
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/service/service',
                                            'title' => '服务管理',
                                            'icon'  =>  'fa fa-amazon',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/service/service/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/service/service/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/service/service/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/service/service/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/service/service/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    4 =>
                        array (
                            'name' => 'xiluxc/news',
                            'title' => '知识库',
                            'icon' => 'fa fa-list',
                            'weigh'	  => '4000',
                            'ismenu' => 1,
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/news/news',
                                            'title' => '知识库管理',
                                            'icon'  =>  'fa fa-amazon',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/news/news_category',
                                            'title' => '知识库分类',
                                            'icon'  =>  'fa fa-cogs',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news_category/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news_category/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news_category/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news_category/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/news/news_category/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    5 =>
                        array (
                            'name' => 'xiluxc/activity',
                            'title' => '活动中心',
                            'icon' => 'fa fa-list',
                            'weigh'	  => '3500',
                            'ismenu' => 1,
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/activity/banner',
                                            'title' => '图片banner',
                                            'icon'  =>  'fa fa-amazon',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/banner/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/activity/navigation',
                                            'title' => '金刚区',
                                            'icon'  =>  'fa fa-cogs',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/navigation/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/navigation/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/navigation/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/navigation/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/navigation/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/activity/coupon',
                                            'title' => '优惠券管理',
                                            'icon' => 'fa fa-list',
                                            'ismenu' => 1,
                                            'weigh'	  => '4000',
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/add',
                                                            'title' => '添加',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/activity/coupon/coupon_log',
                                                            'title' => '领取记录',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    6 =>
                        array (
                            'name' => 'xiluxc/order',
                            'title' => '订单管理',
                            'icon' => 'fa fa-list',
                            'ismenu' => 1,
                            'weigh'	  => '3000',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/order/recharge_order',
                                            'title' => '充值订单',
                                            'icon' => 'fa fa-first-order',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/order/recharge_order/index',
                                                            'title' => '查看',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/order/recharge_order/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/order/recharge_order/del',
                                                            'title' => '删除',
                                                        )
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/order/vip_order',
                                            'title' => '会员卡订单',
                                            'icon' => 'fa fa-first-order',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/order/vip_order/index',
                                                            'title' => '查看',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/order/vip_order/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/order/vip_order/del',
                                                            'title' => '删除',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/order/order',
                                            'title' => '订单管理',
                                            'icon' => 'fa fa-first-order',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/order/order/restore',
                                                            'title' => '还原',
                                                        ),
                                                ),
                                        ),
                                    3 =>
                                        array (
                                            'name' => 'xiluxc/order/aftersale',
                                            'title' => '售后管理',
                                            'icon' => 'fa fa-first-order',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/order/aftersale/restore',
                                                            'title' => '还原',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    7 =>
                        array (
                            'name' => 'xiluxc/finance',
                            'title' => '财务管理',
                            'icon' => 'fa fa-list',
                            'ismenu' => 1,
                            'weigh'	  => '2500',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/finance/shop_withdraw',
                                            'title' => '门店提现',
                                            'icon' => 'fa fa-money',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/shop_withdraw/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/shop_withdraw/processing',
                                                            'title' => '处理中',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/shop_withdraw/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/shop_withdraw/refuse',
                                                            'title' => '拒绝',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/finance/withdraw',
                                            'title' => '会员提现',
                                            'icon' => 'fa fa-cny',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/withdraw/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/withdraw/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/withdraw/del',
                                                            'title' => '删除',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/withdraw/processing',
                                                            'title' => '处理中',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/withdraw/refuse',
                                                            'title' => '拒绝',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/finance/money_log',
                                            'title' => '会员佣金',
                                            'icon' => 'fa fa-money',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/finance/money_log/index',
                                                            'title' => '查看',
                                                        )
                                                ),
                                        ),
                                ),
                        ),
                    8 =>
                        array (
                            'name' => 'xiluxc/comment',
                            'title' => '评论管理',
                            'icon' => 'fa fa-list',
                            'weigh'	  => '2000',
                            'ismenu' => 1,
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/comment/service_comment',
                                            'title' => '服务评价',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/restore',
                                                            'title' => '还原',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/comment/service_comment/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    9 =>
                        array (
                            'name' => 'xiluxc/message',
                            'title' => '消息与反馈',
                            'icon' => 'fa fa-list',
                            'weigh'	  => '1500',
                            'ismenu' => 1,
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/message/notice',
                                            'title' => '平台公告',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/message/notice/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/message/notice/add',
                                                            'title' => '编辑',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/message/notice/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/message/notice/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/message/notice/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/message/advice',
                                            'title' => '投诉建议',
                                            'icon' => 'fa fa-list',
                                            'ismenu' => 1,
                                            'weigh'	  => '2000',
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/del',
                                                            'title' => '删除',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/restore',
                                                            'title' => '还原',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/message/advice/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                    10 =>
                        array (
                            'name' => 'xiluxc/car',
                            'title' => '汽车参数',
                            'icon' => 'fa fa-list',
                            'ismenu' => 1,
                            'weigh'	  => '1000',
                            'sublist' =>
                                array (
                                    0 =>
                                        array (
                                            'name' => 'xiluxc/car/car_brand',
                                            'title' => '汽车品牌',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_brand/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    1 =>
                                        array (
                                            'name' => 'xiluxc/car/car_series',
                                            'title' => '汽车车系',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_series/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                    2 =>
                                        array (
                                            'name' => 'xiluxc/car/car_models',
                                            'title' => '汽车型号',
                                            'ismenu' => 1,
                                            'sublist' =>
                                                array (
                                                    0 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/index',
                                                            'title' => '查看',
                                                        ),
                                                    1 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/recyclebin',
                                                            'title' => '回收站',
                                                        ),
                                                    2 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/add',
                                                            'title' => '添加',
                                                        ),
                                                    3 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/edit',
                                                            'title' => '编辑',
                                                        ),
                                                    4 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/del',
                                                            'title' => '删除',
                                                        ),
                                                    5 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/destroy',
                                                            'title' => '真实删除',
                                                        ),
                                                    6 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/restore',
                                                            'title' => '还原',
                                                        ),
                                                    7 =>
                                                        array (
                                                            'name' => 'xiluxc/car/car_models/multi',
                                                            'title' => '批量更新',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),

                ),
        )

);
return $menu;
