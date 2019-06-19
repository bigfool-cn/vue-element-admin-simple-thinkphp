<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/30
 * Time: 19:38
 */

namespace app\admin_api\model;


use think\Model;

class Role extends Model
{
    protected $pk = 'role_id';

    /**
     * 创建角色
     * @param $data
     * @return mixed
     */
    public function createRole($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $model = self::create($data);
        return $model->role_id;
    }

    /**
     * 更新角色
     * @param $roleId
     * @param $data
     * @return int|string
     */
    public function updateRole($roleId,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $model = self::where('role_id',$roleId)->update($data);
        return $model;
    }

    /**
     * 删除角色
     * @param $condition
     * @return int
     */
    public function deleteRole($condition)
    {
        $model = self::where($condition)->delete();
        return $model;
    }

    /**
     * 获取单条角色
     * @param array $condition
     * @param array $fields
     */
    public function getRole($condition, $fields=array())
    {
        $model = self::where($condition)->field($fields)->find();
         if ($model) {
             $model->toArray();
             $model['router_ids'] = json_decode($model['router_ids'], true);
             $model['button_ids'] = json_decode($model['button_ids'], true);
         }
        return $model;
    }

    /**
     * 获取角色分页
     * @param int $page 页码
     * @param int $row 数量
     * @param $condition
     * @return array
     */
    public function getRolePage($page=1, $row=20, $condition, $sort='create_time DESC')
    {
        $paginate = self::where($condition)->order($sort)->paginate($row);
        $roles = $paginate->all();
        $pages = array(
            'current_page' => (int) $paginate->currentPage(),
            'last_page'    => (int) $paginate->lastPage(),
            'per_page'     => (int) $paginate->listRows(),
            'total'        => (int) $paginate->total(),
        );
        $data = array(
            'pages' => $pages,
            'roles' => $roles
        );
        return $data;
    }

    /**
     * 获取角色权限
     * @param $roleId
     * @return array
     */
    public function getRoleAuths($routerIds, $buttonIds)
    {
        // 去重
        $routerIds = array_unique($routerIds);
        $buttonIds = array_unique($buttonIds);
        // 路由权限
        $adminRouterModel = new AdminRouter();
        $condition = array('admin_router_id'=>$routerIds);
        $routers = $adminRouterModel->getAdminRouterTable($condition);
        // 按钮权限
        $systemButtomModel = new SystemButton();
        $condition = array('button_id'=>$buttonIds);
        $fields = array('key');
        $buttons = $systemButtomModel->getSystemButtonAll($condition, $fields);

        return array('routers'=>$routers, 'buttons'=>array_column($buttons,'key'));
    }
}
