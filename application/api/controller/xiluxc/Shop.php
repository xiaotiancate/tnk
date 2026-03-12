<?php
namespace app\api\controller\xiluxc;
use app\common\controller\xiluxc\XiluxcApi;
use app\common\model\xiluxc\activity\Coupon;
use app\common\model\xiluxc\brand\BranchPackage;
use app\common\model\xiluxc\brand\Package;
use app\common\model\xiluxc\brand\Recharge;
use app\common\model\xiluxc\brand\Shop AS ShopModel;
use app\common\model\xiluxc\brand\ShopBranchService;
use app\common\model\xiluxc\brand\ShopService;
use app\common\model\xiluxc\brand\Shopvip;
use app\common\model\xiluxc\user\UserShopVip;
use think\Collection;
use think\Db;
use function fast\array_get;

/**
 * Class 门店
 * @package app\api\controller
 */
class Shop extends XiluxcApi
{
    protected $noNeedLogin = ['*'];


    /**
     * 门店列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(){
        $params = $this->request->param('');
        $params['city_id'] = $this->cityid;
        // var_dump($params);die;
        // var_dump(1);die;
        $this->success('',ShopModel::searchList($params));
    }

    /**
     * 服务列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function service_lists(){
        $params = $this->request->param('');
        //$params['city_id'] = $this->cityid;
        $model = new ShopBranchService();
        $where = [];
        if($q = array_get($params,'q')){
            $where['service.name'] = ["LIKE","%$q%"];
        }
        $sql = $model->where($model->getTable().".status",'normal')
            ->where("shop_service.status",'normal')
            ->join("xiluxc_service service",'service.id='.$model->getTable().'.service_id')
            ->join("xiluxc_shop_service shop_service",'shop_service.service_id='.$model->getTable().'.service_id')
            ->field($model->getTable().".shop_id")
            ->group($model->getTable().'.shop_id')
            ->whereLike('service.name',"%$q%")
            ->buildSql();
        $shop = new \app\common\model\xiluxc\brand\Shop();
        $shop->normal()->passed();
        $lat = array_get($params,'lat');
        $lng = array_get($params,'lng');
        if($lng && $lat){
            $shop->field("id,name,type,image,point,lat,lng,address,sales,(ST_DISTANCE(POINT(".$lng.", ".$lat."), POINT(lng, lat)) * 111195) AS distance");
        }else{
            $shop->field("id,name,type,image,point,lat,lng,address,sales");
        }
        if($districtId = array_get($params,'district_id')){
            $shop->where("district_id",$districtId);
        }
        $sort  = array_get($params,'sort','weigh');
        $order  = array_get($params,'order','desc');
        $lists = $shop->join([$sql=>'service'],'service.shop_id='.$shop->getTable().'.id','inner')
            ->order($sort,$order)
            ->order("point","desc")
            ->paginate(request()->param('pagesize',10))
            ->each(function ($row){
                $row->append(['image_text']);
                $row->shop_services = $row->shopServices();
                $row->distance = isset($row->distance) ? ($row->distance>=1000 ? bcdiv($row->distance,1000,1).'km' : bcadd($row->distance,0,1).'m') : '';
            });
        $this->success('',$lists);
    }


    /**
     * 门店详情
     */
    public function detail(){
        $params = $this->request->param('');

        $lat = array_get($params,'lat');
        $lng = array_get($params,'lng');
        if($lng && $lat){
            $field = "*,(ST_DISTANCE(POINT(".$lng.", ".$lat."), POINT(lng, lat)) * 111195) AS distance";
        }else{
            $field = "*";
        }
        $shopId = array_get($params,'shop_id');
//        dump($params);die();
        $shop = new ShopModel();
//        dump($shop);die();
        $shop = $shopId?$shop->field($field)->normal()->passed()->where('id',$shopId)->find():null;
//        dump($shop);die();
        if(!$shop){
            $this->error("门店不存在或已下架");
        }
//        dump(input());die();
        //服务与套餐
        $shop->append(['images_text','shop_tag']);
//        $shop->shop_services = $shop->shopServices();
//       $a = (new Collection($shop->shop_services))->toArray();



        $a = Db::name('xiluxc_shop_service')->where('shop_id',$shopId)->select();
//        dump($a);die();
        foreach ($a as &$v){
//            dump(11);die();
            $v['service'] = Db::name('xiluxc_service')->where('id',$v['service_id'])->find();
           $v['shop_service'] = Db::name('xiluxc_shop_service')->where('shop_id',$v['shop_id'])->where('service_id',$v['service_id'])->find();
           $v['shop_service']['image_text'] = 'http://tnk.com'.$v['shop_service']['image'];
        }
//
        $shop->shop_services = $a;



        $shop->shop_package = $shop->shopPackage;
        $shop->distance = isset($shop->distance) ? ($shop->distance>=1000 ? bcdiv($shop->distance,1000,1).'km' : bcadd($shop->distance,0,1).'m') : '';

        //优惠券
        $userId = $this->auth->id;
        $shop->setAttr("coupons",Coupon::getCoupons($shop->id,$userId));
        //充值金额
        $shop->setAttr("recharge",Recharge::getRecharge($shop));
        //会员卡
        $shop->setAttr("vip",Shopvip::getVip($shop));
        //是否会员
        $shop->setAttr("is_vip",$this->auth->isLogin()?UserShopVip::shopDetailVip($userId,$shop):['status'=>0]);
        $this->success('',$shop);

    }

