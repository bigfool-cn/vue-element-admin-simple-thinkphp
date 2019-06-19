<?php
/**
 * Created by PhpStorm.
 * User: oray
 * Date: 2019/5/16
 * Time: 11:30
 */

namespace app\admin_api\model;

use app\common\Tools;
use think\Model;

class AdminRouter extends Model
{
    protected $pk = 'admin_router_id';

    /**
     * 创建路由
     * @param $data
     * @return mixed
     */
    public function createAdminRouter($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $model = self::create($data);
        return $model->admin_router_id;
    }

    /**
     * 更新路由
     * @param $adminRouterId
     * @param $data
     * @return int|string
     */
    public function updateAdminRouter($adminRouterId,$data)
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $model = self::where('admin_router_id',$adminRouterId)->update($data);
        return $model;
    }

    /**
     * 删除路由
     * @param $condition
     * @return int
     */
    public function deleteAdminRouter($adminRouterId=0)
    {
        $model = self::where('admin_router_id', $adminRouterId)->whereOr('parent_id', $adminRouterId)->delete();
        return $model;
    }

    /**
     * 获取路由树
     * @return array
     */
    public function getAdminRouterTree()
    {
        $model = self::order('sort ASC parent_id ASC ')
            ->column('admin_router_id AS id,title AS label,parent_id');
        foreach ($model as $key=>$value) {
            $model[$value['id']] = $value;
        }
        $tree = Tools::arrayTree($model);
        $data = array(
            'routers_tree' => $tree,
        );
        return $data;
    }

    /**
     * 获取一条后台路由
     * @param $condition
     */
    public function getAdminRouter($condition, $fields=array())
    {
        $model = self::where($condition)->field($fields)->find();
        $model && $model->toArray();
        $model && $model['param'] = json_decode($model['param'],true);
        return $model;
    }

    /**
     * 获取多条后台路由
     * @param array $condition
     */
    public function getAdminRouters($condition, $fields=array())
    {
        $model = self::where($condition)->field($fields)->select();
        $model && $model->toArray();
        $model && $model['param'] = json_decode($model['param'],true);
        return $model;
    }

    /**
     * 获取后台路由分页
     * @param int $page 页码
     * @param int $row 数量
     * @param $condition
     * @return array
     */
    public function getAdminRouterPage($page=1, $row=20, $condition)
    {
        $paginate = self::where($condition)->paginate((int) $row);
        $admin_routers = $paginate->all();
        $pages = array(
            'current_page' => $paginate->currentPage(),
            'last_page'    => $paginate->lastPage(),
            'per_page'     => $paginate->listRows(),
            'total'        => $paginate->total(),
        );
        $data = array(
            'pages'         => $pages,
            'admin_routers' => $admin_routers
        );
        return $data;
    }

    /**
     * 获取路由表结构
     * @param $condition
     * @return array
     */
    public function getAdminRouterTable($condition)
    {
        $model = self::where($condition)->order('sort ASC parent_id ASC ')
            ->column('admin_router_id AS id,parent_id,param');
        foreach ($model as $key=>$value) {
            $param               = json_decode($value['param'],true);
            $param['id']         = $value['id'];
            $param['parent_id']  = $value['parent_id'];
            $model[$value['id']] = $param;
        }
        $tree = Tools::arrayTree($model);
        return $tree;
    }
}
