<?php
/**
 * Created by PhpStorm.
 * User: oray
 * Date: 2019/5/16
 * Time: 11:59
 */

namespace app\admin_api\validate;


use think\Validate;

class AdminRouterValidate extends Validate
{
    protected $rule = [
        'parent_id'  => 'require|number',
        'title'      => 'require|max:50',
        'param'      => 'require'
    ];

    protected $message = [
        'parent_id.require' => '顶级路由不能为空',
        'title.require'     => '路由名称不能为空',
        'title.max'         => '路由名称长度不能超过50',
        'param.require'     => '路由配置项不能为空'
    ];
}
