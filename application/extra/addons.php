<?php

return [
    'autoload' => false,
    'hooks' => [
        'upgrade' => [
            'leescore',
        ],
        'app_init' => [
            'leescore',
            'xiluxc',
        ],
        'leescorehook' => [
            'leescore',
        ],
        'user_sidenav_after' => [
            'leescore',
            'recharge',
            'xiluxc',
        ],
        'sms_send' => [
            'smsbao',
        ],
        'sms_notice' => [
            'smsbao',
        ],
        'sms_check' => [
            'smsbao',
        ],
        'user_delete_successed' => [
            'third',
        ],
        'user_logout_successed' => [
            'third',
        ],
        'module_init' => [
            'third',
        ],
        'action_begin' => [
            'third',
        ],
        'config_init' => [
            'third',
            'ueditor',
        ],
        'view_filter' => [
            'third',
        ],
        'xiluxc_shop_user' => [
            'xiluxc',
        ],
        'xiluxc_vip_calculate' => [
            'xiluxc',
        ],
        'xiluxc_service_calculate' => [
            'xiluxc',
        ],
        'xiluxc_add_score' => [
            'xiluxc',
        ],
        'xiluxc_reduce_score' => [
            'xiluxc',
        ],
        'xiluxc_recharge_success' => [
            'xiluxc',
        ],
        'xiluxc_money_pay' => [
            'xiluxc',
        ],
        'xiluxc_refund_success' => [
            'xiluxc',
        ],
        'xiluxc_withdraw' => [
            'xiluxc',
        ],
        'xiluxc_withdraw_refuse' => [
            'xiluxc',
        ],
        'xiluxc_shop_withdraw' => [
            'xiluxc',
        ],
        'xiluxc_shop_withdraw_refuse' => [
            'xiluxc',
        ],
        'xiluxc_service_buy_message' => [
            'xiluxc',
        ],
        'xiluxc_package_buy_message' => [
            'xiluxc',
        ],
        'xiluxc_service_verifier_message' => [
            'xiluxc',
        ],
        'xiluxc_package_verifier_message' => [
            'xiluxc',
        ],
    ],
    'route' => [
        '/leescore/goods$' => 'leescore/goods/index',
        '/leescore/order$' => 'leescore/order/index',
        '/score$' => 'leescore/index/index',
        '/address$' => 'leescore/address/index',
        '/third$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
    ],
    'priority' => [],
    'domain' => '',
];
