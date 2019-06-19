<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/4/27
 * Time: 16:36
 */

namespace app\admin_api\controller;

use app\admin_api\model\AdminUser as AdminUserModel;
use app\admin_api\model\AdminUserRole as AdminUserRoleModel;
use app\admin_api\validate\AdminUserValidate;
use app\admin_api\validate\LoginValidate;
use app\common\AdminApiController;


class AdminUser extends AdminApiController
{
    /**
     * 登录验证器
     * @var null
     */
    private $_loginValidate = null;

    /**
     * 后台用户信息验证器
     * @var null
     */
    private $_adminUserValidate = null;

    /**
     * 后台用户模型
     * @var null
     */
    private $_adminUserModel = null;

    /**
     * 用户角色模型
     * @var null
     */
    private $_adminUserRoleModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->_loginValidate      = new LoginValidate();
        $this->_adminUserValidate  = new AdminUserValidate();
        $this->_adminUserModel     = new AdminUserModel();
        $this->_adminUserRoleModel = new AdminUserRoleModel();
    }

    /**
     * 后台登录
     * @return string
     */
    public function login()
    {
        $form = $this->request->post();
        if ($this->_loginValidate->check($form)) {
            $adminUser = $this->_adminUserModel->getAdminUser(array('username'=>$form['username']));
            if (empty($adminUser)) {
                return $this->json(40003,'用户不存在');
            }
            if (!$adminUser['is_active']) {
                return $this->json(40003,'用户未激活');
            }
            if (!password_verify($form['password'], $adminUser['password'])) {
                return $this->json(40003,'密码错误');
            }
            // 请求验证token
            $startTime  = time();
            $ExpireTime = time()+7200;
            $userId     = $adminUser['admin_user_id'];
            $userName   = $adminUser['username'];
            $access_token = \app\common\Tools::generateJwt($startTime, $ExpireTime, $userId, $userName);
            $data = array(
                'access_token' => $access_token
            );
            // 记录登录时间
            $this->_adminUserModel->updateAdminUser($userId, array('login_time'=>date('Y-m-d H:i:s')));
            return $this->json(20000,'登录成功', $data);
        } else {
            return $this->json(40004, $this->_loginValidate->getError());
        }
    }

    /**
     * 获取信息
     * @return string
     */
    public function info()
    {
        $userId = $this->request->userId;
        $adminUser = $this->_adminUserModel->getAdminUserById($userId);
        if (!$adminUser) {
            return $this->json(40000,'用户不存在');
        }

        // 获取用户角色权限
        $roles = $routers = $buttons = array();
        $this->_getAdminUserRoleAuth($adminUser['roles'],$roles,$routers,$buttons);

        $data = array(
            'user_id'  => $userId,
            'name'     => $adminUser['username'],
            'avatar'   => $adminUser['avatar'],
            'roles'    => $roles,
            'routers'  => $routers,
            'buttons'  => $buttons
        );
        return $this->json(20000,'ok', $data);
    }

    /**
     * 新增管理员
     */
    public function createAdminUser()
    {
        $post = $this->request->post();
        if ($this->_adminUserValidate->scene('create')->check($post)) {
            try {
                $data = array(
                    'username' => $post['username'],
                    'password' => $post['password']
                );
                $this->_adminUserModel->startTrans();
                $this->_adminUserRoleModel->startTrans();
                $adminUserId = $this->_adminUserModel->createAdminUser($data);
                // 添加用户角色
                $roles = $post['roles'];
                $adminUserRoles = array();
                foreach ($roles as $key=>$role) {
                    $adminUserRoles[] = array('admin_user_id'=>$adminUserId,'role_id'=>$role);
                }
                $res = $this->_adminUserRoleModel->saveAdminUserRole($adminUserRoles);
                if ($adminUserId && $res) {
                    $this->_adminUserModel->commit();
                    $this->_adminUserRoleModel->commit();
                    return $this->json(20000, '新增管理员成功');
                } else {
                    $this->_adminUserModel->rollback();
                    $this->_adminUserRoleModel->rollback();
                    return $this->json(50000, '新增管理员失败');
                }
            } catch (\Exception $e) {
                $this->_adminUserModel->rollback();
                $this->_adminUserRoleModel->rollback();
                return $this->json(50000, $e->getMessage());
            }
        } else {
            return $this->json(40003, $this->_adminUserValidate->getError());
        }
    }

    /**
     * 更新用户角色
     * @return string
     * @throws \think\exception\PDOException
     */
    public function updateAdminUserRole()
    {
        $post = $this->request->post();
        if ($this->_adminUserValidate->scene('update_role')->check($post)) {
            $adminUserId = $post['admin_user_id'];
            if ($adminUserId == 1) {
                return $this->json(40003, '超级管理员角色不可更改');
            }
            $data = array();
            foreach ($post['roles'] as $key=>$role) {
                $data[] = array('admin_user_id'=>$adminUserId,'role_id'=>$role);
            }
            try {
                $adminUser = $this->_adminUserModel->getAdminUserById($adminUserId);
                if (!$adminUser) {
                    return $this->json(40000,'用户不存在');
                }
                $this->_adminUserRoleModel->startTrans();

                $delRes = $this->_adminUserRoleModel->deleteAdminRole(array('admin_user_id'=>$adminUserId));
                $saveRes = $this->_adminUserRoleModel->saveAdminUserRole($data);

                if ($delRes && $saveRes) {
                    $this->_adminUserRoleModel->commit();
                    return $this->json(20000, '分配角色成功');
                } else {
                    $this->_adminUserRoleModel->rollback();
                    return $this->json(50000, '分配角色失败');
                }
            } catch (\Exception $e) {
                $this->_adminUserRoleModel->rollback();
                return $this->json(50000, '分配角色失败');
            }
        } else {
            return $this->json(40003, $this->_adminUserValidate->getError());
        }
    }

    /**
     * 获取用户角色权限
     * @param $rolesModel
     * @param $roles
     * @param $routers
     * @param $buttons
     */
    private function _getAdminUserRoleAuth($rolesModel, &$roles, &$routers, &$buttons)
    {
        $routerIds = $buttonIds = array();
        foreach ($rolesModel as $key=>$value) {
            $routerIds = array_merge($routerIds, json_decode($value['router_ids'],true));
            $buttonIds = array_merge($buttonIds, json_decode($value['button_ids'],true));
            array_push($roles, $value['role_name']);
        }
        // 获取权限
        $roleModel = new \app\admin_api\model\Role();
        $auths     = $roleModel->getRoleAuths($routerIds, $buttonIds);
        $routers   = $auths['routers'];
        $buttons   = $auths['buttons'];

    }

    /**
     * 更新管理员激活状态
     * @return false|string
     */
    public function updateAdminUserActive()
    {
        $post = $this->request->post();
        if ($this->_adminUserValidate->scene('update_active')->check($post)) {
            $data = array('is_active'=>!$post['is_active']);
            try {
                if ($post['admin_user_id'] == 1) {
                    return $this->json(40003, '超级管理员激活状态不可更改');
                }
                $res = $this->_adminUserModel->updateAdminUser($post['admin_user_id'], $data);
                if ($res) {
                    return $this->json(20000, '更新激活状态成功');
                } else {
                    return $this->json(50000, '更新激活状态失败');
                }
            } catch (\Exception $e) {
                return $this->json(50000, '更新激活状态失败');
            }

        } else {
            return $this->json(40003, $this->_adminUserValidate->getError());
        }
    }

    /**
     * 修改密码
     * @return false|string
     */
    public function updateAdminUserPassword()
    {
        $post = $this->request->post();
        if ($this->_adminUserValidate->scene('update_password')->check($post)) {
            try {
                $data = array('password'=>password_hash($post['password'], PASSWORD_BCRYPT));
                $res = $this->_adminUserModel->updateAdminUser($post['admin_user_id'], $data);
                if ($res) {
                    return $this->json(20000, '修改密码成功');
                } else {
                    return $this->json(50000, '修改密码失败');
                }
            } catch (\Exception $e) {
                return $this->json(5000, '修改密码失败');
            }
        } else {
            return $this->json(40003, $this->_adminUserValidate->getError());
        }
    }

    /**
     * 用户修改密码
     * @return false|string
     */
    public function updateUserPassword()
    {
        $post = $this->request->post();
        if ($this->_adminUserValidate->scene('update_user_pwd')->check($post)) {
            try {
                $condition = array(
                    'admin_user_id' => $post['admin_user_id']
                );
                $adminUser = $this->_adminUserModel->getAdminUser($condition);
                if (!$adminUser) {
                    return $this->json(40003, '用户不存在');
                }
                if (!password_verify($post['old_password'], $adminUser['password'])) {
                    return $this->json(40003,'旧密码不正确');
                }
                $data = array('password'=>password_hash($post['password'], PASSWORD_BCRYPT));
                $res = $this->_adminUserModel->updateAdminUser($post['admin_user_id'], $data);
                if ($res) {
                    return $this->json(20000, '修改密码成功');
                } else {
                    return $this->json(50000, '修改密码失败');
                }
            } catch (\Exception $e) {
                return $this->json(5000, '修改密码失败');
            }
        } else {
            return $this->json(40003, $this->_adminUserValidate->getError());
        }
    }


    /**
     * 用户上传头像
     * @return string
     */
    public function uploadAvatar()
    {
        $file = $this->request->file('file');
        $adminUserId = $this->request->post('user_id',0);
        if (!$adminUserId) {
            return $this->json(40000,'ID不存在');
        }
        $adminUser = $this->_adminUserModel->getAdminUserById($adminUserId);
        if (!$adminUser) {
            return $this->json(40000,'用户不存在或已删除');
        }
        @unlink(config('app_dir') . $adminUser['avatar']);
        $uploadDir = '/uploads/user-avatar/';
        $info = $file->validate(['size'=>2 * 1024 * 1024,'ext'=>'jpg,png,gif,jpeg'])->move('.' .  $uploadDir);
        if (!$info) {
            return $this->json(50000,'上传失败，'.$file->getError());
        }
        $avatar =  $uploadDir . $info->getSaveName();
        try {
            $res = $this->_adminUserModel->updateAdminUser($adminUserId,array('avatar'=>$avatar));
            if ($res) {
                $data = array(
                    'avatar' => config('app_host') . $avatar
                );
                return $this->json(20000,'上传成功', $data);
            } else {
                return $this->json(50000,'上传失败');
            }
        } catch (\Exception $e) {
            return $this->json(50000,'上传失败');
        }
    }

    /**
     * 获取管理员列表
     * @return string
     */
    public function getAdminUserList()
    {
        $params = $this->request->get();
        $page   = 1;
        $row    = 20;
        $condition  = '';
        $this->_makeCondition($params, $condition, $page, $row);
        $data = $this->_adminUserModel->getAdminUserPage($page, $row, $condition);
        return $this->json(20000,'ok', $data);
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

        // 用户名称
        if (isset($params['username']) && !empty($params['username'])) {
            $condition .= "username LIKE '%{$params['username']}%'";
        }

        // 激活状态
        if (isset($params['is_active']) && $params['is_active'] !== '' && in_array($params['is_active'], [0, 1])) {
            $condition && $condition .= ' AND ';
            $condition .= "is_active = {$params['is_active']}";
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
