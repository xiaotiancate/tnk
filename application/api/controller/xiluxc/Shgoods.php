<?php


namespace app\api\controller\xiluxc;


use app\common\controller\xiluxc\XiluxcApi;
use app\common\model\LeescoreCategory;
use fast\Tree;
use think\Db;

class Shgoods extends XiluxcApi
{

    //分类
    public function category()
    {

        $this->model = new LeescoreCategory();
        $disabledIds = [];

        $cate = $this->model->getLeescoreCategory();

        $tree = Tree::instance()->init($cate, 'category_id');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [];
//        dump($this->categorylist);die();
//        foreach ($this->categorylist as $k => $v) {
//            $categorydata[$v['id']] = $v;
//        }

//        $this->assignconfig('options_val', $categorydata);

//        $this->opt = $categorydata;
////        dump($categorydata);die();
//        $this->view->assign('options', $categorydata);
//        dump($categorydata);die();
        $this->success('',$this->categorylist);

    }
    public function add()
    {
//        dump(input());die();
        $params = input(); // 获取前端传参
//        dump($params);die();
// 1. 预设需要检查的字段列表（属性名）
        $fields = [
            'name', 'category_id', 'paytype', 'type', 'status',
            'body', 'rule', 'thumb', 'pics', 'weigh', 'stock',
            'scoreprice', 'money', 'usenum', 'flag', 'max_buy_number',
            'fenxiao_status', 'firsttime', 'lasttime','id'
        ];

// 2. 初始化$data（放固定值）
//        dump($this->auth->id);die();
        $data = [
            'goodscate' => 1,
            'goodsdad'  => $this->auth->id,
            'createtime' => time(),
            'process'=>0
        ];

// 3. foreach遍历字段列表，筛选有值的字段
        foreach ($fields as $v) { // $v 就是每个字段名（比如 'rule'、'stock'）
            // 判断：前端传了该字段，且值不为空（可根据需求调整判断条件）
            if (isset($params[$v]) && $params[$v] !== '') {
                // 把「字段名=>字段值」加入$data
                $data[$v] = $params[$v];
            }
        }

// 后续事务逻辑（不变）
        Db::startTrans();
        try {
            if (isset($params['id']) && !empty($params['id'])){
                Db::name('leescore_goods')->where('id', $params['id'])->update($data);
                Db::commit();
                return json(['code'=>1, 'msg'=>'修改成功']);
            }else{
                $goodsId = Db::name('leescore_goods')->insert($data);
                Db::commit();
                return json(['code'=>1, 'msg'=>'添加成功']);
            }

//            dump($goodsId);die();
//                Db::commit();
            // 关键：把 "添加成功" 放到第三个参数（code的位置），数据放到第二个参数，第一个参数传空
            // 注释$this->success，改为：
//            return json(['code'=>1, 'msg'=>'添加成功']);
        }catch(\Exception $e) {
//            dump(1111);die();
            Db::rollback();

            $this->error('添加失败：'.$e->getMessage());
        }
    }
//编辑的时候查看的商品数据
    public function list(){
        $params = input();
        $goodsId= $params['id'];
        $goods = Db::name('leescore_goods')->where('id', $goodsId)->find();
        if (!$goods) {
            $this->error('商品不存在');
        }
        $this->success('',$goods);
    }
    //查看所有商品
    public function goodslist(){
        $params = input();
//        dump($params);die();
        $lessgoods = Db::name('leescore_goods')->where('goodsdad', $this->auth->id)->select();
//        dump($lessgoods);die();
        $this->success('',$lessgoods);

    }

