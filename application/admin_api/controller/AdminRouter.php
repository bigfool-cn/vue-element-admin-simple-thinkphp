<?php
/**
 * Created by PhpStorm.
 * User: oray
 * Date: 2019/5/16
 * Time: 11:31
 */

namespace app\admin_api\controller;


use app\admin_api\validate\AdminRouterValidate;
use app\admin_api\model\AdminRouter as AdminRouterModel;
use app\common\AdminApiController;


class AdminRouter extends AdminApiController
{
    /**
     * 后台路由验证器
     */
    private $_adminRouterValidate = null;

    /**
     * 后台路由模型
     */
    private $_adminRouterModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->_adminRouterValidate = new AdminRouterValidate();
        $this->_adminRouterModel    = new AdminRouterModel();
    }

    /**
     * 后台路由新增
     * @return string
     */
    public function createAdminRouter()
    {
        $post = $this->request->post();
        $param = $post['param'];
        $param = json_decode($param,true);
        if (!$param) {
            return $this->json(40000,'路由配置项不是json格式');
        }
        if ($this->_adminRouterValidate->check($post)) {
            try {
                $adminRouter = $this->_adminRouterModel->getAdminRouter($post);
                if ($adminRouter) {
                    return $this->json(40000,'该路由已经存在');
                }
                $adminRouterId = $this->_adminRouterModel->createAdminRouter($post);
                if ($adminRouterId) {
                    $data = $this->_adminRouterModel->getAdminRouterTree();
                    $data = array(
                        'routers_tree'    => $data['routers_tree'],
                        'admin_router_id' => $adminRouterId
                    );
                    return $this->json(20000,'新增成功',$data);
                } else {
                    return $this->json(50000,'新增失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, $e->getMessage());
            }

        } else {
            return $this->json(40000,$this->_adminRouterValidate->getError());
        }
    }

    /**
     * 后台路由修改
     * @return string
     */
    public function updateAdminRouter()
    {
        $post  = $this->request->post();
        $param = $post['param'];
        $param = json_decode($param,true);
        $adminRouterId = (int) $post['admin_router_id'];
        if (!$param) {
            return $this->json(40000,'路由配置项不是json格式');
        }
        if (!is_int($adminRouterId) && $adminRouterId < 0) {
            return $this->json(40000,'ID参数错误');
        }
        if ($this->_adminRouterValidate->check($post)) {
            try {
                $adminRouter = $this->_adminRouterModel->getAdminRouter($post);
                if ($adminRouter && $adminRouter['admin_router_id'] !== $adminRouterId) {
                    return $this->json(40000,'该路由已经存在');
                }
                $res = $this->_adminRouterModel->updateAdminRouter($adminRouterId, $post);
                if ($res) {
                    $data = $this->_adminRouterModel->getAdminRouterTree();
                    return $this->json(20000,'修改成功',$data);
                } else {
                    return $this->json(50000,'修改失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, $e->getMessage());
            }

        } else {
            return $this->json(40000,$this->_adminRouterValidate->getError());
        }
    }

    /**
     * 后台路由删除
     * @return false|string
     */
    public function deleteAdminRouter()
    {
        $post = $this->request->post();
        isset($post['admin_router_id']) ?  $adminRouterId=$post['admin_router_id'] : $adminRouterId=0;
        if (empty($adminRouterId) && !is_int($adminRouterId) && $adminRouterId < 0) {
            return $this->json(40000,'参数ID错误');
        }
        try {
            $res = $this->_adminRouterModel->deleteAdminRouter($adminRouterId);
            if ($res) {
                $data = $this->_adminRouterModel->getAdminRouterTree();
                return $this->json(20000, '删除成功', $data);
            } else {
                return $this->json(50000, '删除失败');
            }
        } catch (\Exception $e) {
            return $this->json(50000,'删除失败');
        }
    }

    /**
     * 后台路由排序
     * @return false|string
     */
    public function updateAdminRouterSort()
    {
        $sort = $this->request->post('sort','');
        try {
            foreach ($sort as $key=>$value) {
                $this->_adminRouterModel->updateAdminRouter((int)$value, array('sort'=>$key));
            }
            return $this->json(20000, '排序成功');
        } catch (\Exception $e) {
            return $this->json(50000,'排序失败');
        }

    }

    /**
     * 获取路由树
     * @return false|string
     */
    public function getAdminRouterTree()
    {
        $data = $this->_adminRouterModel->getAdminRouterTree();
        return $this->json(20000,'获取成功',$data);
    }

    /**
     * 获取单条路由
     * @param int $id
     * @return false|string
     */
    public function getAdminRouter($id=0)
    {
        if (!is_int($id) && $id <= 0) {
            return $this->json(40000,'ID参数错误');
        }
        $data  = $this->_adminRouterModel->getAdminRouter(array('admin_router_id'=>$id));
        return $this->json(20000,'获取成功',$data);
    }

}
