<?php

namespace app\admin\model;

use think\Model;


class Shgl extends Model
{

    

    

    // 表名
    protected $name = 'merchant';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'audit_status_text',
        'audit_time_text',
        'status_text'
    ];
    

    
    public function getAuditStatusList()
    {
        return ['0' => __('Audit_status 0'), '1' => __('Audit_status 1'), '2' => __('Audit_status 2')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getAuditStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['audit_status'] ?? '');
        $list = $this->getAuditStatusList();
        return $list[$value] ?? '';
    }


    public function getAuditTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['audit_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }

    protected function setAuditTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
