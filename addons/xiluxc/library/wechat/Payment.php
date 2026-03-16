<?php


namespace addons\xiluxc\library\wechat;


use app\common\model\xiluxc\order\Aftersale;
use EasyWeChat\Factory;
use app\common\model\xiluxc\current\Config  AS ConfigModel;
use think\Exception;

class Payment
{
    protected $platform;
    protected $payment;

    public function __construct($platform=null){
        $this->platform = $platform;
        $this->payment = $this->paymentInit();
    }

    /**
     * 支付初始化
     * @throws Exception
     * @throws \think\exception\DbException
     */
    private function paymentInit(){
        if($this->platform == 'wxmini'){
            $appConfig = ConfigModel::getMyConfig('wxmini');
            $paymentConfig['app_id'] = $appConfig['wxmini_appid'];
        }else if($this->platform == 'wxoffical'){
            $appConfig = \app\common\model\xiluxc\current\Config::getMyConfig('wxpublic');
            $paymentConfig['app_id'] = $appConfig['wxpublic_appid'];
        }else{
            throw new Exception("支付参数错误");
        }
        $pay_config = ConfigModel::getMyConfig('wxpayment');
        $paymentConfig = array_merge([
            // 必要配置
            'mch_id'             => $pay_config['mch_id'] ?? '',
            'key'                => $pay_config['mch_key'] ?? '',   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => ROOT_PATH.$pay_config['apiclient_cert'] ?? '', // XXX: 绝对路径！！！！
            'key_path'           => ROOT_PATH.$pay_config['apiclient_key'] ?? '',      // XXX: 绝对路径！！！！
            'notify_url'         => '',     // 你也可以在下单时单独设置来想覆盖它
        ],$paymentConfig);
        return Factory::payment($paymentConfig);
    }

    /**
     * 对外的Payment
     */
    public function getPayment(){
        return $this->payment;
    }
    /**
     * @param $order_id
     * @param $to_back_fee
     * @param $reason
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function refund($order_id,$to_back_fee,$reason=''){
        $payment = $this->payment;
        $aftersale = Aftersale::where('id',$order_id)->find();
        $order = $aftersale->ordering;
        $wx_trade_no = $order['order_trade_no'];
        $wx_pay = config('app_debug')? 0.01 : $order->pay_fee;
        $to_back_fee = config('app_debug')? 0.01 : $to_back_fee;
        $refund_no = $aftersale->order_no;
        if (!$order) {
            return ['status'=>false,'msg'=>'订单不存在'];
        }
        if ($to_back_fee*100 > 100 * $wx_pay) {
            return ['status'=>false,'msg'=>'退款金额大于支付金额'];
        }
        try {
            $result = $payment->refund->byOutTradeNumber($wx_trade_no, $refund_no, $wx_pay * 100, $to_back_fee*100, [
                // 可在此处传入其他参数，详细参数见微信支付文档
                'refund_desc' => $reason,
            ]);
            if ('SUCCESS' != $result['return_code'] || "SUCCESS" != $result['result_code']) {
                $err_code_des = isset($result['err_code_des'])?'错误描述：'.$result['err_code_des']:'';
                $return_msg = strtolower($result['return_msg']) == 'ok'?$err_code_des:$result['return_msg'];

                return ['status' => false, 'msg' => "订单号：{$order['trade_no']} 因{$reason} 申请的微信退款失败, 原因：{$return_msg}"];
            } else {
                return ['status'=>true,'msg'=>'退款成功'];
            }
        } catch (Exception $e) {
            return ['status'=>false,'msg'=>"订单号：{$order['trade_no']} 因{$reason} 申请的微信退款失败, 原因：{$e->getMessage()}"];
        }

    }


    /**
     * 申请提现订单  申请提现的记录表结果 id|uid|money|status|create_time|check_time|reason|order_no|reason
     * @param $withdraw array 提现记录数据
     */
    public function withdraw($withdraw, $openid){
        $payment = $this->getPayment();
        $order_no        = $withdraw['order_no'];
        $merchantPayData = [
            'partner_trade_no' => $order_no, //随机字符串作为订单号，跟红包和支付一个概念。
            'openid'           => $openid, //收款人的openid
            'check_name'       => 'NO_CHECK', //文档中三分钟校验实名的方法NO_CHECK OPTION_CHECK FORCE_CHECK
            // 're_user_name'     => '张三', //OPTION_CHECK FORCE_CHECK 校验实名的时候必须提交
            'amount'           => $withdraw['money'] * 100, //单位为分 ，最少不能小于1元
            'desc'             => '用户提现',
            'spbill_create_ip' => request()->ip(), //发起交易的IP地址
        ];
        try {
            $result = $payment->transfer->toBalance($merchantPayData);
            if ('SUCCESS' == $result['return_code'] && 'SUCCESS' == $result['result_code']) {
                return ['status' => true, 'msg'   => '提现成功'];
            } else {
                return ['status' => false, 'msg'   => "{$result['err_code']}：{$result['err_code_des']}"];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'msg'   => $e->getMessage(),];
        }
    }

}