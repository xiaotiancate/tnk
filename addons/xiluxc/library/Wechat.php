<?php
namespace addons\xiluxc\library;

use addons\xiluxc\library\wechat\WeixinMini;
use addons\xiluxc\library\wechat\WeixinPublic;
use think\Exception;

class Wechat
{
    protected $platform;
    protected $app;
    //小程序与公众号可访问方法
    protected $publicMethods = [
        'wxmini'    =>  ["wxlogin","wxNumberEncrypted","getlimited","getUnlimited","getPhoneNumber","union_order"],
        'wxoffical'    =>  ["jsapi","auth","auth_back","union_order"],
    ];

    public function __construct($platform=null){
        $this->platform = $platform;
        $this->app = $this->applicationInit();
    }

    private function applicationInit(){
        if($this->platform == 'wxmini'){
            $app = new WeixinMini();
        }else if($this->platform == 'wxoffical'){
            $app = new WeixinPublic();
        }else{
            throw new Exception("不支持的平台");
        }
        return $app;
    }
    /**
     * 获取平台实例
     * @return \EasyWeChat\MiniProgram\Application|\EasyWeChat\OfficialAccount\Application
     * @throws Exception
     */
    public function getApp(){
        return $this->app;
    }

    /**
     * 方法转发到驱动提供者
     *
     * @param string $funcname
     * @param array $arguments
     * @return void
     */
    public function __call($funcname, $arguments)
    {
        if(method_exists($this->app,$funcname)
            && isset($this->publicMethods[$this->platform])
            && in_array($funcname,$this->publicMethods[$this->platform])){
            return $this->app->{$funcname}(...$arguments);
        }else{
            exception("不可访问的方法");
        }

    }

}