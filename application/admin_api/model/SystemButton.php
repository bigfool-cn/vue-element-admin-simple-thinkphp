<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/25
 * Time: 9:11
 */

namespace app\admin_api\model;


use think\Model;

class SystemButton extends Model
{
    protected $pk = 'button_id';

    /**
     * 新增按钮
     * @param array $data
     * @return mixed
     */
    public function createSystemButton($data=array())
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $model = self::create($data);
        return $model->button_id;
    }

    /**
     * 修改按钮
     * @param int $buttonId
     * @param array $data
     * @return int|string
     */
    public function updateSystemButton($buttonId=0, $data=array())
    {
        $data['update_time'] = date('Y-m-d H:i:s');
        $model = self::where('button_id',$buttonId)->update($data);
        return $model;
    }

    /**
     * 删除按钮
     * @param $condition
     * @return int
     */
    public function deleteSystemButton($condition)
    {
        $model = self::where($condition)->delete();
        return $model;
    }

    /**
     * 获取按钮分页
     * @param int $page
     * @param int $row
     * @param string $sort
     * @return array
     */
    public function getSystemButtonPage($page=1, $row=20, $condition, $sort='create_time Desc')
    {
        $paginate = self::where($condition)->order($sort)->paginate($row)->hidden(['password']);
        $system_buttons = $paginate->all();
        $pages = array(
            'current_page' => (int) $paginate->currentPage(),
            'last_page'    => (int) $paginate->lastPage(),
            'per_page'     => (int) $paginate->listRows(),
            'total'        => (int) $paginate->total(),
        );
        $data = array(
            'pages'          => $pages,
            'system_buttons' => $system_buttons
        );
        return $data;
    }

    /**
     * 获取所有按钮
     * @return false|\think\db\Query[]
     */
    public function getSystemButtonAll($condition, $fields=array())
    {
        $model = self::where($condition)->field($fields)->all()->toArray();
        return $model;
    }
}
