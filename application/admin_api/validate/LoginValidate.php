<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/4/27
 * Time: 16:40
 */

namespace app\admin_api\validate;


use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = array(
        'username' => 'require',
        'password' => 'require|min:6',
    );

    protected $message = array(
        'username.require' => '请输入账号',
        'password.require' => '请输入密码',
        'password.min'      => '密码至少六位数'
    );
}