    //上架下架
    public function status(){
        $params = input();
        $lessgoods = Db::name('leescore_goods')->where('id', $params['id'])->find();
        if ($lessgoods['status'] == 1) {
            $status = 2;
        } else {
            $status = 1;
        }
        Db::name('leescore_goods')->where('id', $params['id'])->update(['status'=>$status]);
        $this->success('修改成功',);
    }
    //删除商品
    public function delete(){
        $params = input();
//        dump($params);die();
        $lessgoods = $params['id'];
        $goods = Db::name('leescore_goods')->where('id', $lessgoods)->find();
        if (!$goods) {
            $this->error('商品不存在');
        }
//        dump(111);die();
        Db::name('leescore_goods')->where('id', $lessgoods)->delete();
        $this->success('删除成功');
    }
    public function edit()
    {

        $params = input();
        $goodsId = $params['id'];
        $goods = Db::name('leescore_goods')->where('id', $goodsId)->find();
        if (!$goods) {
            $this->error('商品不存在');
        }
        $fields = [
            'name', 'category_id', 'paytype', 'type', 'status',
            'body', 'rule', 'thumb', 'pics', 'weigh', 'stock',
            'scoreprice', 'money', 'usenum', 'flag', 'max_buy_number',
            'fenxiao_status', 'firsttime', 'lasttime'
        ];
        $data = [
            'goodscate' => 1,
            'goodsdad'  => $this->user->id,
            'createtime' => time(),
        ];
        foreach ($fields as $v) {
            if (isset($params[$v]) && $params[$v] !== '') {
                $data[$v] = $params[$v];
            }
        }
        Db::startTrans();
        try {
            $goodsId = Db::name('leescore_goods')->where('id', $goodsId)->update($data);
            if ($goodsId) {
                Db::commit();
                $this->success('修改成功');
            } else {
                throw new \Exception('修改失败');
            }
        } catch (\Exception $e) {
            Db::rollback();
            $this->error('修改失败：' . $e->getMessage());
        }

    }
//查看订单,全部或者状态的订单
    public function orderstatus(){

        $parmas = input();
//        dump($parmas);die();
        if (isset($parmas['status']) && $parmas['status'] != ''){
//            dump(11);die();
            $data = Db::name('leescore_order')->alias('lo')
                ->join('leescore_order_goods log','log.order_id = lo.id')
                ->field('log.goods_name,log.goods_thumb,log.buy_num,lo.*')
                ->where('lo.goodsdad', $this->auth->id)->where('lo.status','in',$parmas['status'])
                ->select();
        }else{
//            dump(222);die();
//            $data = Db::name('leescore_order')->where('goodsdad', $this->auth->id)->select();
            $data = Db::name('leescore_order')->alias('lo')
                ->join('leescore_order_goods log','log.order_id = lo.id')
                ->field('log.goods_name,log.goods_thumb,log.buy_num,lo.*')
                ->where('lo.goodsdad', $this->auth->id)
                ->select();
        }
//dump($data);die();
        $this->success('',$data);
    }
    //商品发货
    public function ship(){
        $params = input();
//        dump($params);die();
        $data = [
            'virtual_sn' => $params['expressNo'],
            'virtual_name' => $params['expressCompany'],
            'other' => $params['remark'],
            'status' => 2,
            'virtual_go_time' =>time(),
        ];
       $or = Db::name('leescore_order')->where('id', $params['orderId'])->find();
        if (!$or){
            $this->error('订单不存在');
        }
       $data =  Db::name('leescore_order')->where('id', $params['orderId'])->update($data);
       if ($data) {
           $this->success('发货成功');
       }else{
           $this->error('发货失败');
       }
    }
    //店铺数据
    public function shopHome(){
//        dump('111');die();
        $goods = Db::name('leescore_goods')->where('goodsdad', $this->auth->id)->count();  //商品数量
        $order = Db::name('leescore_order')
            ->where('goodsdad', $this->auth->id)
            ->whereTime('createtime', 'today') // 直接筛选今天的记录
            ->count();    //订单
        $deliver = Db::name('leescore_order')->where('goodsdad', $this->auth->id)->where('status', 1)->count();  //发货
        $this->success('',[
            'goods' => $goods,
            'order' => $order,
            'deliver' => $deliver,
        ]);
    }

}