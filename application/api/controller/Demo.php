<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 示例接口
 */
class Demo extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['test', 'test1','denol','notify','bang','bindCard','transferOrder'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['test2'];

    /**
     * 测试方法
     *
     * @ApiTitle    (测试名称)
     * @ApiSummary  (测试描述信息)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/demo/test/id/{id}/name/{name})
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiParams   (name="name", type="string", required=true, description="用户名")
     * @ApiParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
        })
     */
    public function test()
    {
        $this->success('返回成功', $this->request->param());
    }

    /**
     * 无需登录的接口
     *
     */
    public function test1()
    {
        $this->success('返回成功', ['action' => 'test1']);
    }

    /**
     * 需要登录的接口
     *
     */
    public function test2()
    {
        $this->success('返回成功', ['action' => 'test2']);
    }

    /**
     * 需要登录且需要验证有相应组的权限
     *
     */
    public function test3()
    {
        $this->success('返回成功', ['action' => 'test3']);
    }
      /**
     * 绑卡
     *
     */
    public function bang(){
        $date = date('YmdHis');      // 年月日时分秒
        $rand = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5位随机数
        $ordersn= $date . $rand;
        $privateKey='pKwVOACC9QbmvUpYgTZfbTWQvZb1AMxqM1IGKpeRpKJfkl1hqYyKJcCbXJIVZn3IW6vuutF1gcL5sJLrwgDbkbADtAIYs9BKQCDK9zlRDF0QTOnOJC0t9GZaZpGWgUHs';
        // $notify_url ='https://pay.prod.6jqb.com/api/transfer/account';
        $data['mchNo']='M1754302502';
        $data['appId']='68908a3ee4b010532b9ef99e';
        $data['requestNo']=$ordersn;
        $data['ifCode']='dgpay';
        $data['accountType']=1;
        $data['name']='黄述飞';
        $data['certNo']='612401199801018038';//身份证号
        $data['certValidityType']=1;
        $data['certBeginDate']='20210101';
        $data['mobileNo']=15362034941;
        $data['cardName']='黄述飞';
        $data['cardNo']='6214391880051285370';
        $data['settType']=1;
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        $data['sign']=$this->generateSign($data,$privateKey);
        $url='https://pay.prod.6jqb.com/api/transfer/account';
        $sss=$this->sendPostRequest($url,$data);
        // 将JSON字符串转换为关联数组
        $array = json_decode($jsonString, true);
            
        // 打印数组查看结果
        if($array['code']==0){
            
            $member_account=$array['data'];
            $member_account_array['channelMchNo']=$member_account['channelMchNo'];
            $member_account_array['respCode']=$member_account['respCode'];
            $member_account_array['subRespCode']=$member_account['subRespCode'];
            $member_account_array['name']=$data['name'];
            $member_account_array['mobileNo']=$data['mobileNo'];
            $member_account_array['cardNo']=$data['cardNo'];
            $member_account_array['certNo']=$data['certNo'];
            $member_account_array['uid']=$uid;
            $member_account_array['creatime']=time();
            $res=Db::name('fa_bang')->insert($member_account_array);
            if($res){
                $this->success('返回成功');
            }else{
                $this->error('返回失败');
            }
        }
    }
//     array(7) {
//   ["apiResId"] => string(19) "1953391676738818049"
//   ["code"] => int(0)
//   ["data"] => array(7) {
//     ["applyNo"] => string(0) ""
//     ["authSn"] => string(49) "[{"code":"S","type":"1"},{"code":"S","type":"2"}]"
//     ["respCode"] => string(6) "000000"
//     ["respDesc"] => string(7) "success"
//     ["subRespCode"] => string(8) "00000000"
//     ["subRespDesc"] => string(6) "成功"
//     ["tokenNo"] => string(11) "10056406981"
//   }
//   ["fail"] => bool(false)
//   ["msg"] => string(7) "SUCCESS"
//   ["ok"] => bool(true)
//   ["sign"] => string(32) "A2E3817254ECE4CE606B554EB32EAA5E"
// }
    
    public function bindCard(){
        $date = date('YmdHis');      // 年月日时分秒
        $rand = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5位随机数
        $ordersn= $date . $rand;
        $privateKey='pKwVOACC9QbmvUpYgTZfbTWQvZb1AMxqM1IGKpeRpKJfkl1hqYyKJcCbXJIVZn3IW6vuutF1gcL5sJLrwgDbkbADtAIYs9BKQCDK9zlRDF0QTOnOJC0t9GZaZpGWgUHs';
        // $notify_url ='https://pay.prod.6jqb.com/api/transfer/account';
        $data['mchNo']='M1754302502';
        $data['appId']='68908a3ee4b010532b9ef99e';
        $data['channelMchNo']='6666000174702687';
        $data['ifCode']='dgpay';
        $data['requestNo']=$ordersn;
           $data['mobileNo']=15362034941;
        // $data['mobileNo']=1;
        // $data['cardName']='黄述飞';
        $data['certNo']='612401199801018038';//身份证号
        $data['certValidityType']=1;
        $data['certBeginDate']='20210101';
        $data['cashType']='T1';
        $data['cardName']='黄述飞';
        $data['cardNo']='6214391880051285370';
        $data['settType']=1;
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        $data['provId']='440000';
        $data['areaId']='441900';
        $data['fixAmt']=1;
        $data['sign']=$this->generateSign($data,$privateKey);
        $url='https://pay.prod.6jqb.com/api/account/bindCard';
        $sss=$this->sendPostRequest($url,$data);
        // 将JSON字符串转换为关联数组
        $array = json_decode($sss, true);
        
        dump($array);die;
    }
    
    public function transferOrder(){
                $date = date('YmdHis');      // 年月日时分秒
        $rand = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5位随机数
        $ordersn= $date . $rand;
        $privateKey='pKwVOACC9QbmvUpYgTZfbTWQvZb1AMxqM1IGKpeRpKJfkl1hqYyKJcCbXJIVZn3IW6vuutF1gcL5sJLrwgDbkbADtAIYs9BKQCDK9zlRDF0QTOnOJC0t9GZaZpGWgUHs';
        // $notify_url ='https://pay.prod.6jqb.com/api/transfer/account';
        $data['mchNo']='M1754302502';
        $data['appId']='68908a3ee4b010532b9ef99e';
        // $data['channelMchNo']='6666000174702687';
        $data['ifCode']='dgpay';
        $data['mchOrderNo']=$ordersn;
        $data['entryType']='BANK_CARD';
        $data['amount']=100;
        $data['currency']='cny';
        $data['accountType']=1;
        // $ba=array(
        //     'channelMchNo'=>'6666000174702687',
        //     'tokenNo'=>'03134404'
        //     );
           $ba=  json_encode([
        "cashType" => "T1",
        "receiverChannelMchId" => "6666000174702687",
        "tokenNo" => "03134404",
        "settType" => "1"
    ]);
            // var_dump($ba);die;
        $data['extParam']=$ba;
        // var_dump($data);die;
        // $data['mobileNo']=1;
        // $data['cardName']='黄述飞';
        // $data['certNo']='612401199801018038';//身份证号
        // $data['certValidityType']=1;
        $data['transferDesc']='测试转账';
     
        $data['accountName']='黄述飞';
        $data['accountNo']='6214391880051285370';
        
        // $data['settType']=1;
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        // $data['provId']='440000';
        // $data['areaId']='441900';
        // $data['fixAmt']=1;
        $data['sign']=$this->generateSign($data,$privateKey);
        $url='https://pay.prod.6jqb.com/api/transferOrder';
        $sss=$this->sendPostRequest($url,$data);
        // 将JSON字符串转换为关联数组
        $array = json_decode($sss, true);
        dump($array);die;
    }
    
    
    /**
     * 提现
     * string(276) "{"apiResId":"1953291315147493377","code":0,"data":{"applyNo":"","channelMchNo":"6666000174702687","respBusiness":"","respCode":"00000000","respDesc":"成功","subRespCode":"COM003","tokenNo":""},"fail":false,"msg":"SUCCESS","ok":true,"sign":"6FD2EB2051F43AE12D614AEE43DCEA7E"}"
     *
     */
     
     public function tixian(){
        $date = date('YmdHis');      // 年月日时分秒
        $rand = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5位随机数
        $ordersn= $date . $rand;
        $privateKey='pKwVOACC9QbmvUpYgTZfbTWQvZb1AMxqM1IGKpeRpKJfkl1hqYyKJcCbXJIVZn3IW6vuutF1gcL5sJLrwgDbkbADtAIYs9BKQCDK9zlRDF0QTOnOJC0t9GZaZpGWgUHs';
        // $notify_url ='https://pay.prod.6jqb.com/api/transfer/account';
        $data['mchNo']='M1754302502';
        $data['appId']='68908a3ee4b010532b9ef99e';
        $data['requestNo']=$ordersn;
        $data['ifCode']='ysqbpay';
        $data['accountType']=1;
        $data['name']='黄述飞';
        $data['certNo']='';//身份证号
        $data['mobileNo']=15362034941;
        $data['cardName']='黄述飞';
        $data['cardNo']='6214391880051285370';
        $data['settType']=1;
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        $data['sign']=$this->generateSign($data,$privateKey);
        $url='https://pay.prod.6jqb.com/api/transfer/account';
        $sss=$this->sendPostRequest($url,$data);
        var_dump($sss);die;
    }
     
    /**
     * 回调
     *
    */
    public function notify(){
        $this->noticelog('支付参数');
        $this->noticelog(json_encode($_GET));
        
        $mchOrderNo=$_GET['mchOrderNo'];
        // $ordersn=Db::name('cms_order')->where('orderid',$mchOrderNo)->find();
        // if($ordersn['status']==0){
        //     $date['status']=1;
        //     $date['out_trade_no']=$_GET['channelOrderNo'];
        //     Db::name('cms_order')->where('id',$ordersn['id'])->update($date);
        // }
    }
    public function noticelog($text)
     {
     $now = date('y-m-d', time());
     $filename = RUNTIME_PATH . 'notice_' . $now . '.log';
     $file = fopen($filename, "a+");  //a+表示文件可读写方式打开
     fwrite($file, $text . "\n");
    }
    public function denol(){
        $date = date('YmdHis');      // 年月日时分秒
        $rand = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT); // 5位随机数
        $ordersn= $date . $rand;

         
        //  $price=(int)$lisat['price'];
        //  var_dump($price);die;
        $privateKey='pKwVOACC9QbmvUpYgTZfbTWQvZb1AMxqM1IGKpeRpKJfkl1hqYyKJcCbXJIVZn3IW6vuutF1gcL5sJLrwgDbkbADtAIYs9BKQCDK9zlRDF0QTOnOJC0t9GZaZpGWgUHs';
        $notify_url ='https://che.damaii.cn/api/demo/notify';
        $data['mchNo']='M1754302502';
        $data['appId']='68908a3ee4b010532b9ef99e';
        $data['mchOrderNo']=$ordersn;
        $data['wayCode']='QR_CASHIER';
        $data['amount']=1*100;
        $data['currency']='cny';
        $data['subject']='咨询服务';
        $data['body']='咨询服务';
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        // $data['returnUrl']='https://qiu.damaii.cn/h5/#/pages/payment/payment?id='.$id;
        $data['notifyUrl']=$notify_url;
        $data['sign']=$this->generateSign($data,$privateKey);
        
        $url='https://pay.prod.6jqb.com/api/pay/unifiedOrder';
        $sss=$this->sendPostRequest($url,$data);
        // var_dump($sss);die;
        return  $sss;
    }
    public function generateSign(array $data, string $privateKey): string {
            // 1. 过滤空值参数（空字符串、null、false等转换为空的值）
            $filteredData = array_filter($data, function ($value) {
                return (string)$value !== '';
            });
            
            // 2. 移除签名参数（sign不参与签名）
            unset($filteredData['sign']);
            
            // 3. 按参数名ASCII字典序排序
            ksort($filteredData, SORT_STRING);
            
            // 4. 拼接键值对字符串
            $parts = [];
            foreach ($filteredData as $key => $value) {
                $parts[] = $key . '=' . $value;
            }
            $stringA = implode('&', $parts);
            
            // 5. 拼接私钥并生成MD5签名
            $stringSignTemp = $stringA . '&key=' . $privateKey;
            return strtoupper(md5($stringSignTemp));
        }
        
    public function sendPostRequest(string $url, array $data, string $contentType = 'form'): string {
        $ch = curl_init($url);
        $headers = [];
        
        // 根据Content-Type处理数据
        if ($contentType === 'json') {
            $postData = json_encode($data);
            // var_dump($postData);die;
            $headers[] = 'Content-Type: application/json';
        } else {
            $postData = http_build_query($data);
            //   var_dump($postData);die;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
        // var_dump($postData);die;
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
