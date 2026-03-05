<?php


namespace app\api\controller\xiluxc;


use app\common\controller\xiluxc\XiluxcApi;
use app\common\model\xiluxc\current\Config;
use app\common\model\xiluxc\activity\Coupon AS CouponModel;
use app\common\model\xiluxc\activity\UserCoupon;
use app\common\model\xiluxc\brand\Shop;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use function fast\array_get;

class Coupon extends XiluxcApi
{
    protected $noNeedLogin = [];

    /**
     * 我的优惠券
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function mycoupons(){
        $params = $this->request->param('');
        $tab = array_get($params,'tab');
        $pagesize = array_get($params,'pagesize',10);
        $where = UserCoupon::buildWhere($tab);
        $shops = Shop::alias("shop")
            ->join("XiluxcUserCoupon user_coupon","shop.id=user_coupon.shop_id")
            ->join("XiluxcCoupon coupon","coupon.id=user_coupon.coupon_id")
            ->field(['shop.id', 'shop.name', 'shop.image','shop.lat', 'shop.lng'])
            ->where('user_coupon.user_id',$this->auth->id)
            ->where($where)
            ->order('user_coupon.id','desc')
            ->group('user_coupon.shop_id')
            ->paginate($pagesize);
        foreach ($shops as $shop){
            $shop->append(['image_text']);
            $lists = UserCoupon::field("id,coupon_id,use_status")
                ->with(['coupon'=>function($q){
                    $q->withField(['id','name','at_least','money','use_end_time']);
                }])
                ->where('user_coupon.user_id',$this->auth->id)
                ->where($where)
                ->where("user_coupon.shop_id",$shop->id)
                ->order('user_coupon.id','desc')
                ->select();
            foreach ($lists as $list){
                $list->coupon->append(['use_end_time_text']);
                $list->append(['state']);
            }
            unset($list);
            $shop['coupon'] = $lists;
        }

        $this->success('',$shops);
    }
    
    
    public function kt(){
        // var_dump(1);die;
        $id = $this->request->post('id');
            // var_dump($id);die;
        $userId = $this->auth->id;
         //fa_xiluxc_shop_vip
        $vip = Db::name('xiluxc_shop_vip')->where('id',$id)->find();
        
        $user = Db::name('user')->where('id', $this->auth->id)->find();
        if ($user->level > 0) {
            $this->error('你已开通VIP，无法再开通');
        }
    
         //fa_xiluxc_vip_order
        $data['user_id']        = $userId;
        $data['platform']       = 'wxmini';
        $data['order_no']       = time();
        $data['pay_type']       = 1;
        $data['pay_fee']        = $vip['salesprice'];
        $data['pay_status']     = 'unpaid';
        $data['vip_id']         = $id;
        $data['vip_salesprice'] = $vip['salesprice'];
        
        $data['vip_name']       = $vip['name'];
        $data['vip_days']       = $vip['days'];
        $data['vip_privilege']  = $vip['privilege'];
        $data['createtime']     = time();
        $data['admin_id']       = 0;
        $res=Db::name('xiluxc_vip_order')->insert($data);
     
        
         $money = $vip['salesprice'];
        $uid =$userId;
        $notify_url ='https://che.damaii.cn/api/index/notifya';
        
        // $userid=$_POST['uid'];
        // $mon
        // var_dump($money);
        // var_dump($uid);die;
        $url='https://pay.prod.6jqb.com/api/pay/unifiedOrder';
         $third=Db::name('third')->where('user_id',$userId)->find();
         $can['openid']=$third['openid'];
        $can['isSubOpenId']=1;
        $datas['channelExtra']=json_encode($can);
        $datas['mchNo']='M1755156100';
        $datas['appId']='689d9c5ce4b0c2ccfd6f3a3f';
        $datas['mchOrderNo']= $data['order_no'];
        $datas['wayCode']='WX_JSAPI';
        $datas['amount']=$money*100;
        $datas['currency']='cny';
        $datas['subject']='开通VIP';
        $datas['body']=$vip['name'];
        $datas['reqTime']=time();
        $datas['version']='1.0';
        $datas['signType']='MD5';
        $datas['notifyUrl']=$notify_url;
        $datas['returnUrl']='https://che.damaii.cn/web/pages/profile/payment';
       
        $privateKey='wt1DoX567CaEQVYiLMU2czan9MSWYYseZ4ajFAkL4mBgMVUQNZzn4FjUFsX6w8QiVwzdJwhxAXAWhCzXQqcLQtiNrXXvBc9phGOzjlQfExpIAHmZIoIrr3Hv5aluToGj';
        // $notify_url ='https://che.damaii.cn/api/index/notify';
        // $data['mchNo']='M1743651922';
        // $data['appId']='67ee069fe4b041c770b71598';
        // $data['mchOrderNo']=$ordersn;
        // $data['wayCode']=$a;
        // $data['amount']=$price*100;
        // $data['currency']='cny';
        // $data['subject']='咨询服务';
        // $data['body']='咨询服务';
        // $data['reqTime']=time();
        // $data['version']='1.0';
        // $data['signType']='MD5';
        // $data['returnUrl']='https://qiu.damaii.cn/h5/#/pages/payment/payment?id='.$id;
        // $data['notifyUrl']=$notify_url;
        $datas['sign'] = $this->generateSign($datas,$privateKey);
        
        $url='https://pay.prod.6jqb.com/api/pay/unifiedOrder';
        $sss=$this->sendPostRequest($url,$datas);
        //fa_xiluxc_recharge_order
        // $pa['orderid']=$ordersn;
   

        
        $array=json_decode($sss);
        // var_dump($sss);die;
        $this->success('数据',$array);
          
    }
    public function sendPostRequest( $url,  $data,  $contentType = 'form') {
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
    public function generateSign(array $data,  $privateKey) {
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
    /**
     * 优惠券领取
     * @throws \think\exception\DbException
     */
    public function receive(){
        $couponId = $this->request->post('coupon_id');
        $time = Config::getTodayTime();
        $couponModel = new CouponModel;
        Db::startTrans();
        try {
            $row = $couponId ? $couponModel->where('id',$couponId)
                ->normal()
                ->where('use_start_time','elt',$time)
                ->where('use_end_time','egt',$time)
                ->lock(true)
                ->find() : null;
            if(!$row){
                throw new Exception("优惠券不存在或已下架");
            }
            if($row->max_count<=$row->receive_count){
                throw new Exception("优惠券已领完");
            }
            $userId = $this->auth->id;
            if(UserCoupon::isReceive($userId,$row->id)){
                throw new Exception("不要重复领取");
            }
            $ret = UserCoupon::create([
                'user_id'   =>  $userId,
                'coupon_id' =>  $row->id,
                'shop_id'   =>  $row->shop_id,
            ]);
            $ret2 = $row->save(['receive_count'=>Db::raw("receive_count+1")]);
            if($ret2 === false){
                throw new Exception("领取失败");
            }
        }catch (Exception|PDOException|Exception $e){
            Db::rollback();
            $this->error($e->getMessage());
        }
        Db::commit();
        $this->success('领取成功',$row);
    }

}