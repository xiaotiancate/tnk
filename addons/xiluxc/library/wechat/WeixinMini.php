<?php

namespace addons\xiluxc\library\wechat;

use addons\xiluxc\library\Config;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Http\StreamResponse;
use fast\Http;
use think\Exception;
use think\exception\ErrorException;
use think\exception\ThrowableError;

class WeixinMini{

    protected $app;

    public function __construct(){
        $this->app = Factory::miniProgram(Config::load());
    }

    /**
     * 登录
     * @param $code
     * @return mixed
     * @throws Exception
     * @throws ThrowableError
     */
    public function wxlogin($code)
    {
        try {
            $result = $this->app->auth->session($code);
            if(isset($result['errcode'])) Throw new Exception($result['errmsg']);
        }catch (ThrowableError $e){
            throw $e;
        }catch (Exception $e){
            throw $e;
        }
        return $result;
    }

    /**
     * 手机号解密
     * @param $session_key
     * @param $iv
     * @param $encryptedData
     * @return mixed
     * @throws Exception
     * @throws ThrowableError
     */
    public function wxNumberEncrypted($session_key,$iv,$encryptedData){
        try {
            $result = $this->app->encryptor->decryptData($session_key, $iv, $encryptedData);

        }catch (ThrowableError $e){
            throw $e;
        }catch (Exception $e){
            throw $e;
        }
        return $result;
    }

    /**
     * 获取小程序码-A
     */
    public function getlimited($page)
    {

        $response = $this->app->app_code->get($page);
        $resfilename = '';
        if ($response instanceof StreamResponse) {
            $resfilename = $response->getBodyContents();
        }
        return $resfilename;
    }
    
    /**
     * 获取小程序码-B
     */
    public function getUnlimited($scene,$page)
    {
        $response = $this->app->app_code->getUnlimit($scene,[
            'page'=>$page,
            'env_version'=> 'trial',
            'check_path'=>false
        ]);
        if ($response instanceof StreamResponse) {
            $resfilename = $response->getBodyContents();
        }else{
            return '';
        }
        return $resfilename;
    }
    /**
     * 获取access_token
     */
    private function getToken(){
        $token = $this->app->access_token->getToken();
        return $token['access_token'];
    }
    /**
     * 获取手机号
     * @param $code
     */
    public function getPhoneNumber($code){
        $token = $this->getToken();

        try {
            $data = Http::post("https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=".$token,json_encode(['code'=>$code]));
            $data = json_decode($data,true);
            if($data['errcode'] != '0'){
                throw new Exception("获取手机号失败");
            }
        }catch (ErrorException $exception){

            throw $exception;
        }
        return $data['phone_info'];
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
            $payment = (new Payment('wxmini'))->getPayment();
            $wxpay = $payment->order->unify($orderinfo);
            if($wxpay['return_code'] == "FAIL" || $wxpay['result_code'] == "FAIL"){
                throw new Exception($wxpay['return_code'] == "FAIL"?$wxpay['return_msg']:$wxpay['err_code_des']);
            }
            return $payment->jssdk->bridgeConfig($wxpay['prepay_id'], false);
        }catch (Exception $e){
            return [];
        }
    }

}