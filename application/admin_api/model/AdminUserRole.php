<?php
/**
 * Created by PhpStorm.
 * User: oray
 * Date: 2019/6/5
 * Time: 17:38
 */

namespace app\admin_api\model;


use think\model\Pivot;

class AdminUserRole extends Pivot
{
    /**
     * 保存用户角色
     * @param $data
     * @return \think\Collection
     * @throws \Exception
     */
    public function saveAdminUserRole($data)
    {
        $model = self::saveAll($data);
        return $model;
    }

    /**
     * 删除用户角色
     * @param $condition
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteAdminRole($condition)
    {
        $model = self::where($condition)->delete();
        return $model;
    }
}