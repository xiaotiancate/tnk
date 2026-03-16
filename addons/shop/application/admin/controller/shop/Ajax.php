<?php

namespace app\admin\controller\shop;

use addons\shop\library\aip\AipContentCensor;
use addons\shop\library\SensitiveHelper;
use addons\shop\library\Service;
use app\common\controller\Backend;
use think\Cache;

/**
 * Ajax
 *
 * @icon fa fa-circle-o
 * @internal
 */
class Ajax extends Backend
{

    /**
     * 模型对象
     */
    protected $model = null;
    protected $noNeedRight = ['*'];

    /**
     * 获取模板列表
     * @internal
     */
    public function get_template_list()
    {
        $files = [];
        $keyValue = $this->request->request("keyValue");
        if (!$keyValue) {
            $type = $this->request->request("type");
            $name = $this->request->request("name");
            if ($name) {
                //$files[] = ['name' => $name . '.html'];
            }
            //设置过滤方法
            $this->request->filter(['strip_tags']);
            $config = get_addon_config('shop');
            $themeDir = ADDON_PATH . 'shop' . DS . 'view' . DS . $config['theme'] . DS;
            $dh = opendir($themeDir);
            while (false !== ($filename = readdir($dh))) {
                if ($filename == '.' || $filename == '..') {
                    continue;
                }
                if ($type) {
                    $rule = $type == 'channel' ? '(channel|list)' : $type;
                    if (!preg_match("/^{$rule}(.*)/i", $filename)) {
                        continue;
                    }
                }
                $files[] = ['name' => $filename];
            }
        } else {
            $files[] = ['name' => $keyValue];
        }
        return $result = ['total' => count($files), 'list' => $files];
    }

    /**
     * 检查内容是否包含违禁词
     * @throws \Exception
     */
    public function check_content_islegal()
    {
        $config = get_addon_config('shop');
        $content = $this->request->post('content');
        if (!$content) {
            $this->error(__('Please input your content'));
        }
        if ($config['audittype'] == 'local') {
            // 敏感词过滤
            $handle = SensitiveHelper::init()->setTreeByFile(ADDON_PATH . 'shop/data/words.dic');
            //首先检测是否合法
            $arr = $handle->getBadWord($content);
            if ($arr) {
                $this->error(__('The content is not legal'), null, $arr);
            } else {
                $this->success(__('The content is legal'));
            }
        } else {
            $client = new AipContentCensor($config['aip_appid'], $config['aip_apikey'], $config['aip_secretkey']);
            $result = $client->textCensorUserDefined($content);
            if (isset($result['conclusionType']) && $result['conclusionType'] > 1) {
                $msg = [];
                foreach ($result['data'] as $index => $datum) {
                    $msg[] = $datum['msg'];
                }
                $this->error(implode("<br>", $msg), null, []);
            } else {
                $this->success(__('The content is legal'));
            }
        }
    }

    /**
     * 获取关键字
     * @throws \Exception
     */
    public function get_content_keywords()
    {
        $config = get_addon_config('shop');
        $title = $this->request->post('title');
        $tags = $this->request->post('tags', '');
        $content = $this->request->post('content');
        if (!$content) {
            $this->error(__('Please input your content'));
        }
        $keywords = Service::getContentTags($title);
        $keywords = in_array($title, $keywords) ? [] : $keywords;
        $keywords = array_filter(array_merge([$tags], $keywords));
        $description = mb_substr(strip_tags($content), 0, 200);
        $data = [
            "keywords"    => implode(',', $keywords),
            "description" => $description
        ];
        $this->success("提取成功", null, $data);
    }

    /**
     * 获取标题拼音
     */
    public function get_title_pinyin()
    {
        $title = $this->request->post("title");
        //分隔符
        $delimiter = $this->request->post("delimiter", "");
        $pinyin = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
        if ($title) {
            $result = $pinyin->permalink($title, $delimiter);
            $this->success("", null, ['pinyin' => $result]);
        } else {
            $this->error(__('Parameter %s can not be empty', 'name'));
        }
    }

    /**
     * @ 获取模板内容
     * @return void
     */
    public function get_tpl()
    {
        $tpl_id = $this->request->param('tpl_id');
        $source_id = $this->request->param('source_id');
        if (empty($tpl_id)) {
            $this->error('缺少模板参数');
        }
        if (empty($source_id)) {
            $this->error('缺少卡片来源');
        }
        $html = \addons\shop\library\Service::getSourceTpl($tpl_id, $source_id);
        $this->success('', '', $html);
    }

