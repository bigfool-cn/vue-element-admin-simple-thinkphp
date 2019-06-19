<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/25
 * Time: 9:14
 */

namespace app\admin_api\controller;


use app\admin_api\validate\SystemButtonValidate;
use app\common\AdminApiController;
use app\admin_api\model\SystemButton as SystemButtonModel;
use think\Db;

class SystemButton extends AdminApiController
{

    /**
     * 按钮Model
     * @var null
     */
    private $_systemButtonModel = null;

    /**
     * 按钮验证器
     * @var null
     */
    private $_systemButtomValidate = null;

    public function __construct()
    {
        parent::__construct();
        $this->_systemButtonModel    = new SystemButtonModel();
        $this->_systemButtomValidate = new SystemButtonValidate();
    }

    /**
     * 新增按钮
     * @return false|string
     */
    public function createSystemButton()
    {
        $post = $this->request->post();
        if ($this->_systemButtomValidate->scene('create')->check($post)) {
            try {
                $button_id = $this->_systemButtonModel->createSystemButton($post);
                if ($button_id) {
                    return $this->json(20000, '新增成功');
                } else {
                    return $this->json(50000, '新增失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, '新增成功');
            }
        } else {
            return $this->json(40000,$this->_systemButtomValidate->getError());
        }
    }

    /**
     * 修改按钮
     * @return false|string
     */
    public function updateSystemButton()
    {
        $post = $this->request->post();
        if ($this->_systemButtomValidate->scene('update')->check($post)) {
            // 判断唯一标识是否存在
            $buttonId = Db::table('system_button')->where('key',$post['key'])->value('button_id');
            if ($buttonId && $buttonId!=$post['button_id']) {
                return $this->json(40000, '唯一标识已经存在');
            }
            try {
                $data = array(
                    'title'     => $post['title'],
                    'key'       => $post['key'],
                    'is_enable' => $post['is_enable']
                );
                $res = $this->_systemButtonModel->updateSystemButton($post['button_id'],$data);
                if ($res) {
                    return $this->json(20000, '修改成功');
                } else {
                    return $this->json(50000, '修改失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, '修改成功');
            }
        } else {
            return $this->json(40000,$this->_systemButtomValidate->getError());
        }
    }

    /**
     * 删除按钮
     * @return false|string
     */
    public function deleteSystemButton()
    {
        $post = $this->request->post();
        $buttonId = $post['button_id'];
        if (empty($buttonId) && !is_int($buttonId) && $buttonId < 0) {
            return $this->json(40000,'参数ID错误');
        }
        try {
            $condition = array('button_id'=>$buttonId);
            $res = $this->_systemButtonModel->deleteSystemButton($condition);
            if ($res) {
                return $this->json(20000, '删除成功');
            } else {
                return $this->json(50000, '删除失败');
            }
        } catch (\Exception $e) {
            return $this->json(50000,'删除失败');
        }
    }

    /**
     * 更改是否可用
     * @return false|string
     */
    public function updateSystemButtonEnable()
    {
        $post = $this->request->post();
        if ($this->_systemButtomValidate->scene('update_enable')->check($post)) {
            $data = array('is_enable'=>!$post['is_enable']);
            try {
                $res = $this->_systemButtonModel->updateSystemButton($post['button_id'],$data);
                if ($res) {
                    return $this->json(20000, '更改成功');
                } else {
                    return $this->json(50000, '更改失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, '更改失败');
            }

        } else {
            return $this->json(40000,$this->_systemButtomValidate->getError());
        }
    }

    /**
     * 获取按钮列表
     * @return false|string
     */
    public function getSystemButtonList()
    {
        $params = $this->request->get();
        $page   = 1;
        $row    = 20;
        $condition  = '';
        $this->_makeCondition($params, $condition, $page, $row);
        $data = $this->_systemButtonModel->getSystemButtonPage($page, $row, $condition);
        return $this->json(20000,'获取成功', $data);
    }

    /**
     * 获取所有按钮
     * @return false|string
     */
    public function getSystemButtonAll($is_enable=1)
    {
        $condition = array('is_enable'=>$is_enable);
        $data = $this->_systemButtonModel->getSystemButtonAll($condition);
        return $this->json(20000,'获取成功', $data);
    }

    /**
     * 列表查询条件
     * @param $params
     * @param $condition
     * @param $page
     * @param $row
     */
    private function _makeCondition($params, &$condition, &$page, &$row)
    {
        if (empty($params)) {
            $page = 1;
            $row  = 20;
            return;
        }

        // 页码
        if (isset($params['page']) && $params['page'] > 0 && is_int((int)$params['page'])) {
            $page = $params['page'];
        } else {
            $page = 1;
        }

        // 数量
        if (isset($params['row']) && $params['row'] > 0 && is_int((int)$params['row']) && $params['row'] <= 100) {
            $row = $params['row'];
        } else {
            $row = 20;
        }

        // 按钮名称
        if (isset($params['title']) && !empty($params['title'])) {
            $condition .= "title LIKE '%{$params['title']}%'";
        }

        // 是否可用
        if (isset($params['is_enable']) && $params['is_enable'] !== '' && in_array($params['is_enable'], [0, 1])) {
            $condition && $condition .= ' AND ';
            $condition .= "is_enable = {$params['is_enable']}";
        }

        // 时间
        if (isset($params['date']) && strtotime($params['date'][0])) {
            $condition && $condition .= ' AND ';
            $condition .= "create_time >= '{$params['date'][0]}'";
        }
        if (isset($params['date']) && strtotime($params['date'][1])) {
            $condition && $condition .= ' AND ';
            $endTime = date('Y-m-d', strtotime ("+1 day", strtotime($params['date'][1])));
            $condition .= "create_time <= '$endTime'";
        }
    }
}
