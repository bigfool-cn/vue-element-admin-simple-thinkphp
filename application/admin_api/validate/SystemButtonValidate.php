<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/25
 * Time: 9:38
 */

namespace app\admin_api\validate;


use think\Validate;

class SystemButtonValidate extends Validate
{
    protected $rule = array(
        'button_id' => 'require|integer',
        'title'     => 'require|max:50',
        'key'       => 'require|unique:system_button,key|max:30',
        'is_enable' => 'require|in:0,1'
    );

    protected $message = array(
        'button_id.require' => 'ID不能为空',
        'button_id.integer' => 'ID不是整数',
        'title.require'     => '按钮名称不能为空',
        'title.max'         => '按钮名称长度不能超过50',
        'key.require'       => '唯一标识不能为空',
        'key.unique'        => '唯一标识已存在',
        'key.max'           => '唯一标识长度不能超过30',
        'is_enable.require' => '是否可用不能为空',
        'is_enable.in'      => '是否可用值不在指定范围'
    );

    protected $scene = array(
        'create'        => array('title', 'key', 'is_enable'),
        'update'        => array('button_id', 'title', 'is_enable'),
        'update_enable' => array('button_id', 'is_enable')
    );
}
