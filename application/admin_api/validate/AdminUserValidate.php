<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/19
 * Time: 14:52
 */

namespace app\admin_api\validate;


use think\Validate;

class AdminUserValidate extends Validate
{
    protected $rule = array(
        'admin_user_id' => 'require|number',
        'username'      => 'require|regex:/^[0-9a-zA-z]{1,}$/|min:3|unique:admin_user,username',
        'password'      => 'require|min:6|confirm',
        'old_password'  => 'require|min:6',
        'is_active'     => 'require|in:0,1',
        'roles'         => 'require'
    );

    protected $message = array(
        'admin_user_id.require' => '用户ID不能为空',
        'admin_user_id.number'  => '用户ID不是数字',
        'username.require'      => '用户名不能为空',
        'username.regex'        => '用户名由数字或字母组成',
        'username.min'          => '用户名长度不能小于3',
        'username.unique'       => '用户名已存在',
        'password.require'      => '密码不能为空',
        'password.confirm'      => '两次密码输入不一致',
        'password.min'          => '密码长度不能小于6',
        'old_password.require'  => '旧密码不正确',
        'old_password.min'      => '旧密码不正确',
        'is_active.require'     => '激活状态不能为空',
        'is_active.in'          => '激活状态值不合法',
        'roles.require'         => '角色不能为空'
    );

    protected $scene = array(
        'create'          => array('username','password','is_active', 'roles'),
        'update_password' => array('admin_user_id','password'),
        'update_user_pwd' => array('admin_user_id','password','old_password'),
        'update_active'   => array('admin_user_id','is_active'),
        'update_role'     => array('admin_user_id','roles'),
    );
}
