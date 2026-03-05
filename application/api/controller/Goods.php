<?php

namespace app\api\controller;
use think\Db;
use app\common\controller\Api;
use app\common\model\xiluxc\user\UserAccount;
use app\common\model\xiluxc\brand\Shopvip;

/**
 * 首页接口
 */
class Goods extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();

        // $this->member = $this->auth->getUserInfo();
        $this->model = model('addons\leescore\model\Cart');
    }
    /**
     * 首页
     *
     */
    public function index()
    {
        $pernum = 20;
        $pagenum = input('get.pagenum/d');
        $list = Db::name('leescore_goods')->field('*, CONCAT("http://tnk.com/", thumb) as thumb_url')->page($pagenum, $pernum)->select();

        $this->success('请求成功',$list);
    }
    public function deail(){
        $id = $this->request->post("id");
        $detail=Db::name('leescore_goods')->where('id',$id)->field('*, CONCAT("http://tnk.com/", thumb) as thumb_url')->find();
        $detail['thumb_urls'][0]=$detail['thumb_url'];
//        dump($detail);die();
        $this->success('请求成功',$detail);
    }
    
    public function orderlist(){
        // var_dump(1);die;
        
        $state = input('post.state');
        $where=[];
        $where['o.uid']= $this->auth->id;
        switch ($state) {
            case 'finished'://未支付
                $where['o.status']='3';
                break;
            case 'unpaid'://未支付
                $where['o.status']='0';
                break;
            case 'paid'://已支付
                $where['o.status']='1';
                break;
            case 'goods'://待收货
                $where['o.status']='2';
                break;
        }
        $statusMap = [
            -1 => '已关闭',
            0 => '未支付',
            1 => '已支付',
            2 => '待收货',
            3 => '已完成'
        ];
        // var_dump($where);die;
        
        $page = input('page/d', 1);
        
        $pageSize = input('page_size/d', 15);
        
        $query = Db::name('leescore_order o')
            ->field('o.*, 
                    g.goods_name,  
                    CONCAT("http://tnk.com/", g.goods_thumb) as goods_thumb, 
                    g.goods_id,
                    g.score,
                    g.money,
                    g.buy_num, 
                    a.region, 
                    FROM_UNIXTIME(o.createtime) as createtime_timestamp')
            ->join('leescore_order_goods g', 'o.id = g.order_id','left')
            ->join('leescore_order_address a', 'o.id = a.order_id','left')
            ->where($where);
        
        // 获取分页数据
        $orders = $query->page($page, $pageSize)->select();
        // var_dump($orders);die;
        // 获取总数（用于分页显示）
        $total = $query->removeOption('page')->count();
        
        foreach ($orders as &$order) {
            $order['status_text'] = $statusMap[$order['status']] ?? '未知状态';
        }
        $data['data']=$orders;
        $data['current_page']=$page;
        $data['page_size']=$pageSize;
        $data['total']=$total;
        $data['total_page']=ceil($total / $pageSize);
        $this->success('数据',$data);
        
    }
    
       //创建订单
    public function createOrderOne()
    {

        //读取插件配置（用于获取表前缀）
        // $add_config = get_addon_config('leescore');
        //商品ID
        // $id = input("post.id");
        $id=22;

        //商品数量
        $number = intval(input('post.number'));
        //dump(input("post."));
        $result = $this->isCheck($id, $number);
        //验证不通过
        if ($result['code'] != true) {
            $this->error($result['msg']);
        }

        //获取商品信息
        $goodsDetail = $this->goods->getGoodsDetail($id);
        var_dump($goodsDetail);die;
        //积分
        $scoreTotal = (int)($goodsDetail['scoreprice'] * $number);
        //货币
        $moneyTotal = round(($goodsDetail['money'] * $number), 2);

        //用户编号
        $data['uid'] = $this->auth->id;
        //生成订单号  表前缀 + 时间戳10位 + 微秒3位 + 随机数4位
        $sn = ucfirst(trim($add_config['order_prefix'])) . sprintf("%010d", (time() - 946656000)) . sprintf("%03d", (float)microtime() * 1000) . mt_rand(1000, 9999);
        $data['order_id'] = $sn;
        //收货地址
        //$data['address_id'] = '';
        //支付状态 0未付款 1已付款 2已退款
        $data['pay'] = 0;
        //订单状态： -2=驳回, -1=取消订单,0=未支付,1=已支付,2=已发货,3=已签收,4=退货中,5=已退款
        $data['status'] = 0;
        //支付时间
        //$data['paytime'] = time();
        //付款方式，score = 积分付款, weixin = 微信支付 , alipay = 支付宝 , paypal = paypal
        //$data['paytype'] = '';
        //删除订单（软删除）
        $data['isdel'] = 0;
        //订单创建时间
        $data['createtime'] = time();
        //需支付总积分
        $data['score_total'] = $scoreTotal;
        //需支付总款
        $data['money_total'] = $moneyTotal;

        Db::startTrans();
        try {
            //创建订单
            $this->model->insert($data);
            $lastid = $this->model->getLastInsID();
            $goods_data['order_id'] = $lastid;
            $goods_data['goods_id'] = $goodsDetail['id'];
            $goods_data['buy_num'] = $number;
            $goods_data['score'] = $goodsDetail['scoreprice'];
            $goods_data['money'] = $goodsDetail['money'];
            $goods_data['goods_name'] = $goodsDetail['name'];
            $goods_data['goods_thumb'] = $goodsDetail['thumb'];
            $goods_data['createtime'] = time();
            $goods_data['userid'] = $this->auth->id;

            //写入订单商品表
            $this->order_goods->insert($goods_data);

            $dec['id'] = $goodsDetail['id'];
            //减少库存
            $this->goods->where($dec)->setDec('stock', $number);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        $this->redirect(addon_url('leescore/order/postOrders', ['orderid' => $lastid]));
    }
    
    public function isCheck($id, $number)
    {
        //积分验证
        $info = $this->goods->where("id", $id)->find();
        $score_total = $info['scoreprice'] * $number;

        if ($this->auth->score < $score_total) {
            $result = ['code' => false, 'msg' => __("min score")];
            return $result;
        }

        // 余额验证
        if ($info['money'] > 0) {
            $money_total = $info['money'] * $number;
            if ($this->auth->money < $money_total) {
                $result = ['code' => false, 'msg' => __("余额不足")];
                return $result;
            }
        }

        //库存验证
        if ($info['stock'] < $number) {
            $result = ['code' => false, 'msg' => __("min stock")];
            return $result;
        }

        //最大购买值
        if ($info['max_buy_number'] != '-1') {
            $map['og.userid'] = $this->auth->id;
            $map['og.goods_id'] = $info['id'];
            $map['o.status'] = ['not in', '-1,-2'];
            $maxNum = Db::name('leescore_order_goods')
                ->alias('og')
                ->join('__LEESCORE_ORDER__ o', 'og.order_id = o.id')
                ->where($map)
                ->field('og.*, o.status')
                ->sum("og.buy_num");
            // $maxNum = Db::name('leescore_order_goods')->where($map)->count("buy_num");
            if ($maxNum >= $info['max_buy_number'] || ($maxNum + $number) > $info['max_buy_number']) {
                $result = ['code' => false, 'msg' => __("ex change max")];
                return $result;
            }
        }

        $result = ['code' => true, 'msg' => "Success"];
        return $result;
    }
    
    //收货地址
    public function order_address(){
        $user = $this->auth->getUser();
        $adddres=Db::name('leescore_address')->where('uid',$user['id'])->select();
        $this->success('请求成功',$detail);
    }
    
    public function buy(){
        $id = $this->request->post('id');
        $user = $this->auth->getUser();
        if(!$user){
            $this->success('1');
        }
        $adddres=Db::name('leescore_address')->where('uid',$user['id'])->select();
        if(!$adddres){
             $this->success('2');
        } 
        // var_dump($user);die;
        $data['goods_id']=$id;
        $data['uid']=$user['id'];
        $data['createtime']=time();
        $data['number']=1;
        $res=Db::name('leescore_cart')->insert($data);
        if($res){
            $this->success('添加成功');
        }else {
            $this->error('添加失败');
        }
    }
    public function buyinfo(){
        $user = $this->auth->getUser();
        $cartItems=Db::name('leescore_cart')->where('uid',$user['id'])->select();
        $addres=Db::name('leescore_address')->where('uid',$user['id'])->order('status', 'desc')->select();
         $totalScore = 0;
        $ordermorey=0;
        foreach ($cartItems as $key=> $item) {
            $goods = Db::name('leescore_goods')->where('id', $item['goods_id'])->find();
            $goods['thumb']='http://tnk.com/'.$goods['thumb'];
            $cartItems[$key]['goods']=$goods;
            $totalScore += $goods['scoreprice'] * $item['number'];
            $ordermorey += $goods['money'] * $item['number'];
        }
        $data['ordermorey']=$ordermorey;
        $data['goods']=$cartItems;
        $data['addres']=$addres;
        //计算合计金额
        
        // 获取购物车所有商品
        // $cartItems = Db::name('leescore_cart')->where('uid',$user['id'])->select();
       

        $data['score']=$totalScore;

        $this->success('数据列表',$data);
    }
    
    public function ordercera(){
        
        $time = time();
        // var_dump(1);die;
        $add_config = get_addon_config('leescore');
        
        
        $totalAmount= $this->request->post('totalAmount');
        
        
        $addres_id= $this->request->post('addres_id');
        
        $addres=Db::name('leescore_address')->where('id',$addres_id)->find();
        $user = $this->auth->getUser();
        $account = UserAccount::where('user_id', $this->auth->id)->find();
        
        // var_dump($user['money']);die;
        $ordermorey= $this->request->post('ordermorey');
           //用户编号
        $data['uid'] = $this->auth->id;
        //生成订单号  表前缀 + 时间戳10位 + 微秒3位 + 随机数4位
        $sn = ucfirst(trim($add_config['order_prefix'])) . sprintf("%010d", (time() - 946656000)) . sprintf("%03d", (float)microtime() * 1000) . mt_rand(1000, 9999);
        $data['order_id'] = $sn;
        //收货地址
        //$data['address_id'] = '';
        //支付状态 0未付款 1已付款 2已退款
        $data['pay'] = 0;
        //订单状态： -2=驳回, -1=取消订单,0=未支付,1=已支付,2=已发货,3=已签收,4=退货中,5=已退款
        $data['status'] = 0;
        //支付时间
        //$data['paytime'] = time();
        //付款方式，score = 积分付款, weixin = 微信支付 , alipay = 支付宝 , paypal = paypal
        //$data['paytype'] = '';
        //删除订单（软删除）
        $data['isdel'] = 0;
        //订单创建时间
        $data['createtime'] = time();
        //需支付总积分
        $data['score_total'] = $totalAmount;
        //需支付总款
        $data['money_total'] = $ordermorey;
    
        Db::name('leescore_order')->insert($data);
        $lastid = Db::name('leescore_order')->getLastInsID();
        //添加收货地址 fa_leescore_order_address
        $addresa['zip']='';
        $addresa['order_id']=$lastid;
        $addresa['mobile']=$addres['mobile'];
        $addresa['country']=$addres['country'];
        $addresa['region']=$addres['region'];
        $addresa['city']=$addres['city'];
        $addresa['xian']=$addres['xian'];
        // $addresa['uid']=$this->auth->id;;
        $addresa['address']=$addres['address'];
        $addresa['createtime']=time();
        $addresa['truename']=$addres['truename'];
        Db::name('leescore_order_address')->insert($addresa );
        $cart=Db::name('leescore_cart')->where('uid', $this->auth->id)->select();
        // var_dump($cart);die;
        foreach ($cart as $k => $v) {
            $numbers = $v['number'];
            //从购物车中获取商品信息
            $info=Db::name('leescore_goods')->where('id',$v['goods_id'])->find();
            // $info = $this->cart->with('goodsDetail')->where("id", $v)->find();
            $order_goods['order_id'] = $lastid;
            $order_goods['goods_id'] = $v['goods_id'];
            $order_goods['buy_num'] = $numbers;
            $order_goods['score'] = $info['scoreprice'];
            $order_goods['money'] = $info['money'];
            $order_goods['goods_name'] = $info['name'];
            $order_goods['goods_thumb'] = $info['thumb'];
            $order_goods['createtime'] = time();
            $order_goods['userid'] = $this->auth->id;
            // 减少商品库存
            Db::name('leescore_goods')->where("id", $v['goods_id'])->setDec('stock', $numbers);
            Db::name('leescore_order_goods')->insert($order_goods);
        }
        Db::name('leescore_cart')->where('uid',$this->auth->id)->delete();
        //余额支付+积分支付

        if($account['money'] < $ordermorey){
            $this->error('余额不足!');
        }

        if($account['points'] < $totalAmount){
            $this->error('积分不足!');
        }
        
        Db::startTrans();
        try{
            $res = Db::name('xiluxc_user_account')
                ->where('user_id', $this->auth->id)
                ->dec('points', $totalAmount)
                ->dec('money', $ordermorey)
                ->update();
            if($res){
                //更新订单状态
                DB::name('leescore_order')->where('id', $lastid)->update([
                    'status' => 1,
                    'trade_time' => $time,
                    'trade_score' => $totalAmount,
                    'trade_money' => $ordermorey
                ]);
                
                if ($ordermorey > 0) {
                        Db::name('user')->where('id', $this->auth->id)->setInc('monery', $ordermorey);
                        $money_log = [
                            'money'        => -$ordermorey,
                            'user_id'      => $this->auth->id,
                            'createtime'   => $time,
                            'before'       => $account['money'],
                            'after'        => bcsub($account['money'], $ordermorey, 2),
                            'memo'         => '兑换积分商品',
                        ];
                        
                        // $datas['order_id']=$order_id;
                        Db::name('user_money_log')->insert($money_log);
                    }
                    
                    if ($totalAmount > 0) {
                        $score_log = [
                            'score'        => -$totalAmount,
                            'user_id'      => $this->auth->id,
                            'createtime'   => $time,
                            'before'       => $account['points'],
                            'after'        => bcsub($account['points'], $totalAmount, 2),
                            'memo'         => '兑换积分商品',
                        ];
                        Db::name('user_score_log')->insert($score_log);
                    }
            }
            
             Db::commit();    
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success('支付成功');
    }
    
    public function scorelog($type,$money,$bianmonery,$user_id,$note,$order_id){
        $data['type']=$type;//1:积分  2：余额
        $data['money']=$money;
        $data['biangeng']=$bianmonery;
        $data['user_id']=$user_id;
        $data['createtime']=time();
        $data['note']=$note;
        $data['order_id']=$order_id;
        $res=Db::name('user_scorelog')->insert($data);

        return 1;
    }
    
    public function orderstatus(){
        $order_id= $this->request->post('order_id');
        $data['status']=3;
        $res=Db::name('leescore_order')->where('id',$order_id)->update($data);
        if($res){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
    
    public function orderinfo(){
        $order_id= $this->request->post('order_id');
        $where['o.id']=$order_id;
        $orders = Db::name('leescore_order o')
        ->field('o.*, 
            g.goods_name,  
            CONCAT("http://tnk.com/", g.goods_thumb) as goods_thumb, 
            g.goods_id,
            g.score,
            g.money,
            g.buy_num, 
            a.region, 
            a.truename,
            a.mobile,
            FROM_UNIXTIME(o.createtime) as createtime_timestamp,
            CASE o.status 
                WHEN -1 THEN "已关闭"
                WHEN 0 THEN "未支付" 
                WHEN 1 THEN "已支付" 
                WHEN 2 THEN "待收货" 
                WHEN 3 THEN "已完成" 
                ELSE "未知状态" 
            END as status_text')
        ->join('leescore_order_goods g', 'o.id = g.order_id')
        ->join('leescore_order_address a', 'o.id = a.order_id')
        ->where($where)
        ->find();
        $statusMap = [
            0 => '未支付',
            1 => '已支付',
            2 => '待收货',
            3 => '已完成'
        ];
        // foreach ($orders as &$order) {
        $orders['status_text'] = $statusMap[$orders['status']] ?? '未知状态';
        // }
        $this->success('操作成功',$orders);
        // var_dump($orders);die;
    }
    public function order_pay() {
        $time = time();
        $order_id = $this->request->post('order_id');
        $order_info = DB::name('leescore_order')->where('id',$order_id)->find();
        if (!$order_info) {
            $this->error('商品不存在');
        }
        $user = $this->auth->getUser();
              //余额支付+积分支付
        $account = UserAccount::where('user_id', $this->auth->id)->find();

        if($account['money'] < $order_info['money_total']){
            $this->error('余额不足!');
        }

        if($account['points'] < $order_info['score_total']){
            $this->error('积分不足!');
        }
        
        Db::startTrans();
        try{
        
            $res = Db::name('xiluxc_user_account')->where('user_id',$this->auth->id)
                ->dec('points',$order_info['score_total'])
                ->dec('money',$order_info['money_total'])
                ->update();
            if($res) {
                
                //更新订单状态
                DB::name('leescore_order')->where('id',$order_id)->update(['status' => 1]);
                
                if ($order_info['money_total'] > 0) {
                    Db::name('user')->where('id', $this->auth->id)->setInc('monery', $order_info['money_total']);
                    $money_log = [
                        'money'        => -$order_info['money_total'],
                        'user_id'      => $this->auth->id,
                        'createtime'   => $time,
                        'before'       => $account['money'],
                        'after'        => bcsub($account['money'], $order_info['money_total'], 2),
                        'memo'         => '兑换积分商品',
                    ];
                    
                    // $datas['order_id']=$order_id;
                    Db::name('user_money_log')->insert($money_log);
                }
                
                if ($order_info['score_total'] > 0) {
                    $score_log = [
                        'score'        => -$order_info['score_total'],
                        'user_id'      => $this->auth->id,
                        'createtime'   => $time,
                        'before'       => $account['points'],
                        'after'        => bcsub($account['points'], $order_info['score_total'], 2),
                        'memo'         => '兑换积分商品',
                    ];
                    Db::name('user_score_log')->insert($score_log);
                }
                
            }
            Db::commit();    
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        
        $this->success('操作成功');
    }
    
    public function shop_pay() {
        $actualAmount= $this->request->post('actualAmount');
        $discountRate= $this->request->post('discountRate');  
        $shop_id= $this->request->post('shop_id');  
        // var_dump($shop_id);die;
        $totalAmount= $this->request->post('totalAmount');
        $shop=Db::name('xiluxc_shop')->where('id',$shop_id)->find();
        $user = $this->auth->getUser();
        if(!$shop){
            $this->error('门店不存在');
        }
        
        // $vip = Shopvip::where('id', $user->level)->find();
        // if ($vip) {
            // $year = getYearDifference($user->viptime);
            // $money_log = Db::name('xiluxc_money_log')->where('user_id', $user->id)->where('type', 3)->find();
            // if ($vip->first_free == 1 && empty($money_log)) {
            //     $user->first_free = 1;
            // } else if ($vip->first_discount > 0 && empty($money_log)) {
            //     $user->vip_discount = $vip->first_discount;
            // } else {
            //     if ($year < 1) {
            //         $user->vip_discount = $vip->first_year_discount;
            //     } else if ($year == 1) {
            //         $user->vip_discount = $vip->second_year_discount;
            //     } else {
            //         $user->vip_discount = $vip->perpetual_discount;
            //     }
            // }
        // }
        
        
        $data['user_id']=$this->auth->id;
        $data['actualamount']=$actualAmount;
        $data['discountrate']=$discountRate;
        $data['shop_id']=$shop_id;
        $data['totalamount']=$totalAmount;
        $data['createtime']=time();
        $data['status']=0;
        
        
        
        
        $res=Db::name('shop_pay')->insert($data);
        $lastid = Db::name('leescore_order')->getLastInsID();
        
        $account = UserAccount::where('user_id', $this->auth->id)->find();
        $before = $account['money'];
        if($account['money'] < $data['actualamount']){
            $this->error('余额不足');
        }
        
        $shop_account = Db::name('xiluxc_shop_account')->where('shop_id', $shop_id)->find();
        
        try {
            Db::startTrans();
            $account->money = bcsub($account['money'], $data['actualamount'], 2);
            // $account->save();
            
            $res = Db::name('user')->where('id',$this->auth->id)
                ->inc('monery', $totalAmount)
                ->update();
                
            if($res && $account->save()){
                
                //更新订单状态
                $order_status['status'] = 1;
                
                DB::name('shop_pay')->where('id', $lastid)->update($order_status);
                
                $datasa['type']         = 3;
                $datasa['money']        = $data['actualamount'];
                $datasa['event']        = 'shop_earnings';
                $datasa['user_id']      = $this->auth->id;
                $datasa['shop_id']      = $shop_id;
                $datasa['createtime']   = time();
                $datasa['before']       = $shop_account['money'];
                $datasa['after']        = bcadd($shop_account['money'], $data['actualamount'], 2);
                $datasa['memo']         = '门店收款';
                // $datas['order_id']=$order_id;
                Db::name('xiluxc_money_log')->insert($datasa);
                
                Db::name('user_money_log')->insert([
                    'user_id' => $this->auth->id,
                    'total_money' => -$totalAmount,
                    'money'   => $data['actualamount'],
                    'before'  => $before,
                    'after'   => $account->money,
                    'memo'    => '门店消费',
                    'createtime' => time()
                ]);
                
                //更新门店
                 $res = Db::name('xiluxc_shop_account')->where('shop_id',$shop_id)->inc('money', $data['actualamount'])->update();
                
                Db::commit();
            } else {
                Db::rollback();
            }
        } catch (\Exception $e) {
            Db::rollback();
            $this->error('支付错误:'. $e->getMessage());
            
        }
        $this->success('付款成功');
    }
    public function addres(){
        $addres=Db::name('leescore_address')->where('uid',$this->auth->id)->order('status', 'desc')->select();
        // var_dump($addres);DIE;
        $this->success('获取数据成功',$addres);
    }
    public function addresinfo(){
        $id= $this->request->post('id');
        $addres=Db::name('leescore_address')->where('id',$id)->find();
        $this->success('获取数据成功',$addres);
    }
    
    public function addresadd(){
        $id= $this->request->post('id');
        $data['address']=$this->request->post('address');;
        $data['mobile']=$this->request->post('mobile');;
        $data['region']=$this->request->post('region');;
        $data['truename']=$this->request->post('truename');;
        $data['status']=$this->request->post('status');;
        $res=Db::name('leescore_address')->where('id',$id)->update($data);
        if($res){
           $this->success('操作成功'); 
        }else{
            $this->error('操作失败');
        }
        // var_dump($data);die;
    }
      public function addresetit(){
        // $id= $this->request->post('id');
        $data['address']=$this->request->post('address');;
        $data['mobile']=$this->request->post('mobile');;
        $data['region']=$this->request->post('region');;
        $data['truename']=$this->request->post('truename');;
        $data['status']=$this->request->post('status');;
        $data['uid']=$this->auth->id;
        $res=Db::name('leescore_address')->insert($data);
        if($res){
           $this->success('操作成功'); 
        }else{
            $this->error('操作失败');
        }
        // var_dump($data);die;
    }
    
    public function cancel()
    {
        $order_id = $this->request->post('order_id');
        $order_info = DB::name('leescore_order')->where('id',$order_id)->find();
        if (empty($order_info) || $order_info['status'] != 0) {
            $this->error('非法访问');
        }
        
        Db::name('leescore_order')->where('id', $order_id)->update(['status' => -1]);
        $this->success('操作成功');
    }
}
