<?php
/**
 * Created by PhpStorm.
 * User: JS_chen
 * Date: 2019/4/27
 * Time: 16:37
 */

Route::post('/user/login','admin_api/AdminUser/login');

Route::group('', function () {
    // 用户信息
    Route::get('/user/info','admin_api/AdminUser/info');
    // 用户上传头像
    Route::post('/user/upload-avatar','admin_api/AdminUser/uploadAvatar');

    // 系统管理
    Route::group('/system', function () {

        // 管理员
        Route::group('/user', function () {
            Route::post('/create-adminuser','admin_api/AdminUser/createAdminUser');
            Route::post('/update-adminuser-active','admin_api/AdminUser/updateAdminUserActive');
            Route::post('/update-adminuser-role','admin_api/AdminUser/updateAdminUserRole');
            Route::post('/update-adminuser-password','admin_api/AdminUser/updateAdminUserPassword');
            Route::post('/update-user-password','admin_api/AdminUser/updateUserPassword');
            Route::get('/adminuser-list','admin_api/AdminUser/getAdminUserList');
        });

        // 路由管理
        Route::group('/router', function () {
            Route::get('/router-tree','admin_api/AdminRouter/getAdminRouterTree');
            Route::get('/get-admin-router','admin_api/AdminRouter/getAdminRouter');
            Route::post('/create-router','admin_api/AdminRouter/createAdminRouter');
            Route::post('/update-router','admin_api/AdminRouter/updateAdminRouter');
            Route::post('/delete-router','admin_api/AdminRouter/deleteAdminRouter');
            Route::post('/update-router-sort','admin_api/AdminRouter/updateAdminRouterSort');
        });

        // 按钮管理
        Route::group('/button', function () {
            Route::get('/button-list','admin_api/SystemButton/getSystemButtonList');
            Route::get('/button-all','admin_api/SystemButton/getSystemButtonAll');
            Route::post('/create-button','admin_api/SystemButton/createSystemButton');
            Route::post('/update-button','admin_api/SystemButton/updateSystemButton');
            Route::post('/delete-button','admin_api/SystemButton/deleteSystemButton');
            Route::post('/update-button-enable','admin_api/SystemButton/updateSystemButtonEnable');
        });

        // 权限列表
        Route::get('/auth-list','admin_api/Auth/getAuthList');

        // 角色管理
        Route::group('/role', function () {
            Route::get('get-role','admin_api/Role/getRole');
            Route::get('/role-list','admin_api/Role/getRoleList');
            Route::post('/create-role','admin_api/Role/createRole');
            Route::post('/update-role','admin_api/Role/updateRole');
            Route::post('/delete-role','admin_api/Role/deleteRole');
        });
    });
})->middleware(['checkToken']);


