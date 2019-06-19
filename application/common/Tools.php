<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/4/27
 * Time: 16:58
 */

namespace app\common;


use Firebase\JWT\JWT;

class Tools
{
    /**
     * 返回json格式数据
     * @param int $code 响应状态
     * @param string $msg 响应消息
     * @param array $data 响应数据
     */
    public static function echoJson($code=200, $msg='', $data=array())
    {
        $json = array(
            'code' => $code,
            'msg'  => $msg,
            'data' =>$data
        );
        echo json_encode($json, true);
        exit();
    }

    public static function generateJwt($startTime, $ExpireTime, $userId, $userUName)
    {
        $access_token = array(
            "iss"      => config('app_host'), // 签发者
            "aud"      => config('app_host'), // 面向的用户
            "iat"      => time(), // 签发时间
            "nbf"      => $startTime , // jwt开始生效 
            "exp"      => $ExpireTime, // 过期时间120分钟
            'id'       => $userId, // 用户ID
            'username' => $userUName // 用户名
        );
        $access_token  = JWT::encode($access_token, config('jwt_key'));
        return $access_token;
    }

    /**
     * 无限极分类
     * @param array $items
     * @return array
     */
    public static function arrayTree($items=array())
    {
        $tree = array();
        foreach ($items as $item) {
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$items[$item['id']];
            } else {
                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
    }
}