    public function get_page_list()
    {
        $pageList = [
            ['path' => 'https://www.baidu.com', 'name' => '外部链接'],
            ['path' => '/pages/index/index', 'name' => '首页'],
            ['path' => '/pages/category/index', 'name' => '分类'],
            ['path' => '/pages/cart/cart', 'name' => '购物车'],

            ['path' => '/pages/my/my', 'name' => '我的', 'flag' => 'my'],
            ['path' => '/pages/order/list', 'name' => '我的订单', 'flag' => 'my'],
            ['path' => '/pages/signin/signin', 'name' => '每日一签', 'flag' => 'my'],
            ['path' => '/pages/my/collect', 'name' => '我的收藏', 'flag' => 'my'],
            ['path' => '/pages/address/address', 'name' => '我的地址', 'flag' => 'my'],
            ['path' => '/pages/coupon/user', 'name' => '我的优惠券', 'flag' => 'my'],
            ['path' => '/pages/score/order', 'name' => '我的积分兑换', 'flag' => 'my'],
            ['path' => '/pages/remark/comment', 'name' => '我的评论', 'flag' => 'my'],
            ['path' => '/pages/my/profile', 'name' => '个人资料', 'flag' => 'my'],
            ['path' => '/pages/help/index', 'name' => '帮助中心', 'flag' => 'my'],
            ['path' => '/pages/page/page?diyname=aboutus', 'name' => '关于我们', 'flag' => 'my'],
            ['path' => '/pages/my/agreement', 'name' => '用户协议', 'flag' => 'my'],
            ['path' => '/pages/signin/ranking', 'name' => '签到排行榜'],
            ['path' => '/pages/signin/logs', 'name' => '签到日志'],

            ['path' => '/pages/login/login', 'name' => '登录(账号密码)'],
            ['path' => '/pages/login/mobilelogin', 'name' => '登录(手机号)'],
            ['path' => '/pages/login/register', 'name' => '注册'],
            ['path' => '/pages/login/forgetpwd', 'name' => '忘记密码'],

            ['path' => '/pages/goods/goods?category_id=1', 'name' => '商品列表（category_id=分类ID）'],
            ['path' => '/pages/goods/detail?id=1', 'name' => '商品详情（id=数据ID）'],
            ['path' => '/pages/address/addedit', 'name' => '地址编辑(添加)'],
            ['path' => '/pages/page/page?id=1&diyname=diyname', 'name' => '单页详情（id=数据ID和diyname=单页自定义URL名称）'],
        ];
        if ($this->request->isAjax()) {
            $filter = $this->request->get('filter');
            $filterArr = (array)json_decode($filter, true);
            $list = \app\admin\model\shop\Category::where('status', '<>', 'hidden')->field('*')->select();
            foreach ($list as $index => $item) {
                $pageList[] = ['path' => '/pages/goods/goods?category_id=' . $item['id'], 'name' => '分类:' . $item['name'], 'flag' => 'category'];
            }
            $list = \app\admin\model\shop\Goods::where('status', '<>', 'hidden')->field('*')->select();
            foreach ($list as $index => $item) {
                $pageList[] = ['path' => '/pages/goods/detail?id=' . $item['id'], 'name' => '商品:' . $item['title'], 'flag' => 'goods'];
            }
            $list = \app\admin\model\shop\Page::where('status', '<>', 'hidden')->field('*')->select();
            foreach ($list as $index => $item) {
                $pageList[] = ['path' => '/pages/page/page?id=' . $item['id'], 'name' => '单页:' . $item['title'], 'flag' => 'page'];
            }
            if ($filterArr) {
                if (isset($filterArr['flag']) && $filterArr['flag'] && $filterArr['flag'] != 'all') {
                    foreach ($pageList as $index => $item) {
                        if (!isset($item['flag']) || $item['flag'] !== $filterArr['flag']) {
                            unset($pageList[$index]);
                        }
                    }
                }
                $pageList = array_values($pageList);
                if (isset($filterArr['path']) && $filterArr['path']) {
                    foreach ($pageList as $index => $item) {
                        if (stripos($item['path'], $filterArr['path']) === false) {
                            unset($pageList[$index]);
                        }
                    }
                }
                $pageList = array_values($pageList);
                if (isset($filterArr['name']) && $filterArr['name']) {
                    foreach ($pageList as $index => $item) {
                        if (stripos($item['name'], $filterArr['name']) === false) {
                            unset($pageList[$index]);
                        }
                    }
                }
            }

            $search = $this->request->get('search', '');
            $offset = $this->request->get('offset', 0);
            $limit = $this->request->get('limit', 10);
            if ($search) {
                foreach ($pageList as $index => $item) {
                    if (stripos($item['path'], $search) === false && stripos($item['name'], $search) === false) {
                        unset($pageList[$index]);
                    }
                }
                $pageList = array_values($pageList);
            }
            $result = array("total" => count($pageList), "rows" => array_slice($pageList, $offset, $limit));

            return json($result);
        }
        $this->view->assign('pageList', $pageList);
        return $this->view->fetch('shop/common/pages');
    }

    public function config()
    {
        $name = $this->request->get('name');
        if ($name == 'sms') {
            $config = config('addons.hooks');
            if (isset($config['sms_send']) && $config['sms_send']) {
                $name = reset($config['sms_send']);
            } else {
                $this->error("请在插件管理中安装一款短信验证插件", "");
            }
        } elseif ($name == 'oss') {
            $config = config('addons.hooks');
            if (isset($config['upload_config_init']) && $config['upload_config_init']) {
                $availableArr = array_intersect($config['upload_config_init'], ['alioss', 'bos', 'cos', 'upyun', 'ucloud', 'hwobs', 'qiniu']);
                if ($availableArr) {
                    $name = reset($availableArr);
                }
            }
            if (!$name || $name == 'oss') {
                $this->error("请在插件管理中安装一款云存储插件", "");
            }
        } else {
            $info = get_addon_info($name);
            $addonArr = [
                'third'  => '第三方登录',
                'signin' => '会员签到',
                'epay'   => '微信支付宝整合',
            ];
            if (!$info) {
                $this->error('请检查对应插件' . (isset($addonArr[$name]) ? "《{$addonArr[$name]}》" : "") . '是否安装且启用', "");
            }
        }
        $this->redirect('addon/config?name=' . $name . '&dialog=1');
    }

    /**
     * 清除缓存
     */
    public function clearcache()
    {
        Cache::clear("shop");
        $config = get_addon_config('shop');
        @rmdirs(TEMP_PATH, false);
        $this->success("");
    }
}
