<?php

namespace addons\xiluxc\library\wechat;

use EasyWeChat\Factory;
use think\Exception;


/**
 * 微信接口
 */
class WeixinPublic
{
    public $app = null;
    public $payment = null;

    public function __construct()
    {
        $wxpublic_config = \app\common\model\xiluxc\current\Config::getMyConfig('wxpublic');
        $config = [
            /**
             * 账号基本信息，请从小程序获取
             */
            'app_id'        => $wxpublic_config['wxpublic_appid'], // AppID
            'secret'        => $wxpublic_config['wxpublic_secret'], // AppSecret

            'response_type' => 'array',
            /**
             * 日志配置
             * level: 日志级别, 可选为：debug/info/notice/warning/error/critical/alert/emergency
             * path：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log'           => [
                'default'  => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev'  => [
                        'driver' => 'single',
                        'path'   => ROOT_PATH . 'runtime/log/easywechat.log',
                        'level'  => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path'   => ROOT_PATH . 'runtime/log/easywechat.log',
                        'level'  => 'debug',
                    ],
                ],
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_base'],
                'callback' => $wxpublic_config['wxpublic_site'],
            ],
        ];
    
        $this->app = Factory::officialAccount($config);
    }

    //生成jsapi 配置
    public function jsapi($APIs, $url = '')
    {
//        if(get_platform()!='weixin'){
//            return [];
//        }
        $js = $this->app->jssdk;

        if ($url) {
            $js->setUrl($url);
        }
        return $js->buildConfig($APIs, $debug = false, $beta = false, $json = true);
    }

    //授权登录
    public function auth()
    {
        try {
            $response = $this->app->oauth->redirect();
            $response->send();
        } catch (\Exception $e) {
            dump("授权失败." . $e->getMessage());
        }
    }

    // 授权登录后的回调页面
    public function auth_back()
    {
        $code = request()->param('code');
        if (empty($code)) {
            exception('code 参数缺少');
        }
        $oauth = $this->app->oauth;
        // 获取 OAuth 授权结果用户信息
        $authUser = $oauth->user()->toArray();
        $wechat_userinfo = $authUser['original'];


//        $third_model = new Third();
//        $third = $third_model->where('openid',$wechat_userinfo['openid'])->where('platform','wxpublic')->find();
//        $param = [
//            'platform'  =>  'wxpublic',
//            'openid'    =>  $wechat_userinfo['openid'],
//            'openname'  =>  $wechat_userinfo['nickname'],
//            'avatar'    =>  $wechat_userinfo['headimgurl'],
//            'access_token'=>$authUser['access_token'],
//            'auth_userinfo'=> '1'
//        ];
//        if(!$third || !$third->user_id || !User::where('id',$third->user_id)->value('id')){
//            Cookie::set('token', '');
//            if(!$third){
//                $third_model->save($param);
//                $third = $third_model;
//            }
//
//           //创建授权信息的同时创建账号信息
//            $auth = (Auth::instance());
//            $ret = $auth->register($wechat_userinfo['nickname'], Random::alnum(), '', '', ['avatar'=>$wechat_userinfo['headimgurl'],'nickname'=>$wechat_userinfo['nickname']]);
//
//            Cookie::set('uid', $auth->id);
//            Cookie::set('token', $auth->getToken(),30 * 86400);
//            $third->user_id = $auth->id;
//            $third->save();
//        }else{
//            //if(!$token = Cookie::get('token')){
//                $user = User::get($third->user_id);
//                $auth = (Auth::instance());
//                $ret = $auth->direct($user->id);
//                Cookie::set('uid', $auth->id);
//                Cookie::set('token', $auth->getToken(),30 * 86400);
//            //}
//        }

        $target_url = !session('target_url') ? url('index/index/index') : session('target_url');
        $target = explode('?',$target_url);
        if(count($target) > 1){
            $domain = $target[0];
//            $query = $target[1].'&token='.Cookie::get('token');
            $query = $target[1].'&openid='.$wechat_userinfo['openid'];
            $target_url = $domain.'?'.$query;
        }else{
            $target_url .= '?openid='.$wechat_userinfo['openid'];
        }
        header('location:'.$target_url);
        //redirect($target_url);
    }

    /**
     * 生成统一订单
     */
    public function union_order($order){

        $orderinfo = [
            'body'              => $order['body'],
            'out_trade_no'      => $order['order_no'],
            'total_fee'         => (int)($order['pay_price'] * 100),
            //'spbill_create_ip'  => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url'        => $order['notify_url'], // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type'        => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid'            => $order['openid'],
        ];
        try {
            $payment = (new Payment('wxoffical'))->getPayment();
            $wxpay = $payment->order->unify($orderinfo);
            if($wxpay['return_code'] == "FAIL" || $wxpay['result_code'] == "FAIL"){
                throw new Exception($wxpay['return_code'] == "FAIL"?$wxpay['return_msg']:$wxpay['err_code_des']);
            }
            return $payment->jssdk->bridgeConfig($wxpay['prepay_id'], false);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}
