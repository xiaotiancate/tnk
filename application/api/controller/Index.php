<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\xiluxc\ScoreLog;
use BsPaySdk\core\BsPay;
use think\Log;
use think\Db;
/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }
    
    //测试支付
    public function ceshi(){
        $apiUrl='http://marketpayktwo.dev.jh:8627/online/direct/gatherPlaceorder';
        $url='http://marketpayktwo.dev.jh:8028/online/direct/gatherPlaceorder';
        $jsonStr = '{
            "Ccy": "156",
            "Clrg_Dt": "20257021",
      
            "Ittparty_Jrnl_No": "2942856130859007152",
            "Ittparty_Stm_Id": "00000",
            "Ittparty_Tms": "20250709155932774",
            "Main_Ordr_No": "1942856130859057132",
            "Mkt_Id": "41060860802355",
            "Order_Time_Out": "1800",
            "Orderlist": [
                {
                    "Cmdty_Ordr_No": "19428561308590571520000",
                    "Mkt_Mrch_Id": "41060860802355000000",
                    "Ordr_Amt": 10.0,
             
                    "Txnamt": 10.0
                }
            ],
            "Ordr_Tamt": "10.0",
            "Pay_Dsc": "",
            "Py_Chnl_Cd": "0000000000000000000000000",
            "Py_Ordr_Tpcd": "04",
            "Pymd_Cd": "03",
            "Sign_Inf": "HC3YeYfE2FKra6LRQcqKa9+qXxkJLU4T+NGCToxkF+rnzvCV269ef/QljuZ5oBse5X0Zneimff27N7MapM2+m12jrRUaLn0qCX9vz2+Wr3iHqIWU0f/xn6olVen1rUkv1Ca595tygngAnJLn51dLwEZ+lldrdAgz9UKykHaVNX/w4idNhMWmCKMgaoqEGfwI9Whf1kH77jQsmOxuL05S7vBy+Nb0jTve6YbF56LxbcJ/itSkgc2vJhr84i5OzmUIOVoJpj8JcnOqrnqOajXC6vcsoCoe0nB72mFvKcxvmpHA5Zb3m2sTYmqYzj9f7B1KMLdU0OWbaBOTefIkvpixgw==",
            "Txn_Tamt": "10.0",
            "Vno": "4"
        }';
        
        
        
        $jsonStr='{
        	"Ccy": "156",
        	"Clrg_Dt": "20250723",
        	"Ittparty_Jrnl_No": "2942856130859007152",
        	"Ittparty_Stm_Id": "00000",
        	"Ittparty_Tms": "20250709155932774",
        	"Main_Ordr_No": "1942856130859057132",
        	"Mkt_Id": "41060860802355",
        	"Order_Time_Out": "1800",
        	"Ordr_Tamt": "10.0",
        	"Pay_Dsc": "",
        	"Py_Chnl_Cd": "0000000000000000000000000",
        	"Py_Ordr_Tpcd": "04",
        	"Pymd_Cd": "03",
        	"Sign_Inf": "HC3YeYfE2FKra6LRQcqKa9+qXxkJLU4T+NGCToxkF+rnzvCV269ef/QljuZ5oBse5X0Zneimff27N7MapM2+m12jrRUaLn0qCX9vz2+Wr3iHqIWU0f/xn6olVen1rUkv1Ca595tygngAnJLn51dLwEZ+lldrdAgz9UKykHaVNX/w4idNhMWmCKMgaoqEGfwI9Whf1kH77jQsmOxuL05S7vBy+Nb0jTve6YbF56LxbcJ/itSkgc2vJhr84i5OzmUIOVoJpj8JcnOqrnqOajXC6vcsoCoe0nB72mFvKcxvmpHA5Zb3m2sTYmqYzj9f7B1KMLdU0OWbaBOTefIkvpixgw==",
        	"Txn_Tamt": "10.0",
        	"Vno": "4"
        }';
        $goods[0]['Cmdty_Ordr_No']='19428561308590571520000';
        $goods[0]['Mkt_Mrch_Id']='41060860802355000000';
        $goods[0]['Ordr_Amt']=10;
        $Parlist[0]['Amt']=9;
        $Parlist[0]['Mkt_Mrch_Id']='41060860802355000010';
        $Parlist[0]['Seq_No']=1;
        $Parlist[1]['Amt']=1;
        $Parlist[1]['Mkt_Mrch_Id']='41060860802355000011';
        $Parlist[1]['Seq_No']=2;
        $goods[0]['Parlist']=$Parlist;
        $goods[0]['Txnamt']=10;
        // dump($goods);die;
    // $goods='[{
    //         "Cmdty_Ordr_No": "19428561308590571520000",
    //         "Mkt_Mrch_Id": "41060860802355000000",
    //         "Ordr_Amt": 10.0,
    //         "Parlist": [{
    //             "Amt": "9.0",
    //             "Mkt_Mrch_Id": "41060860802355000010",
    //             "Seq_No": "1"
    //         }, {
    //             "Amt": "1.0",
    //             "Mkt_Mrch_Id": "41060860802355000011",
    //             "Seq_No": "2"
    //         }],
    //         "Txnamt": 10.0
    //     }]';  // 移除了末尾的逗号
    
    // 将JSON字符串转换为PHP数组
    // $goodsArray = json_decode($goods, true);
    
    // 打印数组
    // print_r($goodsArray);die;
    

        	
        // $goods_array=json_decode($goods,true);
        // var_dump($goods_array);die;
        // 将JSON字符串转换为PHP数组
        $dataArray = json_decode($jsonStr, true);
            $dataArray['Orderlist']=json_encode($goods);
        $dataArray['Ittparty_Tms']=time();
        // dump($dataArray);die;
           $sign = $this->generateSign($dataArray, '');
        $dataArray['Sign_Inf']=$sign;
        // var_dump($dataArray);die;
        $response =$this->sendPaymentRequest($apiUrl, $dataArray);
        
        dump($response);die;
        
        
    }
    
    
    public function payapi(){
        
                
        
        // 设置时区
        date_default_timezone_set('Asia/Shanghai');
        
        // 请求URL
        $url = "http://marketpayktwo.dev.jh:8627/online/direct/gatherPlaceorder";
        
 
        
        // 构造请求数据
        $Ittparty_Stm_Id = "00000";
        $Py_Chnl_Cd = "0000000000000000000000000";
        $Ittparty_Tms = date("YmdHisv"); // PHP没有完全等同的毫秒格式化，这里简化处理
        $Ittparty_Jrnl_No = (string)(time() * 1000); // 近似处理
        $Mkt_Id = "41060860802355";
        $Main_Ordr_No = (string)(time() * 1000);
        $Pymd_Cd = "03";
        $Py_Ordr_Tpcd = "04";
        $Ccy = "156";
        $Pgfc_Ret_Url_Adr = "http://test.zmyou.com/rwy-pay";
        $Ordr_Tamt = "100";
        $Txn_Tamt = "100";
        $Hdcg_Brs_Id = "41060860802355000000";
        $Vno = "4";
        
        // 子订单1数据
        $Mkt_Mrch_Id1 = "41060860802355000000";
        $Cmdty_Ordr_No1 = $Ittparty_Jrnl_No . "01";
        $Ordr_Amt1 = "100";
        $Txnamt1 = "100";
        $Apd_Tamnt1 = "1";
        
        // 子订单2数据
        $Mkt_Mrch_Id2 = "41060860802355000010";
        $Cmdty_Ordr_No2 = $Ittparty_Jrnl_No . "02";
        $Ordr_Amt2 = "3";
        $Txnamt2 = "1";
        $Apd_Tamnt2 = "1";
        
        // 消费券数据
        $Cnsmp_Note_Ordr_Id1 = "C4106086089801631311";
        $Amt3 = "1";
        $Cnsmp_Note_Ordr_Id2 = "C4106086089801631140";
        $Amt33 = "1";
        
        // 附加项数据
        $Prj_Id = "P410608608980168604";
        $Prj_Nm = "加项";
        $Pjcy_Tp = "1";
        $Amt = "1";
        
        // 构造JSON数据
        $data = [
            "Ittparty_Stm_Id" => $Ittparty_Stm_Id,
            "Py_Chnl_Cd" => $Py_Chnl_Cd,
            "Ittparty_Tms" => $Ittparty_Tms,
            "Ittparty_Jrnl_No" => $Ittparty_Jrnl_No,
            "Mkt_Id" => $Mkt_Id,
            "Main_Ordr_No" => $Main_Ordr_No,
            "Pymd_Cd" => $Pymd_Cd,
            "Py_Ordr_Tpcd" => $Py_Ordr_Tpcd,
            "Ccy" => $Ccy,
            "Pgfc_Ret_Url_Adr" => $Pgfc_Ret_Url_Adr,
            "Ordr_Tamt" => $Ordr_Tamt,
            "Txn_Tamt" => $Txn_Tamt,
            "Vno" => $Vno,
            'Hdcg_Brs_Id'=>$Hdcg_Brs_Id,
            "Orderlist" => [
                [
                    "Mkt_Mrch_Id" => $Mkt_Mrch_Id1,
                    "Cmdty_Ordr_No" => $Cmdty_Ordr_No1,
                    "Ordr_Amt" => $Ordr_Amt1,
                    "Txnamt" => $Txnamt1,
                ],
                
                
            ],
               " parlist" => [
                [
                    "Seq_No" => 1,
                    "Mkt_Mrch_Id" => '41060860802355000000 ',
                    "Amt" => 40,
  
                ],
                   [
                    "Seq_No" => 2,
                    "Mkt_Mrch_Id" => '41060860802355000010  ',
                    "Amt" => 60,
  
                ]
            ]
        ];
        
        // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $data['Sign_Inf'] = $signInf;
        $finalJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";

    }
    public  function rsaSigna($data, $privateKey) {
        
            $privateKey = "-----BEGIN PRIVATE KEY-----\n" . 
                         chunk_split($privateKey, 64, "\n") . 
                         "-----END PRIVATE KEY-----\n";
            
            openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
            
            return base64_encode($signature);
    }
    public function callback(){
        // var_dump($_GET);die;
         Log::write('日志信息',  json_encode($_GET));
        Log::write('日志信息', json_encode($_POST));
        // $
        $_POST=array (
          'Ordr_Amt' => 100,
          'Main_Ordr_No' => '1753341287000',
          'Sign_Inf' => 'fMkRCTH2Y0K9tGqY9oMGfhMRn+zHpNmjYoSuHHOi21juevLMN3WdJhPqAYJJ8EhR+qSiRVfydMLGRrZmeBbzTdVmUbxUvJV9Epv4LULdnRZPgKLvGC8Qr8t/8dGdwK2LwBy6ImLCc0dWEsUFTQMdvsTHcqFTPVeb+FAL725vr3VtOJxQAhvmNdk0noGHhcqeGvxqH4ii5q9xxqCjNWeLj3SD8RVwD3xVUF27s+nAtfUjiE2DtQ4eEocqSLEf9u1shAltrQfN7vkRp5BQiFrbMoijIiGPK9TKNAXFI0gIu2KA4vaGjTU9DPRWwtUbagWpJHZm5LAdUZB9jo8Ehg7xoQ==',
          'Pay_Time' => '20250724151804',
          'Py_Trn_No' => '105000007630648250724000001539',
          'Txnamt' => 100,
          'Ordr_Stcd' => '2',
        );
        $Ordr_Amt=$_POST['Ordr_Amt'];
        $Main_Ordr_No=$_POST['Main_Ordr_No'];
        $Sign_Inf=$_POST['Sign_Inf'];
        $Pay_Time=$_POST['Pay_Time'];
        $Py_Trn_No=$_POST['Py_Trn_No'];
        $Txnamt=$_POST['Txnamt'];
             $Ordr_Stcd=$_POST['Ordr_Stcd'];
        $this->chaorder($Main_Ordr_No,$Py_Trn_No,$Ordr_Amt,$Txnamt,$Pay_Time,$Ordr_Stcd,$Sign_Inf);
        // return json_encode(array('code'=>1,'msg'=>'请求成功'));
        // var_dump(1);die;
        // \think\facade\Log::record('特殊日志', 'info', 'special');
        // \think\facade\Log::write('这是一条日志信息', json_decode($_GET));
            //   \think\facade\Log::write('这是一条日志信息', json_decode($_POST));
    }
    public function shou(){
        $url='http://marketpayktwo.dev.jh:8627/online/direct/mergeNoticeArrival';
                 $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>'10500000763064825072400001663H',
            'Prim_Ordr_No'=>'105000007630648250724000001539',
            'Mkt_Id'=>'41060860802355',
            'Vno'=>4,
            ];
            // var_dump($json);die;
        
        // dump($json);die;
        
        
        // echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
               // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
    }
    //查询退款
    public function chatka(){

        $url='http://marketpayktwo.dev.jh:8627/online/direct/enquireRefundOrder';
        // $json['Ittparty_Stm_Id']='00000';
            $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>'1753234977000',
            'Rfnd_Trcno'=>250723080132071,
            'Mkt_Id'=>'41060860802355',
            'Vno'=>4,
            ];
            // var_dump($json);die;
        
        // dump($json);die;
        
        
        // echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
               // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
        
    }
    public function fenzhang(){
        // https://marketpay.ccb.com/online/direct/subAccountEnquire
               $url='http://marketpayktwo.dev.jh:8627/online/direct/subAccountEnquire';
                 $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>'10500000763064825072400001663H',
            'Py_Trn_No'=>'10500000763064825072400001663H',
            'Mkt_Id'=>'41060860802355',
            'Vno'=>5,
            ];
            // var_dump($json);die;
        
        // dump($json);die;
        
        
        // echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
               // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
    }
    //查询退款
    public function chatk(){

        $url='http://marketpayktwo.dev.jh:8627/online/direct/enquireRefundOrder';
        // $json['Ittparty_Stm_Id']='00000';
            $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>'1753234977000',
            'Rfnd_Trcno'=>250723080132071,
            'Mkt_Id'=>'41060860802355',
            'Vno'=>4,
            ];
            // var_dump($json);die;
        
        // dump($json);die;
        
        
        // echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
               // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
    }
    //查询接口
    public function chaorder($Main_Ordr_No,$Py_Trn_No,$Ordr_Amt,$Txnamt,$Pay_Time,$Ordr_Stcd,$Sign_Inf){
        // 市场方地址
        // $url = "http://marketpayktwo.dev.jh:8627/payreback/notice";
          $url = "http://marketpayktwo.dev.jh:8627/online/direct/gatherEnquireOrder";
        // RSA算法采用该私钥

        // 主订单编号
        // $Main_Ordr_No = round(microtime(true) * 1000);
        // 支付流水号
        // $Py_Trn_No = "12345";
        // 订单金额
        // $Ordr_Amt = "2";
        // 支付金额
        // $Txnamt = "2";
        // 支付时间
        // $Pay_Time = date("YmdHisv");
        // 订单状态代码
        // $Ordr_Stcd = "1";
        
        // $json = [
        //     // 主订单编号
        //     "Main_Ordr_No" => $Main_Ordr_No,
        //     // 支付流水号
        //     "Py_Trn_No" => $Py_Trn_No,
        //     // 订单金额
        //     "Ordr_Amt" => $Ordr_Amt,
        //     // 支付金额
        //     "Txnamt" => $Txnamt,
        //     // 支付时间
        //     "Pay_Time" => $Pay_Time,
        //     // 订单状态代码
        //     "Ordr_Stcd" => $Ordr_Stcd
        // ];
        
        $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>$Py_Trn_No,
            'Mkt_Id'=>'41060860802355',
            'Vno'=>4,
            'Main_Ordr_No'=>$Main_Ordr_No
            ];
            // var_dump($json);die;
        
        // dump($json);die;
        
        
        // echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
               // 生成JSON字符串（保持键的顺序）
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
        // 注意：PHP中没有直接对应的SplicingUtil.createSign方法
        // 这里假设它是将JSON按特定规则拼接成字符串
        // $jsonString = createSign(json_encode($json));
        // echo "加签字符串：" . $jsonString . "\n";
        
        // // 使用私钥进行签名
        // $signInf = rsaSign($private_RSA, $jsonString);
        // $json["Sign_Inf"] = $signInf;
        
        // echo "传入的json串：" . json_encode($json, JSON_PRETTY_PRINT) . "\n";
        
        // try {
        //     $result = httpPostJson($url, $json);
        //     echo "result=" . $result . "\n";
        // } catch (Exception $e) {
        //     echo $e->getMessage() . "\n";
        // }

    }
    
    public function push(){
        Log::write('日志信息',  json_encode($_GET));
        Log::write('日志信息', json_encode($_POST));
        return '成功';
            // return $result;
    }
    // https://che.damaii.cn/api/index/push    推送
    //退款接口
    
    public function tk(){

        $url='http://marketpayktwo.dev.jh:8627/online/direct/refundOrder';
        
        //           'Ordr_Amt' => 1,
        //   'Main_Ordr_No' => '1753180388000',
        //   'Sign_Inf' => 'dkokE85/v8PfrBDqn6w6zl3d4rmQVtUDcSUOxNBE6EuFdOfWVUdtWCiBTPN70caVrZs3rY/9SFSYpqzCKTKqtJZ+S3b8gJE83nWUgPoCO8mHPxpkCAsXljPILHrTZy8MTLRS6mmgPo+8S845bfWZx2b1Lp7feDtbCWwa0DLdddRwJzJivHU/yR1iXEvpUJnFGzW5EsWznX7aG7wAXJoin1lwLzxh6u9/HV0YpV+BChm1AtmcJ8B0g4+Z9EkRn0Ha1tr+U9M8wtFAq8Koe9k4BMInwBy5LWG8eNZwq8Dq7/gfogppUdMgHPMLjX523x4A+ACsIJ1ndUfoAX7g1j6I2Q==',
        //   'Pay_Time' => '20250722183604',
        //   'Py_Trn_No' => '10500000763064825072200001617H',
        //   'Txnamt' => 1,
        //   'Ordr_Stcd' => '2',
        $json=[
            'Ittparty_Stm_Id'=>'00000',
            'Py_Chnl_Cd'=>'0000000000000000000000000',
            'Ittparty_Tms'=>date("YmdHisv"),
            'Ittparty_Jrnl_No'=>'1753234977000',
            'Mkt_Id'=>'41060860802355',
            'Rfnd_Amt'=>0.5,
            'Sub_Ordr_List'=> [
                    "Sub_Ordr_Id" => '105000007630648250723000001517001',
                    "Rfnd_Amt" => 0.5,
                ],
            'Vno'=>4,
            'Py_Trn_No'=>'10500000763064825072300001619H',
        ];
        
              $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // 打印JSON数据
        echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        $signString = $this->buildSignatureString( json_decode($jsonString, true));
        echo "加签字符串:<br>" . $signString . "<br>";
        
        // RSA签名（需要OpenSSL扩展）

        
        $signInf = $this->rsaSign($signString);
        // echo "签名结果:<br>" . $signInf . "<br>";
        
        // 将签名添加到JSON数据
        $json['Sign_Inf'] = $signInf;
        $finalJson = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        $response = $this->sendRequest($url, $finalJson);
        echo "API响应:<br>" . $response . "<br>";
    }


    public function sendRequest($urlPath, $json) {
    $result = '';
    
    try {
        $ch = curl_init();
        
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $urlPath);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Connection: Keep-Alive',
            'Charset: UTF-8',
            'Content-Type: application/json; charset=UTF-8',
            'userid: 8a80c4d7648dcd0401648dcd316c0000',
            'serviceid: 4028bcd26565cc9901658e01b9f40000',
            'accept: application/json'
        ]);
        
        // 禁用缓存
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        
        // 设置 POST 数据
        if ($json !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        
        // 执行请求
        $result = curl_exec($ch);
        
        // 获取 HTTP 响应码
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "http返回码: " . $httpCode . "\n";
        
        // 检查错误
        if (curl_errno($ch)) {
            throw new Exception('cURL 错误: ' . curl_error($ch));
        }
        
        // 关闭 cURL 资源
        curl_close($ch);
        
    } catch (Exception $e) {
        // 错误处理
        echo '请求异常: ' . $e->getMessage() . "\n";
        return false;
    }
    
    return $result;
}


     // 生成签名字符串（这里需要实现SplicingUtil.createSign的PHP版本）
    public function createSigna($jsonString) {
    // 这里应该实现与Java相同的签名字符串生成逻辑
    // 通常包括：按字典序排序、拼接键值对、去除特殊字符等
    // 这是一个简化版的示例，实际实现应根据Java代码的具体逻辑
    
    // 将JSON字符串解码为数组
    $data = json_decode($jsonString, true);
    
    // 递归排序所有键
    array_walk_recursive($data, function(&$value, $key) {
        if (is_array($value)) {
            ksort($value);
        }
    });
    ksort($data);
    
    // 拼接键值对
    $signStr = '';
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $signStr .= $key . '=' . json_encode($value, JSON_UNESCAPED_UNICODE) . '&';
        } else {
            $signStr .= $key . '=' . $value . '&';
        }
    }
    
    // 去除最后的&
    $signStr = rtrim($signStr, '&');
    
    return $signStr;
}

    public function ksortRecursive(&$array) {
        if (is_array($array)) {
            ksort($array);
            foreach ($array as &$value) {
                if (is_array($value)) {
                   $this->ksortRecursive($value);
                }
            }
        }
    }
    
    public function buildSignatureString($data) {
        if (!is_array($data)) {
            return $data;
        }
        
        $this->ksortRecursive($data);
        $parts = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // 检查是否是数字索引数组（列表）
                if (array_keys($value) === range(0, count($value) - 1)) {
                    foreach ($value as $item) {
                        if (is_array($item)) {
                            $parts[] = $this->buildSignatureString($item);
                        }
                    }
                } else {
                    $parts[] =$this->buildSignatureString($value);
                }
            } else {
                $parts[] = "$key=$value";
            }
        }
        
        return implode('&', $parts);
    }



     /**
     * 请求
     *
     * @param [type] $url 请求地址
     * @param [type] $data 请求数据
     * @return void
     */
    public function requestApi($url, $data)
    {
        // $signstring = $this->getSignString($data);
        $sign = $this->rsaSign(MD5(json_encode($data)));
        $data = $this->publicEncrypt(json_encode($data));
        // $ress = $this->verifySign($data,$sign,$this->getPublicKey2());
        $request = [
            'app_id' => '17ca4677d3cb40dfbc46d273480b0fb8',
            'time'  => time(),
            'sign'  => $sign,
            'data'  => $data
        ];
        $request = json_encode($request);
        $url = 'https://wapi.waas.group/api/v1' . $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }

    /**
     * 使用公钥将数据加密
     * @param $data string 需要加密的数据
     * @param $publicKey string 公钥
     * @return string 返回加密串(base64编码)
     */
    public static function publicEncrypt($data)
    {
        $publicKey = self::getPublicKey();
        $data = str_split($data, 117);
        $encrypted = '';
        foreach ($data as &$chunk) {
            // $chunk = str_pad($chunk, 117);
            if (!openssl_public_encrypt($chunk, $encryptData, $publicKey)) {
                return '';
            } else {
                $encrypted .= $encryptData;
            }
        }
        return self::urlSafeBase64encode($encrypted);
    }


    /**
     * 获取baas公钥
     * @return bool|resource
     */
    public  function getPublicKey()
    {
        // $content = file_get_contents('baas_key.key');
        $fKey = "-----BEGIN PRIVATE KEY-----\n";
        $pubKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0vbHtcG6udUl8OPW2YMyHY/0oYletfG/7zQ87RYSv4ltZw9czMaO9mA/ch0V/I1Vp6wcg8C9fNdOxytp2Gx7vLdwXMXfJN/7aWYKLGwzqFxMQ7iRtNnMEseI2wiuseVlr7nBjvrY6DGgdaTxLqyukfAFcpHBMwenqNGxko8emVA4d6VBEjF0ypF6EDv3yaH6tQYm1lKuSqQHXjS/soxVFGYNOTx9iu10RafJ8HvbQ0Nx671MdR4cjT/yBcVP10kfw+7mbGLmDnoYgV++pkM2A/D6HJ0tHoY6XMoaO0f45hrg+DugRnVsQrYaza60XeoNN9P0730YnLYrhCQDEkKn/wIDAQAB';
        $len = strlen($pubKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PRIVATE KEY-----";
        $public_key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($pubKey, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
        return openssl_pkey_get_public($public_key);
    }


    /**
     * 私钥加签名
     * @param $data 被加签数据
     * @param $privateKey 私钥
     * @return mixed|string
    */
    public  function rsaSign($data){
        $privateKey = $this->getPrivateKey();
        if(openssl_sign($data, $sign, $privateKey, OPENSSL_ALGO_SHA256)){
            return $this->urlSafeBase64encode($sign);
        }
        return '';
    }

        //对接支付
        
    public  function pay_one1(){
        session_start(); 
        // die;
        $money = $this->request->post('money');
        $uid = $this->request->post('uid');
        
        if ($money < 100) {
            $this->error('最少充值100');
        }
        
        
        $notify_url ='https://che.damaii.cn/api/index/notify';
        // var_dump($uid);die;
        $user=Db::name('third')->where('user_id',$uid)->find();
        // var_dump($user['openid']);die;
        $can['openid']=$user['openid'];
        $can['isSubOpenId']=1;
        $data['channelExtra']=json_encode($can);
            
        $url='https://pay.prod.6jqb.com/api/pay/unifiedOrder';
        $data['mchNo']='M1755156100';
        $data['appId']='689d9c5ce4b0c2ccfd6f3a3f';
        $data['mchOrderNo']=time();
        $data['wayCode']='WX_JSAPI';
        //  $data['wayCode']='WX_JSAPI';
        $data['amount']=$money*100;
        $data['currency']='cny';
        $data['subject']='余额充值';
        $data['body']='余额充值';
        $data['reqTime']=time();
        $data['version']='1.0';
        $data['signType']='MD5';
        $data['notifyUrl']=$notify_url;
        $data['returnUrl']='https://che.damaii.cn/web/pages/profile/payment';
        $privateKey='wt1DoX567CaEQVYiLMU2czan9MSWYYseZ4ajFAkL4mBgMVUQNZzn4FjUFsX6w8QiVwzdJwhxAXAWhCzXQqcLQtiNrXXvBc9phGOzjlQfExpIAHmZIoIrr3Hv5aluToGj';
 
      
        $data['sign']=$this->generateSign($data,$privateKey);
        
        $url='https://pay.prod.6jqb.com/api/pay/unifiedOrder';
        $sss=$this->sendPostRequest($url,$data);
        // $pa['orderid']=$ordersn;
        $pa['user_id']=$uid;
        $pa['platform']='wxmini';
        $pa['order_no']=$data['mchOrderNo'];
        $pa['pay_type']=1;
        $pa['pay_fee']=$money;
        $pa['recharge_money']=$money;
        $pa['recharge_extra_money']=0;
        $pa['recharge_total_money']=$money;
        $pa['real_price']=$money;
        $pa['createtime']=time();
        $pa['admin_id']=$uid;
        // $pa['archives_id']=$id;
        // $pa['title']='付费';
        // $pa['amount']=$price;
        // $pa['payamount']=$price;
        // $pa['paytype']=$a;
        // $pa['createtime']=time();
        $instid=Db::name('xiluxc_recharge_order')->insert($pa);
        
        $array=json_decode($sss);
        $this->success('数据',$array);
        
        
        // dump($array);die;
        
        
        // $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // // 打印JSON数据
        // echo "JSON数据:<br>" . $jsonString . "<br>";

        
        
        // // $signString = $this->buildSignatureString( json_decode($jsonString, true));
        // // echo "加签字符串:<br>" . $signString . "<br>";
        
        // // RSA签名（需要OpenSSL扩展）

        
        // // $signInf = md5($signString);
        // // $signInf = strtoupper($signInf);
        // // var_dump($signInf);die;
        // // echo "签名结果:<br>" . $signInf . "<br>";
        
        // // // 将签名添加到JSON数据
        // // $data['Sign_Inf'] = $signInf;
        
        // // 签名密钥（你提供的）
        // $key = 'wt1DoX567CaEQVYiLMU2czan9MSWYYseZ4ajFAkL4mBgMVUQNZzn4FjUFsX6w8QiVwzdJwhxAXAWhCzXQqcLQtiNrXXvBc9phGOzjlQfExpIAHmZIoIrr3Hv5aluToGj';
        
        // // 1. 按字典序排序参数
        // ksort($data);
        
        // // 2. 构建待签名字符串
        // $signStr = '';
        // foreach ($data as $key => $value) {
        //     $signStr .= $key . '=' . $value . '&';
        // }
        // $signStr .= 'key=' . $key; // 注意这里加上key和密钥
        
        // // 3. 计算MD5签名
        // $sign = md5($signStr);
        
        // // 输出结果
        // echo "待签名字符串: " . $signStr . "\n";
        // echo "MD5签名结果: " . $sign . "\n";
        
        // // 4. 最终请求参数（包含签名）
        // $data['sign'] = strtoupper($sign);
        // $finalJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        //   echo "最终请求数据:<br>" . $finalJson . "<br>";


        
        // $response = $this->sendRequest($url, $finalJson);
        // echo "API响应:<br>" . $response . "<br>";
        
        // echo "最终请求数据:<br>" . $finalJson . "<br>";
        
            
    }

    public function pay_one2(){
//        dump('11');die();
        // 手动加载 BsPaySdk 相关文件（因为 dg-php-sdk 包没有配置自动加载）
        $sdkPath = __DIR__ . '/../../../vendor/huifurepo/dg-php-sdk/BsPaySdk/';
        require_once $sdkPath . 'core/BsPayRequestV2.php';
        require_once $sdkPath . 'core/BsPay.php';
        require_once $sdkPath . 'core/BsPayTools.php';
        require_once $sdkPath . 'config/MerConfig.php';

        // 确保 BsPay 已经初始化配置
        if (empty(BsPay::getConfig())) {
            // 这里需要根据实际情况初始化配置，可以从配置文件或数据库读取
            // 示例：BsPay::init(__DIR__ . '/../../../config/bspay_config.json');
            
            // 如果暂时没有配置文件，可以手动创建配置
            $config = [
                'product_id' => 'your_product_id',
                'sys_id' => 'your_sys_id',
                'rsa_merch_private_key' => 'your_private_key',
                'rsa_huifu_public_key' => 'huifu_public_key'
            ];
            BsPay::init($config, true);
        }

        $merConfig = BsPay::getConfig();
//        dump($merConfig);die();

// 2.组装请求参数
        $paramsInfo = array();
// 请求日期
        $paramsInfo["req_date"]= "20260117";
// 请求流水号
        $paramsInfo["req_seq_id"]= "rQ20260117154517543186";
// 商户号
        $paramsInfo["huifu_id"]= "6666000109133323";
// 商品描述
        $paramsInfo["goods_desc"]= "hibs自动化-通用版验证";
// 交易类型
        $paramsInfo["trade_type"]= "T_MINIAPP";
// 交易金额
        $paramsInfo["trans_amt"]= "0.10";
// 交易有效期
        $paramsInfo["time_expire"]= "20250518235959";
// 微信参数集合
        $paramsInfo["wx_data"]= "{\"sub_appid\":\"wxdfe9a5d141f96685\",\"sub_openid\":\"o8jhotzittQSetZ-N0Yj4Hz91Rqc\",\"detail\":{\"cost_price\":\"43.00\",\"receipt_id\":\"20220628132043853798\",\"goods_detail\":[{\"goods_id\":\"6934572310301\",\"goods_name\":\"太龙双黄连口服液\",\"price\":\"43.00\",\"quantity\":\"1\",\"wxpay_goods_id\":\"12235413214070356458058\"}]}}";
// 是否延迟交易
        $paramsInfo["delay_acct_flag"]= "N";
// 分账对象
        $paramsInfo["acct_split_bunch"]= "{\"acct_infos\":[{\"div_amt\":\"0.10\",\"huifu_id\":\"6666000109133323\"}]}";
// 传入分账遇到优惠的处理规则
        $paramsInfo["term_div_coupon_type"]= "0";
// 禁用信用卡标记
        $paramsInfo["limit_pay_type"]= "NO_CREDIT";
// 场景类型
        $paramsInfo["pay_scene"]= "02";
// 备注
        $paramsInfo["remark"]= "string";
// 安全信息
        $paramsInfo["risk_check_data"]= "{\"ip_addr\":\"180.167.105.130\",\"base_station\":\"192.168.1.1\",\"latitude\":\"33.3\",\"longitude\":\"33.3\"}";
// 设备信息
        $paramsInfo["terminal_device_data"]= "{\"device_type\":\"1\",\"device_ip\":\"10.10.0.1\",\"device_gps\":\"192.168.0.0\",\"devs_id\":\"SPINTP357338300264411\"}";
// 异步通知地址
        $paramsInfo["notify_url"]= "http://www.baidu.com";

// 3. 发起API调用
        $bspay = new BsPay();
        

        $result = $bspay->post("v3.trade.payment.jspay", $paramsInfo, '', 'default');
        if (!$result || $result->isError()) {  //失败处理
            var_dump($result -> getErrorInfo());
        } else {    //成功处理
            var_dump($result);
        }

    }

    public function pay_one(){
        require_once ROOT_PATH . 'huifurepo/BsPayDemo/loader.php';
        BsPay::getConfig(); // 加载配置（确保config.ini里填了你的商户信息）
dump('111');die();
        // 2. 组装支付参数（重点：trade_type必须是"A_NATIVE"，正扫对应的类型！）
        $params = [
            "req_date" => date("Ymd"), // 今天日期
            "req_seq_id" => "rQ" . date("YmdHis") . rand(100000, 999999), // 唯一流水号
            "huifu_id" => "你的商户号", // 替换成你的真实商户号
            "goods_desc" => "测试商品",
            "trade_type" => "A_NATIVE", // 正扫必须传这个值！（固定）
            "trans_amt" => "0.01", // 测试金额0.01元
            "time_expire" => date("YmdHis", strtotime("+30分钟")), // 二维码有效期30分钟
            "wx_data" => json_encode([ // 微信正扫只需要sub_appid（必填）
                "sub_appid" => "你的微信子商户APPID" // 替换成你的
            ]),
            "alipay_data" => "{}", // 支付宝正扫可以传空（SDK会自动处理）
            "notify_url" => "http://你的域名/api/pay/notify", // 支付成功后的回调地址
        ];

        // 3. 调用汇付接口，生成二维码
        $bspay = new BsPay();
        $result = $bspay->post("v3.trade.payment.jspay", $params, '', 'default');

        // 4. 处理返回结果，提取二维码信息
        if (!$result || $result->isError()) {
            // 失败：返回错误信息
            return json([
                'code' => -1,
                'msg' => $result->getErrorInfo()
            ]);
        }

        // 成功：提取微信/支付宝的二维码链接
        $payData = $result->getData();
        $qrCodeUrl = '';
        if (!empty($payData['code_url'])) {
            // 微信正扫返回的是code_url（比如weixin://wxpay/bizpayurl?xxx）
            $qrCodeUrl = $payData['code_url'];
        } elseif (!empty($payData['qr_code'])) {
            // 支付宝正扫返回的是qr_code（直接是二维码链接）
            $qrCodeUrl = $payData['qr_code'];
        }

        // 5. 返回二维码链接给前端
        return json([
            'code' => 1,
            'msg' => '二维码生成成功',
            'data' => [
                'qr_code_url' => $qrCodeUrl, // 二维码链接
                'out_trade_no' => $params['req_seq_id'] // 你的订单号，后续查单要用
            ]
        ]);
    }
    public function notify() {
        // var_dump(1);die;
        $this->noticelog('支付参数');
        $this->noticelog(json_encode($_GET));
        
        $mchOrderNo = $_GET['mchOrderNo'];
        $state = $_GET['state'];
        $ordersn = Db::name('xiluxc_recharge_order')->where('order_no',$mchOrderNo)->find();
        if($state == 2 && $ordersn['pay_status'] == 'unpaid'){
            $date['pay_status']     = 'paid';
            $date['paytime']        = time();
            $date['updatetime']     = time();
            $date['order_trade_no'] = $_GET['channelOrderNo'];
            Db::name('xiluxc_recharge_order')->where('id',$ordersn['id'])->update($date);
            $user_level = Db::name('user')->where('id', $ordersn['user_id'])->value('level');
            
            $account = Db::name('xiluxc_user_account')->where('user_id',$ordersn['user_id'])->find();
            
            $money = bcadd($account['money'], $ordersn['pay_fee'], 2);
            $data['money'] = $money;
            
            if ($user_level > 0) {
                $data['points'] = bcadd($account['points'], $ordersn['pay_fee']);
            }
            
            Db::name('xiluxc_user_account')->where('user_id',$ordersn['user_id'])->update($data);
            //加余额
            //fa_user_money_log
            $money_log['type'] = 1;
            $money_log['event'] = 'recharge';
            $money_log['user_id'] = $ordersn['user_id'];
            $money_log['order_id'] = $ordersn['id'];
            $money_log['money']   = $ordersn['pay_fee'];
            $money_log['before']  = $account['money'];
            $money_log['after']   = $money;
            $money_log['memo']    = '充值';
            $money_log['createtime'] = time();
            Db::name('xiluxc_money_log')->insert($money_log);
            
            
            if ($user_level > 0) {
                $score_log = [
                    'event'    => 'recharge',
                    'user_id'  => $ordersn['user_id'],
                    'order_id' => $ordersn['id'],
                    'score'    => $ordersn['pay_fee'],
                    'before'   => $account['points'],
                    'after'    => $data['points'],
                    'memo'     => '充值赠送',
                ];
                ScoreLog::create($score_log);
            }
            
        }
    }
    
    
    public function notifya(){
        // var_dump(1);die;
        $this->noticelog('支付参数');
        $this->noticelog(json_encode($_GET));
        
        $mchOrderNo=$_GET['mchOrderNo'];
        $state = $_GET['state'];
        
        $ordersn = Db::name('xiluxc_vip_order')->where('order_no',$mchOrderNo)->find();
        if($state == 2 && $ordersn['pay_status'] == 'unpaid'){
            $date['pay_status']='paid';
            $date['paytime']=time();
            $date['updatetime']=time();
            $date['order_trade_no']=$_GET['channelOrderNo'];
            Db::name('xiluxc_vip_order')->where('id',$ordersn['id'])->update($date);
            
            $user=Db::name('user')->where('id',$ordersn['user_id'])->find();
            $account = Db::name('xiluxc_user_account')->where('user_id',$ordersn['user_id'])->find();
            
            $before = $account['money'];
            $money = bcadd($account['money'], $ordersn['pay_fee'], 2);

            Db::name('xiluxc_user_account')->where('user_id',$ordersn['user_id'])->update(['money' => $money]);

            $data['level'] = $ordersn['vip_id'];
            $data['viptime'] = time();
            Db::name('user')->where('id', $ordersn['user_id'])->update($data);
            //加余额
            //fa_user_money_log
            $money_log = [
                'type'      => 4,
                'event'     => 'vip',
                'user_id'   => $ordersn['user_id'],
                'money'     => $ordersn['pay_fee'],
                'before'    => $before,
                'after'     => $money,
                'memo'      => '开通VIP',
                'createtime' => time()
            ];
            Db::name('xiluxc_money_log')->insert($money_log);
            
            $score_log = [
                'event'    => 'vip',
                'user_id'  => $ordersn['user_id'],
                'order_id' => $ordersn['id'],
                'score'    => $ordersn['pay_fee'],
                'before'   => $account['points'],
                'after'    => bcadd($account['points'], $ordersn['pay_fee'], 2),
            ];
            
            ScoreLog::create($score_log);
        }
    }
    
    public function noticelog($text)
    {
     $now = date('y-m-d', time());
     $filename = RUNTIME_PATH . 'notice_' . $now . '.log';
     $file = fopen($filename, "a+");  //a+表示文件可读写方式打开
     fwrite($file, $text . "\n");
    }
    
        public function sendPostRequest(string $url, array $data, string $contentType = 'form'): string {
        $ch = curl_init($url);
        $headers = [];
        
        // 根据Content-Type处理数据
        if ($contentType === 'json') {
            $postData = json_encode($data);
            $headers[] = 'Content-Type: application/json';
        } else {
            $postData = http_build_query($data);
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
        
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
    public  function rsaSignn($data){
        $privateKey = $this->getPrivateKeyss();
        // $privateKey='wt1DoX567CaEQVYiLMU2czan9MSWYYseZ4ajFAkL4mBgMVUQNZzn4FjUFsX6w8QiVwzdJwhxAXAWhCzXQqcLQtiNrXXvBc9phGOzjlQfExpIAHmZIoIrr3Hv5aluToGj';
        if(openssl_sign($data, $sign, $privateKey, OPENSSL_ALGO_SHA256)){
            return $this->urlSafeBase64encode($sign);
        }
        return '';
    }
    
        public  function getPrivateKeyss()
    {
        // $content = file_get_contents('private_key.pem');
        $fKey = "-----BEGIN PRIVATE KEY-----\n";
        $priKey = 'wt1DoX567CaEQVYiLMU2czan9MSWYYseZ4ajFAkL4mBgMVUQNZzn4FjUFsX6w8QiVwzdJwhxAXAWhCzXQqcLQtiNrXXvBc9phGOzjlQfExpIAHmZIoIrr3Hv5aluToGj';
        $len = strlen($priKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($priKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PRIVATE KEY-----";
        return openssl_pkey_get_private($fKey);
    }
    /**
     * 获取私钥
     * @return bool|resource
     */
    public  function getPrivateKey()
    {
        // $content = file_get_contents('private_key.pem');
        $fKey = "-----BEGIN PRIVATE KEY-----\n";
        $priKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDS9se1wbq51SXw49bZgzIdj/ShiV618b/vNDztFhK/iW1nD1zMxo72YD9yHRX8jVWnrByDwL18107HK2nYbHu8t3Bcxd8k3/tpZgosbDOoXExDuJG02cwSx4jbCK6x5WWvucGO+tjoMaB1pPEurK6R8AVykcEzB6eo0bGSjx6ZUDh3pUESMXTKkXoQO/fJofq1BibWUq5KpAdeNL+yjFUUZg05PH2K7XRFp8nwe9tDQ3HrvUx1HhyNP/IFxU/XSR/D7uZsYuYOehiBX76mQzYD8PocnS0ehjpcyho7R/jmGuD4O6BGdWxCthrNrrRd6g030/TvfRictiuEJAMSQqf/AgMBAAECggEAHa61OMCSSjVQSk10XFRWR8yKafQPDGCAVeKus9kIOETYzMhfkTxavxWZt6+Z+VfVdmsD9BG5V4hfwCw+j0HsQwg4WgVJOUH+eLzvr4Jl3klmPZ0Jez2ttfK3McJN+h/Bp/Dl5/0paboZzpOvj5aiVUxFJ/KUEV8BWwJuDqXuczmRsG/JwXCDnLsrIEMmyBXDgvGSEiu/L6mjfIMNpwBPGkTiiJGRlBSWIAWdQr/jNw/po0zb+jlCVGWoPcivWGAafXJQX66aAk4JMiNCuLjdkH+xen/xSWU/QEQ9nWwLRJh6l9shvPWY3bfqQKPYkDGyyLdIUzxpIQBpkn+SZp5TSQKBgQD7KOtBC0bcVPpOVu333BC/pxMzqA2HPMyflQuUr7Di6HXZulxb2qEuXfafRMgVo7puLu/TDozZa83L7W1nEf5nbjClVEgVMbhT1uNgkIRuJ8CJu50wnDWUBsmMB4+Dn6kUG7YD/Psp4M1xzhWQSXvyxFXzAf6+DzrISK0FymJeVQKBgQDXB462cxKLbot8la1rQIjw2lX6p/WMBASuqOnPFlkDjIZuQbYfXchWxJRXVLCUikPzBLjWvJHbjrhmBTeIyxTyib+JThqaMzyMf0Y77/GiUJdUBx7PFHFyhDaj+jSmOxi7ceSZF37RNn539D2S6E7Qusj3OYPlaQrSV1WmLxBZAwKBgQDh9lSBdnXQMRvpc0gxoOnoo5Yg+WcCbu7h/CQpJ1ALNX0h4ArMEQzGPH9vl2A0J9PI4a2eww5xZg4HFJtDCetKftaBSCx59PuTYle7PwoGWPlecU7gtwl1Hg4iT4MMto5VqwC84dPOP5RWeUTpRVOgfIefVAIuWGFYZBpWhViu6QKBgQDLU3gdCX6VnbgD3DyZV/KlXK9ETyGefgY3ab18djNBadWL2FLwIevYMBXc5lX6fyt1Vhe55aE+LRwsS+6RSQbLuHkGynXZLW2ppIezEVY5F1+gswLs6PXFRUOtll/Gd8cRJ8bzBAaEqbS4lJjMmyI7uQNi0l3nxYXYE4EHnSUmJQKBgHqcrvr0P2fl01Yma4CxU/CVppAWpN2xPFrIaOgIhMRwAIB/VJxMIcA35hjf5IDMFeevGFtqUCMHtxvFeDWsQlFw4WVR3aaATnQypNC3lukH4kgko7v6IsVj0NoaVOJd2kpNmivlcQGNGX1fg+wfkuF2vksKWfsJVZjM8GUoqMiu';
        $len = strlen($priKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($priKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PRIVATE KEY-----";
        return openssl_pkey_get_private($fKey);
    }

    /**
     * url base64编码
     * @param $string
     * @return mixed|string
     */
    public static function urlSafeBase64encode($string)
    {
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($string));
        return $data;
    }


    /**
     * 验证返回data
     *
     * @param [type] $data
     * @return void
     */
    public function verifydata($data)
    {
        $res  = $this->object_array($data);
        $decrypt_data = $this->privateDecrypt($res['data'], $this->getPrivateKey());
        $decrypt_data = $this->object_array(json_decode($decrypt_data));
        return $decrypt_data;
    }

    /**
     * 对象转数组
     *
     * @param [type] $array
     * @return void
     */
    public function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    /**
     * 使用私钥解密
     * @param $data string 需要解密的数据
     * @param $privateKey string 私钥
     * @return string 返回解密串
     */
    public static function privateDecrypt($data, $privateKey)
    {
        $data = str_split(self::urlSafeBase64decode($data), 256);

        $decrypted = '';
        foreach ($data as &$chunk) {
            if (!openssl_private_decrypt($chunk, $decryptData, $privateKey)) {
                return '';
            } else {
                $decrypted .= $decryptData;
            }
        }
        return $decrypted;
    }

    /**
     * url base64解码
     * @param $string
     * @return bool|string
     */
    public static function urlSafeBase64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }



}
