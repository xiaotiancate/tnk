<?php

namespace app\admin\controller\xiluxc\finance;

use app\common\controller\Backend;
use think\Db;
/**
 * 会员余额变动管理
 *
 * @icon fa fa-circle-o
 */
class MoneyLog extends Backend
{
    protected $relationSearch = true;
    /**
     * MoneyLog模型对象
     * @var \app\common\model\xiluxc\MoneyLog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\xiluxc\MoneyLog;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

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
            ->with(["user"=>function($q){
                $q->withField(['id','nickname','avatar']);
            }])
            ->where('type',\app\common\model\xiluxc\MoneyLog::TYPE_COMMISSION)
            ->where($where)
            ->order($sort, $order)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }
    public function recharge(){
        $user_id=$this->request->request('user_id');
        // var_dump($user_id);die;
         $dara=$this->request->post('row/a');
        //  $row=$this->request->request('row');
        if (null === $dara) {
            $this->view->assign('user_id', $user_id);
            return $this->view->fetch();
        }
        $user=Db::name('xiluxc_user_account')->where('user_id',$dara['user_id'])->find();
        // dump($user);die;  $user['money']
        $dara_log['type']=1;
        $dara_log['shop_id']=0;
        $dara_log['user_id']=$dara['user_id'];
        $dara_log['order_id']=0;
        $dara_log['withdraw_id']=0;
        $dara_log['money']=$dara['money'];
        $dara_log['before']=$user['money'];
        $dara_log['after']=$dara['money']+$user['money'];
        $dara_log['memo']='后台充值';
        $dara_log['status']=1;
        $dara_log['createtime']=time();
        $res=Db::name('xiluxc_money_log')->insert($dara_log);
        if($res){
            $monry['money']=$dara_log['money']+$user['money'];
            Db::name('xiluxc_user_account')->where('user_id',$dara['user_id'])->update(($monry));
        }
        return json(['code'=>1]);
        // dump($dara);die;
    }
    public function add()
    {
        return;
    }

    public function edit($ids=null)
    {
        return;
    }

    public function del($ids=null)
    {
        return;
    }

    public function multi($ids=null)
    {
        return;
    }

}
