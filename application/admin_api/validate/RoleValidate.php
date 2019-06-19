<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/6/2
 * Time: 17:43
 */

namespace app\admin_api\validate;


use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = array(
        'role_id'   => 'require|integer',
        'role_name' => 'require|max:50,unique:role,role_name',
        'desc'      => 'require',
    );

    protected $message = array(
        'role_id.require'       => 'ID不能为空',
        'role_id.integer'       => 'ID不是整数',
        'role_name.require'     => '角色名称不能为空',
        'role_name.max'         => '角色名称长度不能超过50',
        'role_name.unique'      => '角色名称已存在',
        'desc.require'          => '角色描述不能为空',
    );

    protected $scene = array(
        'create'        => array('role_name', 'desc'),
        'update'        => array('role_id', 'desc')
    );
}