    /**
     * 服务详情
     */
    public function service_detail(){
        $params = $this->request->param('');
        $shopServiceId = array_get($params,'id');
        $shopId = array_get($params,'shop_id');
        $shopBranchService = new ShopBranchService();
        $shopBranchService = $shopBranchService->normal()->where("shop_id",$shopId)->where("shop_service_id",$shopServiceId)->find();
//        dump(input());die();
        if(!$shopBranchService){
            $this->error("服务不存在或已下架");
        }
        $shopService = new ShopService();
        $shopService = $shopServiceId?$shopService->with(['service_price'])->normal()->where('id',$shopServiceId)->find():null;
        if(!$shopService || !$shopService->service){
            $this->error("服务不存在或已下架");
        }
        $shopService->setAttr("is_vip",$this->auth->isLogin()?UserShopVip::shopDetailVip($this->auth->id,$shopId):['status'=>0]);
        $this->success('',$shopService);

//
//        $parmas = input();
//        $shopService = Db::name('xiluxc_shop_service')->where('id',$parmas['id'])->where('shop_id',$parmas['shop_id'])->find();
//        $shopService['service'] = Db::name('xiluxc_service')->where('id',$shopService['service_id'])->find();
//        $shopService['image_text'] = 'http://tnk.com'.$shopService['image'];
//        $shopService['service']['image_text'] = 'http://tnk.com'.$shopService['service']['image'];
//        $shopService['service_price'] = Db::name('xiluxc_shop_service_price')->where('shop_service_id',$shopService['id'])->where('shop_id',$parmas['shop_id'])
//            ->where('service_id',$shopService['service_id'])->select();
//        $a = new ShopService();
//       $b = $a->setAttr("is_vip",$this->auth->isLogin()?UserShopVip::shopDetailVip($this->auth->id,$parmas['shop_id']):['status'=>0]);
//       $shopService['is_vip'] = $b['is_vip'];
//        $this->success('',$shopService);
//        dump($parmas);die();
    }

    /**
     * 套餐详情
     */
    public function package_detail(){
        $params = $this->request->param('');
        $packageId = array_get($params,'id');
        $shopId = array_get($params,'shop_id');
        $shopBranchPackage = new BranchPackage();
        $shopBranchPackage = $shopBranchPackage->normal()->where("shop_id",$shopId)->where("shop_package_id",$packageId)->find();
        if(!$shopBranchPackage){
            $this->error("套餐不存在或已下架");
        }
        $package = new Package();
        $package = $packageId?$package->normal()->where('id',$packageId)->find():null;
        if(!$package){
            $this->error("套餐不存在或已下架");
        }
        $package->relationQuery(['package_service2'=>function($q){
            $q->with(['service','service_price']);
        }]);
        $package->setAttr("is_vip",$this->auth->isLogin()?UserShopVip::shopDetailVip($this->auth->id,$shopId):['status'=>0]);
        $this->success('',$package);
    }

}