<?php

namespace addons\xiluxc;

use app\common\library\Menu;
use app\common\model\xiluxc\brand\ShopAccount;
use app\common\model\xiluxc\brand\ShopService;
use app\common\model\ScoreLog as Uscorelog;
use app\common\model\User;
use app\common\model\xiluxc\brand\ShopUser;
use app\common\model\xiluxc\brand\Shopvip;
use app\common\model\xiluxc\current\Config;
use app\common\model\xiluxc\finance\ShopWithdraw;
use app\common\model\xiluxc\finance\Withdraw;
use app\common\model\xiluxc\MoneyLog;
use app\common\model\xiluxc\order\Order;
use app\common\model\xiluxc\ScoreLog;
use app\common\model\xiluxc\service\Service;
use app\common\model\xiluxc\user\UserAccount;
use app\common\model\xiluxc\user\UserPackage;
use app\common\model\xiluxc\user\UserPackageService;
use app\common\model\xiluxc\user\UserShopAccount;
use app\common\model\xiluxc\UserMessage;
use think\Addons;
use think\Db;
use think\db\Expression;
use think\Exception;
use think\exception\PDOException;
use app\common\model\xiluxc\Divide;
use think\Log;
use think\Request;
use function fast\array_get;

/**
 * 插件
 */
class Xiluxc extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = include ADDON_PATH . 'xiluxc' . DS . 'data' . DS . 'menu.php';
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("xiluxc");
        return true;
    }
    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable("xiluxc");
        return true;
    }
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("xiluxc");
        return true;
    }

    /**
     * 添加命令行扩展
     */
    public function appInit()
    {
        // 公共方法
        require_once __DIR__ . '/helper.php';
    }

    /**
     * 会员中心边栏后
     * @return mixed
     * @throws \Exception
     */
    public function userSidenavAfter()
    {
        $request = Request::instance();
        $actionname = strtolower($request->action());
        $data = [
            'actionname' => $actionname
        ];
        return $this->fetch('view/hook/user_sidenav_after', $data);
    }

    /**
     * 添加门店会员
     */
    public function xiluxcShopUser($params){
        $order = $params['order'] ?? '';
        if($order){
            $where = [];
            if($order->brand_id){
                $where['brand_id'] = $order->brand_id;
            }else{
                $where['shop_id'] = $order->shop_id;
            }
            $shopUser = ShopUser::where("user_id",$order->user_id)->where($where)->count();
            if(!$shopUser){
                ShopUser::create([
                    'user_id'   =>  $order->user_id,
                    'shop_id'   =>  $order->shop_id,
                    'brand_id'  =>  $order->brand_id
                ]);
            }
        }
        return true;
    }

    /**
     * 会员卡订单佣金计算
     * @param Order $order
     */
    public function xiluxcVipCalculate($params){
        $type = $params['type'] ?? '';
        $order = $params['order'] ?? '';
        $divide = Divide::where('order_id',$order->id)->where('type',$type)->find();
        if($divide){
            return false;
        }

        //订单金额
        $order_money = $order->pay_fee;
        $distribution = Config::getMyConfig("distribution");
        #门店钱包
        $shopAccount = ShopAccount::addAccount($order->shop_id);
        $platform_rate = $shopAccount->vip_rate ? $shopAccount->vip_rate : 0;
        $platform_money = bcmul($order_money,bcdiv($platform_rate,100,2),2);
        $shop_money = bcsub($order_money,$platform_money,2);
        #一级分销金额
        $userAccount = UserAccount::where('user_id',$order->user_id)->field("id,first_user_id,second_user_id")->find();
        if($distribution['distribution_type'] == 2 && array_get($distribution,'distribution_status')==1){
            $vip = Shopvip::get($order->vip_id);
        }
        $first_user_id = $first_rate = $first_money = 0;
        if($userAccount->first_user_id > 0 && array_get($distribution,'distribution_status')==1){
            $first_user_id = $userAccount->first_user_id;
            if($distribution['distribution_type'] == 1){
                $first_rate = array_get($distribution,'distribution_one_rate') ?? 0;
            }else{
                $first_rate = array_get($distribution,'distribution_status')==1?$vip['distribution_one_rate']??0 : 0;
            }
            $first_money = bcmul($first_rate,bcdiv($order_money, 100,2),2);
        }
        #二级分销金额
        $second_user_id = $second_rate =$second_money = 0;
        if($userAccount->second_user_id > 0 && array_get($distribution,'distribution_status')==1){
            $second_user_id = $userAccount->second_user_id;
            if($distribution['distribution_type'] == 1){
                $second_rate = array_get($distribution,'distribution_two_rate') ?? 0;
            }else{
                $second_rate =  array_get($distribution,'distribution_status')==1?$vip['distribution_two_rate']??0 : 0;
            }

            $second_money = bcmul($second_rate,bcdiv($order_money, 100,2),2);
        }
        try {
            $divide = Divide::create([
                'type'          =>  $type,
                'user_id'       =>  $order->user_id,
                'order_id'      =>  $order->id,
                'order_money'   =>  $order_money,
                'shop_money'    =>  $shop_money,
                'platform_rate' =>  $platform_rate,
                'platform_money'=>  $platform_money,
                'first_user_id' =>  $first_user_id,
                'first_rate'    =>  $first_rate,
                'first_money'   =>  $first_money,
                'second_user_id'=>  $second_user_id,
                'second_rate'   =>  $second_rate,
                'second_money'  =>  $second_money,
                'shop_id'       =>  $order->shop_id,
                'status'        => '1'
            ]);
            //用户下订单-门店佣金
            MoneyLog::create([
                'type'          =>  MoneyLog::TYPE_SHOP, //门店金额
                'event'         =>  $type,
                //'user_id'       =>  $shopAccount->user_id,
                'shop_id'       =>  $shopAccount->shop_id,
                'divide_id'     =>  $divide->id,
                'order_id'      =>  $order->id,
                'before'        =>  $shopAccount->money,
                'money'         =>  $divide->shop_money,
                'after'         =>  bcadd($shopAccount->money,$divide->shop_money,2),
                'memo'          => '门店会员收入',
                'status'        => 0,
                'extra' => json_encode([
                    'order_id'      =>  $order->id,
                    'user_id'       =>  $order->user_id,
                    'shop_id'       =>  $order->shop_id,
                    'order_no'      =>  $order->order_no,
                    'pay_fee'       =>  $order->pay_fee
                ])
            ]);
            //一级佣金
            if($first_user_id>0 && $first_money>0){
                MoneyLog::create([
                    'type'          =>  MoneyLog::TYPE_COMMISSION,
                    'user_id'       =>  $first_user_id,
                    'divide_id'     =>  $divide->id,
                    'order_id'      =>  $order->id,
                    'money'         =>  $first_money,
                    'memo'          => '门店会员购买反佣',
                    'status'        => 0,
                    'extra' => json_encode([
                        'order_id'      =>  $order->id,
                        'user_id'       =>  $order->user_id,
                        'shop_id'       =>  $order->shop_id,
                        'order_no'      =>  $order->order_no,
                        'pay_fee'       =>  $order->pay_fee
                    ])
                ]);
            }
            //二级佣金
            if($second_user_id>0 && $second_money>0){
                MoneyLog::create([
                    'type'          =>  MoneyLog::TYPE_COMMISSION,
                    'user_id'       =>  $second_user_id,
                    'divide_id'     =>  $divide->id,
                    'order_id'      =>  $order->id,
                    'money'         =>  $second_money,
                    'memo'          => '门店会员购买反佣',
                    'status'        => 0,
                    'extra' => json_encode([
                        'order_id'      =>  $order->id,
                        'user_id'       =>  $order->user_id,
                        'shop_id'       =>  $order->shop_id,
                        'order_no'      =>  $order->order_no,
                        'pay_fee'       =>  $order->pay_fee
                    ])
                ]);
            }
            $this->xiluxcUnfreeze($params);
        }catch (Exception|PDOException $e){

        }
    }

    /**
     * 服务核销成功佣金计算
     * @param Order $order
     */
    public function xiluxcServiceCalculate($params){
        $type = $params['type'] ?? '';
        $order = $params['order'] ?? '';
        $divide = Divide::where('order_id',$order->id)->where('type',$type)->find();
        if($divide){
            return false;
        }
        /*************************************/
        if($order->pay_type == 3){
            $userPackage = UserPackage::where('id',$order->user_package_id)->find();
            if(!$userPackage){
                return;
            }
            $userPackageService = UserPackageService::where('user_package_id',$userPackage->id)->where('service_price_id',$order->order_item->service_price_id)->find();
            if(!$userPackageService){
                return;
            }
            //核销金额
            $all_amount = 0; //所有子服务总价格
            foreach ($userPackage->package_service as $item){
                $all_amount += $item['salesprice']*$item['total_count'];
            }
            //当前服务所占比例
            $percent = bcdiv($userPackageService['salesprice'] * $userPackageService['total_count'],$all_amount,2);
            //当前服务总金额
            $service_amount = bcmul($percent,$userPackage->order_amount,2);
            $order_amount = bcdiv($service_amount,$userPackageService->total_count,2);
        }else{
            $order_amount = $order->shop_fee;
        }
        /*****************************/
        //订单金额
        $order_money = $order_amount;
        $distribution = Config::getMyConfig("distribution");
        #门店钱包
        $shopAccount = ShopAccount::addAccount($order->shop_id);
        $platform_rate = $shopAccount->rate ? $shopAccount->rate : 0;
        $platform_money = bcmul($order_money,bcdiv($platform_rate,100,2),2);
        $shop_money = bcsub($order_money,$platform_money,2);
        #一级分销金额
        $userAccount = UserAccount::where('user_id',$order->user_id)->field("id,first_user_id,second_user_id")->find();
        if($distribution['distribution_type'] == 2 && array_get($distribution,'distribution_status')==1){
            $shopService = ShopService::get($order->order_item->data_id);
            $service = $shopService ? Service::get($shopService->service_id) : null;
        }
        $first_user_id = $first_rate = $first_money = 0;
        if($userAccount->first_user_id > 0 && array_get($distribution,'distribution_status')==1){
            $first_user_id = $userAccount->first_user_id;
            if($distribution['distribution_type'] == 1 && array_get($distribution,'distribution_status')==1){
                $first_rate = array_get($distribution,'distribution_one_rate') ?? 0;
            }else{
                $first_rate = array_get($distribution,'distribution_status')==1?$service['distribution_one_rate']??0 : 0;
            }
            $first_money = bcmul($first_rate,bcdiv($order_money, 100,2),2);
        }
        #二级分销金额
        $second_user_id = $second_rate =$second_money = 0;
        if($userAccount->second_user_id > 0 && array_get($distribution,'distribution_status')==1){
            $second_user_id = $userAccount->second_user_id;
            if($distribution['distribution_type'] == 1  && array_get($distribution,'distribution_status')==1){
                $second_rate = array_get($distribution,'distribution_two_rate') ?? 0;
            }else{

                $second_rate =  array_get($distribution,'distribution_status')==1?$service['distribution_two_rate']??0 : 0;
            }

            $second_money = bcmul($second_rate,bcdiv($order_money, 100,2),2);
        }
        try {
            $divide = Divide::create([
                'type'          =>  $type,
                'user_id'       =>  $order->user_id,
                'order_id'      =>  $order->id,
                'order_money'   =>  $order_money,
                'shop_money'    =>  $shop_money,
                'platform_rate' =>  $platform_rate,
                'platform_money'=>  $platform_money,
                'first_user_id' =>  $first_user_id,
                'first_rate'    =>  $first_rate,
                'first_money'   =>  $first_money,
                'second_user_id'=>  $second_user_id,
                'second_rate'   =>  $second_rate,
                'second_money'  =>  $second_money,
                'shop_id'       =>  $order->shop_id,
                'status'        => '1'
            ]);
            //用户下订单-门店佣金
            MoneyLog::create([
                'type'          =>  MoneyLog::TYPE_SHOP, //门店金额
                'event'         =>  $type,
                //'user_id'       =>  $shopAccount->user_id,
                'shop_id'       =>  $shopAccount->shop_id,
                'divide_id'     =>  $divide->id,
                'order_id'      =>  $order->id,
                'before'        =>  $shopAccount->money,
                'money'         =>  $divide->shop_money,
                'after'         =>  bcadd($shopAccount->money,$divide->shop_money,2),
                'memo'          => '门店服务核销成功收入',
                'status'        => 0,
                'extra' => json_encode([
                    'order_id'      =>  $order->id,
                    'user_id'       =>  $order->user_id,
                    'shop_id'       =>  $order->shop_id,
                    'order_no'      =>  $order->order_no,
                    'pay_fee'       =>  $order->pay_fee
                ])
            ]);
            //一级佣金
            if($first_user_id>0 && $first_money>0){
                MoneyLog::create([
                    'type'          =>  MoneyLog::TYPE_COMMISSION,
                    'event'         =>  $type,
                    'user_id'       =>  $first_user_id,
                    'divide_id'     =>  $divide->id,
                    'order_id'      =>  $order->id,
                    'money'         =>  $first_money,
                    'memo'          => '服务核销成功反佣',
                    'status'        => 0,
                    'extra' => json_encode([
                        'order_id'      =>  $order->id,
                        'user_id'       =>  $order->user_id,
                        'shop_id'       =>  $order->shop_id,
                        'order_no'      =>  $order->order_no,
                        'pay_fee'       =>  $order->pay_fee
                    ])
                ]);
            }
            //二级佣金
            if($second_user_id>0 && $second_money>0){
                MoneyLog::create([
                    'type'          =>  MoneyLog::TYPE_COMMISSION,
                    'event'         =>  $type,
                    'user_id'       =>  $second_user_id,
                    'divide_id'     =>  $divide->id,
                    'order_id'      =>  $order->id,
                    'money'         =>  $second_money,
                    'memo'          => '服务核销成功反佣',
                    'status'        => 0,
                    'extra' => json_encode([
                        'order_id'      =>  $order->id,
                        'user_id'       =>  $order->user_id,
                        'shop_id'       =>  $order->shop_id,
                        'order_no'      =>  $order->order_no,
                        'pay_fee'       =>  $order->pay_fee
                    ])
                ]);
            }
            $this->xiluxcUnfreeze($params);
        }catch (Exception|PDOException $e){

        }
    }

    /**
     * 佣金解冻成功
     * 核销成功，则佣金解冻
     */
    protected function xiluxcUnfreeze($params){
        $type = $params['type'] ?? '';
        $order = $params['order'] ?? '';
        $divide = Divide::where('order_id',$order->id)->where('type',$type)->find();
        if(!$divide || $divide->status != '1'){
            return false;
        }
        #1.佣金解冻
        $divide->status = '3';
        $divide->unfreezetime = time();
        $divide->save();
        #2.企业产品销售额发放
        $shopAccount = ShopAccount::addAccount($order->shop_id);
        $shopAccount->total_money = new Expression("total_money+".$divide->shop_money);
        $shopAccount->money = new Expression("money+".$divide->shop_money);
        $shopAccount->save();
        #3.个人佣金发放
        if($divide->first_user_id && $divide->first_money){
            $firstAccount = UserAccount::addAccount($divide->first_user_id);
            $firstAccount->total_money = new Expression("total_money+".$divide->first_money);
            $firstAccount->money = new Expression("money+".$divide->first_money);
            $firstAccount->save();
        }
        if($divide->second_user_id && $divide->second_money){
            $secondAccount = UserAccount::addAccount($divide->second_user_id);
            $secondAccount->total_money = new Expression("total_money+".$divide->second_money);
            $secondAccount->money = new Expression("money+".$divide->second_money);
            $secondAccount->save();
        }
        #4.公司/个人佣金解冻
        MoneyLog::where('event', $type)->where('divide_id',$divide->id)->update(['status'=>1]);
        return true;
    }

    /**
     * 新增积分
     * @param $params
     */
    public function xiluxcAddScore($params){
        $type = $params['type'] ?? '';
        $order = $params['order'] ?? '';
        $score = $order ? floor($order->pay_fee) : 0;
        if($score<=0){
            return false;
        }
        $scoreModel = new ScoreLog();
        $memoList = $scoreModel->getTypeMemoList();
        $userAccount = UserAccount::addAccount($order->user_id);
        $scoreModel->save([
            'event'         =>  $type,
            'user_id'       =>  $order->user_id,
            'shop_id'       =>  $order->shop_id,
            'order_id'      =>  $order->id,
            'before'        =>  $userAccount->points,
            'score'         =>  $score,
            'after'         =>  bcadd($userAccount->points,$score),
            'memo'          => $memoList[$type] ?? '未知',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $order->id,
                'user_id'       =>  $order->user_id,
                'shop_id'       =>  $order->shop_id,
                'order_no'      =>  $order->order_no,
                'pay_fee'       =>  $order->pay_fee,
            ])
        ]);
        $userAccount->save(["total_points"=>Db::raw("total_points+".$score),"points"=>Db::raw("points+".$score)]);
    }

    /**
     * 抵扣积分-减少
     * @param $params
     */
    public function xiluxcReduceScore($params){
        $type = $params['type'] ?? '';
        $order = $params['order'] ?? '';
        $score = $order ? floor($order->points) : 0;
        if($score<=0){
            return false;
        }
        $scoreModel = new ScoreLog();
        $memoList = $scoreModel->getTypeMemoList();
        $userAccount = UserAccount::addAccount($order->user_id);
        $scoreModel->save([
            'event'         =>  $type,
            'user_id'       =>  $order->user_id,
            'shop_id'       =>  $order->shop_id,
            'order_id'      =>  $order->id,
            'before'        =>  $userAccount->points,
            'score'         =>  '-'.$score,
            'after'         =>  bcsub($userAccount->points,$score),
            'memo'          => $memoList[$type] ?? '未知',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $order->id,
                'user_id'       =>  $order->user_id,
                'shop_id'       =>  $order->shop_id,
                'order_no'      =>  $order->order_no,
                'pay_fee'       =>  $order->pay_fee,
            ])
        ]);
        $userAccount->save(["points"=>Db::raw("points-".$score)]);
    }

    /**
     * 充值
     */
    public function xiluxcRechargeSuccess($rechargeOrder){
        $userShopAccount = UserShopAccount::addAccount($rechargeOrder->user_id,$rechargeOrder->shop_id);
        #1.查询是否已给过
        $log = MoneyLog::where("type",1)->where("order_id",$rechargeOrder->id)->where('event','recharge')->find();
        if($log){
            return;
        }
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_USER_BALANCE, //余额
            'event'         =>  'recharge',
            'user_id'       =>  $rechargeOrder->user_id,
            'shop_id'       =>  $rechargeOrder->shop_id,
            'divide_id'     =>  0,
            'order_id'      =>  $rechargeOrder->id,
            'before'        =>  $userShopAccount->money,
            'money'         =>  $rechargeOrder->recharge_total_money,
            'after'         =>  bcadd($userShopAccount->money,$rechargeOrder->recharge_total_money),
            'memo'          => '余额充值',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $rechargeOrder->id,
                'user_id'       =>  $rechargeOrder->user_id,
                'shop_id'       =>  $rechargeOrder->shop_id,
                'order_no'      =>  $rechargeOrder->order_no,
                'pay_fee'       =>  $rechargeOrder->pay_fee,
                'recharge_money'=>  $rechargeOrder->recharge_money,
                'recharge_extra_money'=>  $rechargeOrder->recharge_extra_money,
            ])
        ]);
        
        $user = User::get($rechargeOrder->user_id);
        
        $after = bcadd($user->score, $rechargeOrder->recharge_total_money, 2);
        
        $user->score = $after;
        $user->save();
        
        Uscorelog::create([
            'user_id' => $rechargeOrder->user_id,
            'score'   => $rechargeOrder->recharge_total_money,
            'before'  => $user->score,
            'after'   => $after,
            'memo'    => '充值赠送',
        ]);

        $userShopAccount->save([
            "total_money" => Db::raw(
                "total_money+".$rechargeOrder->recharge_total_money),
                "money"=>Db::raw("money+".$rechargeOrder->recharge_total_money)
            ]);
    }

    /**
     * 余额支付
     */
    public function xiluxcMoneyPay($order){   //余额支付

//        $userShopAccount = UserShopAccount::addAccount($order->user_id,$order->shop_id);

        $UserAccount = UserAccount::Where('user_id',$order->user_id)->find();
//        dump($user);die();
//        dump($userShopAccount);die();
        #1.查询是否已经添加过
        $log = MoneyLog::where("type",1)->where("order_id",$order->id)->where('event','payment')->find();
        if($log){
            throw new Exception("不要重复支付");
        }
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_USER_BALANCE, //余额
            'event'         =>  'payment',
            'user_id'       =>  $order->user_id,
            'shop_id'       =>  $order->shop_id,
            'divide_id'     =>  0,
            'order_id'      =>  $order->id,
            'before'        =>   $UserAccount->money,
            'money'         =>  '-'.$order->pay_fee,
            'after'         =>  bcsub( $UserAccount->money,$order->pay_fee,2),
            'memo'          => '余额支付',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $order->id,
                'user_id'       =>  $order->user_id,
                'shop_id'       =>  $order->shop_id,
                'order_no'      =>  $order->order_no,
                'pay_fee'       =>  $order->pay_fee
            ])
        ]);

        $data = [
            'user_id'         => $order->user_id,
            'money'           => '-'.$order->pay_fee,
            'before'          => $UserAccount['money'],
            'after'          => bcsub($UserAccount['money'],$order->pay_fee,2),
            'memo'            => '余额支付消费',
            'createtime'      => time(),
        ];
        Db::name('user_money_log')->insert($data);
        return  $UserAccount->save([
            "money" => Db::raw("money - " . $order->pay_fee),
            "lj"    => Db::raw("lj + " . $order->pay_fee)
        ]);
    }

    /**
     * 退款成功余额返还
     */
    public function xiluxcRefundSuccess($aftersale){
//        dump($aftersale);die();
//        $userShopAccount = UserShopAccount::addAccount($aftersale->user_id,$aftersale->shop_id);
        $UserAccount = UserAccount::where('user_id',$aftersale->user_id)->find();
        #1.查询是否已给过
        $log = MoneyLog::where("type",1)->where("order_id",$aftersale->id)->where('event','aftersale')->find();
        if($log){
            return;
        }
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_USER_BALANCE, //余额
            'event'         =>  'aftersale',
            'user_id'       =>  $aftersale->user_id,
            'shop_id'       =>  $aftersale->shop_id,
            'divide_id'     =>  0,
            'order_id'      =>  0,
            'before'        =>  $UserAccount->money,
            'money'         =>  $aftersale->refund_money,
            'after'         =>  bcadd($UserAccount->money,$aftersale->refund_money,2),
            'memo'          => '退款成功返还',
            'status'        => 0,
            'extra' => json_encode([
                'aftersale_id'  =>  $aftersale->id,
                'user_id'       =>  $aftersale->user_id,
                'shop_id'       =>  $aftersale->shop_id,
                'order_no'      =>  $aftersale->order_no,
            ])
        ]);
        $data = [
            'user_id'         => $aftersale->user_id,
            'money'           => $aftersale->refund_money,
            'before'          => $UserAccount['money'],
            'after'          => bcadd($UserAccount['money'],$aftersale->refund_money,2),
            'memo'            => '退款成功返还',
            'createtime'      => time(),
        ];
        Db::name('user_money_log')->insert($data);
        $UserAccount->save(["money"=>Db::raw("money+".$aftersale->refund_money)]);
    }


    /*********************提现****************/
    /**
     * 佣金提现
     */
    public function xiluxcWithdraw(Withdraw $withdraw){
        $account = UserAccount::get(['user_id'=>$withdraw->user_id]);
        if(!$account || $account->money<$withdraw->money){
            throw new Exception("余额不足");
        }
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_COMMISSION, //余额
            'event'         =>  'withdraw',
            'user_id'       =>  $withdraw->user_id,
            'shop_id'       =>  0,
            'divide_id'     =>  0,
            'withdraw_id'   =>  $withdraw->id,
            'before'        =>  $account->money,
            'money'         =>  '-'.$withdraw->money,
            'after'         =>  bcsub($account->money,$withdraw->money),
            'memo'          => '提现',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $withdraw->id,
                'user_id'       =>  $withdraw->user_id,
                'order_no'      =>  $withdraw->order_no,
            ])
        ]);
        //扣减金额
        $account->money = new Expression("money-".$withdraw->money);
        $account->save();
    }

    /**
     * 佣金提现拒绝
     */
    public function xiluxcWithdrawRefuse(Withdraw $withdraw){
        $account = UserAccount::addAccount($withdraw->user_id);
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_COMMISSION, //余额
            'event'         =>  'withdraw_refuse',
            'user_id'       =>  $withdraw->user_id,
            'shop_id'       =>  0,
            'divide_id'     =>  0,
            'withdraw_id'   =>  $withdraw->id,
            'before'        =>  $account->money,
            'money'         =>  $withdraw->money,
            'after'         =>  bcadd($account->money,$withdraw->money),
            'memo'          => '提现拒绝返还',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $withdraw->id,
                'user_id'       =>  $withdraw->user_id,
                'order_no'      =>  $withdraw->order_no,
            ])

        ]);
        $account->money = new Expression("money+".$withdraw->money);
        $account->save();
        return true;
    }

    /**
     * 门店申请提现
     */
    public function xiluxcShopWithdraw(ShopWithdraw $withdraw){
        $shopAccount = ShopAccount::addAccount($withdraw->shop_id);
        //用户下订单-门店佣金
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_SHOP, //门店金额
            'event'         =>  "withdraw",
            'user_id'       =>  $withdraw->user_id,
            'shop_id'       =>  $shopAccount->shop_id,
            'divide_id'     =>  0,
            'withdraw_id'   =>  $withdraw->id,
            'before'        =>  $shopAccount->money,
            'money'         =>  '-'.$withdraw->money,
            'after'         =>  bcsub($shopAccount->money,$withdraw->money,2),
            'memo'          => '门店提现',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $withdraw->id,
                'user_id'       =>  $withdraw->user_id,
                'order_no'      =>  $withdraw->order_no,
            ])
        ]);
        //可提现余额减少
        $shopAccount->money = new Expression("money-".$withdraw->money);
        $shopAccount->withdraw_money = new Expression("withdraw_money+".$withdraw->money);
        $res = $shopAccount->allowField(['money','withdraw_money'])->save();
        return true;
    }
    /**
     * 门店提现拒绝
     */
    public function xiluxcShopWithdrawRefuse(ShopWithdraw $withdraw){
        $shopAccount = ShopAccount::addAccount($withdraw->shop_id);
        //拒绝
        MoneyLog::create([
            'type'          =>  MoneyLog::TYPE_SHOP, //门店金额
            'event'         =>  "withdraw_refuse",
            'user_id'       =>  $withdraw->user_id,
            'shop_id'       =>  $shopAccount->shop_id,
            'divide_id'     =>  0,
            'withdraw_id'   =>  $withdraw->id,
            'before'        =>  $shopAccount->money,
            'money'         =>  $withdraw->money,
            'after'         =>  bcadd($shopAccount->money,$withdraw->money,2),
            'memo'          => '门店提现拒绝',
            'status'        => 0,
            'extra' => json_encode([
                'order_id'      =>  $withdraw->id,
                'user_id'       =>  $withdraw->user_id,
                'order_no'      =>  $withdraw->order_no,
            ])
        ]);
        //可提现余额减少
        $shopAccount->money = new Expression("money+".$withdraw->money);
        $shopAccount->withdraw_money = new Expression("withdraw_money-".$withdraw->money);
        $res = $shopAccount->allowField(['money','withdraw_money'])->save();
        return true;
    }

    /***********************************消息******************************/
    /**
     * 服务购买成功
     */
    public function xiluxcServiceBuyMessage($order){
        $model = UserMessage::create([
            'user_id'      =>   $order->user_id,
            'type'         =>   UserMessage::TYPE_SERVICE_ORDER,
            'title'        =>   '预约成功消息',
            'content' => sprintf('您已成功预约"%s"的"%s"门店的"%s"服务，请注意使用时间', datetime($order->appoint_date,'Y-m-d'),$order->shop->name, $order->order_item['title'].'（'.$order->order_item['sku_text'].'）'),
            'extra' => [
                'order_id'  =>  $order->id,
                'shop_id'   =>  $order->shop_id,
                'image'     =>  $order->order_item['image'],
            ]
        ]);
        if($model->getError()) {
            Log::error('消息发送失败:'.$model->getError());
        }
    }
    /**
     * 套餐购买成功
     */
    public function xiluxcPackageBuyMessage($order){
        $model = UserMessage::create([
            'user_id'      =>   $order->user_id,
            'type'         =>   UserMessage::TYPE_PACKAGE_ORDER,
            'title'        =>   '套餐购买成功',
            'content' => sprintf('您已成功购买"%s"门店的"%s"套餐，感谢您的信任', $order->shop->name, $order->order_item['title']),
            'extra' => [
                'order_id'  =>  $order->id,
                'shop_id'   =>  $order->shop_id,
                'image'     =>  $order->order_item['image'],
            ]
        ]);
        if($model->getError()) {
            Log::error('消息发送失败:'.$model->getError());
        }
    }
    /**
     * 服务核销成功
     */
    public function xiluxcServiceVerifierMessage($params){
        $order = $params['order'] ?? '';
        $qrcode = $params['qrcode'] ?? '';
        $model = UserMessage::create([
            'user_id'      =>   $order->user_id,
            'type'         =>   UserMessage::TYPE_SERVICE_VERIFIER_SUCCESS,
            'title'        =>   '核销成功',
            'content' => sprintf('您购买的"%s"门店的"%s"服务，已核销成功', $order->shop->name, $order->order_item['title'].'（'.$order->order_item['sku_text'].'）'),
            'extra' => [
                'order_id'      =>  $order->id,
                'verifier_id'   =>  $qrcode->verifier_id,
                'image'   =>  $order->order_item['image'],
            ]
        ]);
        if($model->getError()) {
            Log::error('消息发送失败:'.$model->getError());
        }
    }

    /**
     * 套餐核销成功
     */
    public function xiluxcPackageVerifierMessage($params){
        $order = $params['order'] ?? '';
        $qrcode = $params['qrcode'] ?? '';
        $userPackage = $params['user_package'] ?? '';
        $model = UserMessage::create([
            'user_id'      =>   $order->user_id,
            'type'         =>   UserMessage::TYPE_PACKAGE_VERIFIER_SUCCESS,
            'title'        =>   '核销成功',
            'content' => sprintf('您已使用 的"%s"门店的"%s"套餐，核销了"%s"服务，核销成功，已扣除套餐中该服务1次', $order->shop->name,$userPackage->package_name, $order->order_item['title'].'（'.$order->order_item['sku_text'].'）'),
            'extra' => [
                'order_id'          =>  $order->id,
                'user_package_id'   =>  $userPackage->id,
                'verifier_id'       =>  $qrcode->verifier_id,
                'image'             =>  $order->order_item['image'],
            ]
        ]);
        if($model->getError()) {
            Log::error('消息发送失败:'.$model->getError());
        }
    }
}
