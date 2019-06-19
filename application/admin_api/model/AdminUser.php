<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/4/27
 * Time: 17:15
 */

namespace app\admin_api\model;


use think\Model;

class AdminUser extends Model
{
    protected $pk = 'admin_user_id';

    protected $visible = ['roles'=>['role_id', 'role_name', 'is_enable', 'router_ids', 'button_ids']];

    /**
     * 处理头像返回绝对路径
     * @param $value
     * @return string
     */
    public function getAvatarAttr($value)
    {
        return config('app_host') . $value;
    }

    /**
     * 新增用户信息
     * @param array $data
     * @return mixed
     */
    public function createAdminUser($data=array())
    {
        $data['password']    = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['create_time'] = date('Y-m-d H:i:s');
        $model = self::create($data);
        return $model->admin_user_id;
    }

    /**
     * 更新用户信息
     * @param $adminUserId
     * @param $data
     * @return int|string
     */
    public function updateAdminUser($adminUserId, $data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $res = self::where('admin_user_id', $adminUserId)->update($data);
        return $res;
    }

    /**
     * 根据id获取用户信息
     * @param $adminUserId
     * @return mixed
     */
    public function getAdminUserById($adminUserId)
    {
        $data = self::with('roles')->get($adminUserId)->toArray();
        return $data;
    }

    /**
     * 获取单条用户信息
     * @param $condition
     * @param array $hidden
     * @return array
     */
    public function getAdminUser($condition=array(), $hidden=array())
    {
        $data = self::where($condition)->with('roles')->hidden($hidden)->findOrEmpty()->toArray();
        return $data;
    }

    /**
     * 获取多条用户信息
     * @param array $condition
     * @param array $hidden
     * @return mixed
     */
    public function getAdminUsers($condition, $hidden=array())
    {
        $data = self::where($condition)->with('roles')->hidden($hidden)->all()->toArray();
        return $data;
    }

    /**
     * 获取用户信息分页
     * @param int $page
     * @param int $row
     * @return array
     */
    public function getAdminUserPage($page=1, $row=20, $condition='', $sort='create_time DESC')
    {
        $paginate = self::where($condition)->with('roles')->order($sort)
            ->paginate($row)->hidden(['password']);
        $admin_users = $paginate->all();
        $pages = array(
            'current_page' => (int) $paginate->currentPage(),
            'last_page'    => (int) $paginate->lastPage(),
            'per_page'     => (int) $paginate->listRows(),
            'total'        => (int) $paginate->total(),
        );
        $data = array(
            'pages'       => $pages,
            'admin_users' => $admin_users
        );
        return $data;
    }

    public function roles()
    {
        return $this->belongsToMany('Role','AdminUserRole','role_id','admin_user_id');
    }
}
