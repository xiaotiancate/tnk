<?php

namespace app\admin\controller\xiluxc;

use app\common\controller\Backend;
use think\Db;

/**
 * 区域代理管理
 *
 * @icon fa fa-circle-o
 */
class Agent extends Backend
{

    /**
     * Admin模型对象
     * @var \app\admin\model\Agent
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Agent();

    }
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $list = $this->model
            ->where($where)
            ->order($sort, $order)
            ->paginate($limit);
        foreach ($list as &$item) {
            // 查省份名称
            $item['province_name'] = Db::name('xiluxc_area')->where('id', $item['province_id'])->value('name');
            // 查城市名称
            $item['city_name'] = Db::name('xiluxc_area')->where('id', $item['city_id'])->value('name');
            // 查区县名称
            $item['district_name'] = Db::name('xiluxc_area')->where('id', $item['district_id'])->value('name');
        }
        $result = ['total' => $list->total(), 'rows' => $list->items()];
//        dump($list->items());die();
        return json($result);
    }

    public function add()
    {
//        dump(input());die();
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = input();
//        dump($params['row']['user_id']);die();
        $data = [
            'user_id' => $params['row']['user_id'],
            'province_id' => $params['province'],
            'city_id' => $params['city'],
            'district_id' => $params['district'],
            'address' => $params['row']['address'],
            'lat' => $params['row']['lat'],
            'lng' => $params['row']['lng'],
            'commission_rate' => $params['row']['commission_rate'],
            'status' => 1,
            'createtime' => time(),
        ];
       $res = Db::name('xiluxc_agent')->insert($data);
       if($res){
           $this->success('添加成功');
       }else{
           $this->error('添加失败');
       }

    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


}
