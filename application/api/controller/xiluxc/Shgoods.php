<?php


namespace app\api\controller\xiluxc;


use app\common\controller\xiluxc\XiluxcApi;
use app\common\model\LeescoreCategory;
use fast\Tree;

class Shgoods extends XiluxcApi
{
    public function category()
    {

        $this->model = new LeescoreCategory();
        $disabledIds = [];

        $cate = $this->model->getLeescoreCategory();

        $tree = Tree::instance()->init($cate, 'category_id');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }

//        $this->assignconfig('options_val', $categorydata);

//        $this->opt = $categorydata;
////        dump($categorydata);die();
//        $this->view->assign('options', $categorydata);
//        dump($categorydata);die();
        $this->success('',$categorydata);

    }

}