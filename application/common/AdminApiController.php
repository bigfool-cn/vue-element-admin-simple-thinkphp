<?php
/**
 * Created by PhpStorm.
 * User: oray
 * Date: 2019/4/28
 * Time: 15:33
 */

namespace app\common;

use app\admin_api\model\AdminUser as AdminUserModel;
use think\Controller;
use think\facade\Cache;

class AdminApiController extends Controller
{
    public function json($code=200, $msg='', $data=array())
    {
        if ($this->request->isRefreshToken) {
            $userId = $this->request->userId;
            // 刷新token
            $adminUser = AdminUserModel::where('admin_user_id', $this->request->userId)->findOrEmpty();
            // 请求验证token
            $startTime  = time();
            $ExpireTime = time()+7200;
            $userId     = $adminUser->admin_user_id;
            $userName   = $adminUser->username;
            $accessToken = \app\common\Tools::generateJwt($startTime, $ExpireTime, $userId, $userName);
            $data['access_token'] = $accessToken;
            $oldAccessToken  = $this->request->header('access-token');
            Cache::store('redis')->set($oldAccessToken,true, 20);
        }
        $json = array(
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        );
        return json_encode($json, true);
    }
}
