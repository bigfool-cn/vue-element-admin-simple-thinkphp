<?php

namespace app\http\middleware;

use app\admin_api\model\AdminUser as AdminUserModel;
use Firebase\JWT\JWT;
use think\facade\Cache;

class CheckToken
{
    public function handle($request, \Closure $next)
    {
        $access_token  = $request->header('access-token');
        $request->isRefreshToken = false;
        if (!$access_token) {
            $data = json_encode(array('code'=>50008,'msg'=>'无效token'),true);
            response()->data($data)->send();
        }
        try {
            $decode = JWT::decode($access_token, config('jwt_key'), array('HS256'));
            if ($decode->iss!=$decode->aud && $decode->iss!=config('app_host')) {
                $data = json_encode(array('code'=>50008,'msg'=>'无效token'),true);
                response()->data($data)->send();
            }

            $admin_user = AdminUserModel::where('admin_user_id', $decode->id)->findOrEmpty();
            if ($admin_user->isEmpty()) {
                $data = json_encode(array('code'=>50008,'msg'=>'无效token'),true);
                response()->data($data)->send();
            }

            $time = abs(time() - $decode->exp);
            if ($time <= 60*10) {
                $request->isRefreshToken = true;
            }

            if ((time()-$decode->exp) > 60*10){
                $data = json_encode(array('code'=>50014,'msg'=>'token已过期'),true);
                response()->data($data)->send();
            }
            $request->userId = $decode->id;
        } catch (\Exception $e) {
            if (Cache::store('redis')->get($access_token)) {
                return $next($request);
            }
            $data = json_encode(array('code'=>50008,'msg'=>'无效token'),true);
            response()->data($data)->send();
        }
        return $next($request);
    }
}
