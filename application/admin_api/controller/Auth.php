<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/5/31
 * Time: 23:22
 */

namespace app\admin_api\controller;


use app\common\AdminApiController;
use think\Db;

class Auth extends AdminApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAuthList()
    {
        $params = $this->request->get();
        $page   = 1;
        $row    = 20;
        $condition  = '';
        $this->_makeCondition($params, $condition, $page, $row);
        try {
            $router = Db::table('admin_router')->field('admin_router_id AS id,title,create_time,update_time,"路由" AS type')
                ->buildSql();
            $button = Db::table('system_button')->where('is_enable',1)
                ->field('button_id AS id,title,create_time,update_time,"按钮" AS type')
                ->union($router)->buildSql();
            $paginate = Db::table($button.' a')->where($condition)->order('create_time DESC')->page($page, $row);
            $auths = $paginate->all();
            $pages = array(
                'current_page' => (int)$page,
                'last_page'    => (int)$page + 1 ,
                'per_page'     => (int)$row,
                'total'        => $paginate->count(),
            );
            $data = array(
                'pages' => $pages,
                'auths' => $auths
            );
            return $this->json(20000, '获取成功', $data);
        } catch (\Exception $e) {
            return $this->json(50000, $e->getMessage());
        }
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

        // 权限名称
        if (isset($params['title']) && !empty($params['title'])) {
            $condition .= "title LIKE '%{$params['title']}%'";
        }

        // 权限类型
        if (isset($params['type']) && in_array($params['type'],['路由', '按钮'])) {
            $condition && $condition .= ' AND ';
            $condition .= "type = '{$params['type']}'";
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
