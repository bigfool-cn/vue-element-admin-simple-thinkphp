/*
 Navicat Premium Data Transfer

 Source Server         : 阿里云
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : 139.224.11.85:3306
 Source Schema         : vue_element_admin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 19/06/2019 20:34:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_router
-- ----------------------------
DROP TABLE IF EXISTS `admin_router`;
CREATE TABLE `admin_router`  (
  `admin_router_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '路由配置项',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父类ID',
  `sort` int(10) NOT NULL DEFAULT 0 COMMENT '排序 代表所在位置，越小越靠前',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`admin_router_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_router
-- ----------------------------
INSERT INTO `admin_router` VALUES (2, '系统管理', '{\n  \"path\": \"/system\",\n  \"component\": \"Layout\",\n  \"redirect\": \"noRedirect\",\n  \"name\": \"System\",\n  \"alwaysShow\": true,\n  \"meta\": {\n    \"title\": \"系统管理\",\n    \"icon\": \"system\"\n  }\n}', 0, 2, '2019-06-12 11:11:09', '2019-05-25 21:25:06');
INSERT INTO `admin_router` VALUES (3, '动态路由', '{\n  \"path\": \"/system/router\",\n  \"component\": \"/system/router\",\n  \"name\": \"Router\",\n  \"meta\": {\n    \"title\": \"动态路由\"\n  }\n}', 2, 0, '2019-06-12 11:11:31', '2019-05-25 21:34:20');
INSERT INTO `admin_router` VALUES (6, '按钮管理', '{\n  \"path\": \"/system/button\",\n  \"component\": \"/system/button\",\n  \"name\": \"Button\",\n  \"meta\": {\n    \"title\": \"按钮管理\"\n  }\n}', 2, 1, '2019-06-12 11:11:08', '2019-05-25 21:40:35');
INSERT INTO `admin_router` VALUES (15, '用户管理', '{\n  \"path\": \"/system/user-manage\",\n  \"name\": \"UserManage\",\n  \"component\": \"/system/user-manage\",\n  \"redirect\": \"noRedirect\",\n  \"alwaysShow\": true,\n  \"meta\": {\n    \"title\": \"用户管理\"\n  }\n}', 2, 2, '2019-06-12 11:11:08', '2019-05-31 20:03:45');
INSERT INTO `admin_router` VALUES (17, '管理员', '{\n  \"path\": \"/system/user-manage/adminuser\",\n  \"name\": \"AdminUser\",\n  \"component\": \"/system/user-manage/adminuser\",\n  \"meta\": {\n    \"title\": \"管理员\"\n  }\n}', 15, 0, '2019-06-11 17:38:07', '2019-05-31 20:06:25');
INSERT INTO `admin_router` VALUES (18, '权限管理', '{\n  \"path\": \"/system/auth-manage\",\n  \"name\": \"AuthManage\",\n  \"component\": \"/system/auth-manage\",\n  \"alwaysShow\": true,\n  \"redirect\": \"noRedirect\",\n  \"meta\": {\n    \"title\": \"权限管理\"\n  }\n}', 2, 3, '2019-06-12 11:11:08', '2019-05-31 20:35:14');
INSERT INTO `admin_router` VALUES (19, '角色管理', '{\n  \"path\": \"/system/auth-manage/role\",\n  \"name\": \"Role\",\n  \"component\": \"/system/auth-manage/role\",\n  \"meta\": {\n    \"title\": \"角色管理\"\n  }\n}', 18, 0, '2019-06-11 17:57:55', '2019-05-31 20:40:01');
INSERT INTO `admin_router` VALUES (22, '权限列表', '{\n  \"path\": \"/system/auth-manage/auth\",\n  \"name\": \"Auth\",\n  \"component\": \"/system/auth-manage/auth\",\n  \"meta\": {\n    \"title\": \"权限列表\"\n  }\n}', 18, 3, '2019-06-11 17:57:55', '2019-06-01 21:20:02');
INSERT INTO `admin_router` VALUES (23, '管理员--新增', '{\n  \"path\": \"/system/user-manage/adminuser/add\",\n  \"name\": \"AdminUserAdd\",\n  \"component\": \"/system/user-manage/adminuser/add\",\n  \"hidden\": true,\n  \"meta\": {\n    \"title\": \"管理员--新增\"\n  }\n}', 15, 0, '2019-06-11 17:54:29', '2019-06-11 13:29:17');
INSERT INTO `admin_router` VALUES (24, '角色管理--新增', '{\n  \"path\": \"/system/auth-manage/role/add\",\n  \"name\": \"RoleAdd\",\n  \"component\": \"/system/auth-manage/role/add\",\n  \"hidden\": true,\n  \"meta\": {\n    \"title\": \"角色管理--新增\"\n  }\n}', 18, 1, '2019-06-11 17:57:55', '2019-06-11 13:31:07');
INSERT INTO `admin_router` VALUES (26, '角色管理--修改', '{\n  \"path\": \"/system/auth-manage/role/edit\",\n  \"name\": \"RoleEdit\",\n  \"component\": \"/system/auth-manage/role/edit\",\n  \"hidden\": true,\n  \"meta\": {\n    \"title\": \"角色管理--修改\"\n  }\n}', 18, 2, '2019-06-11 17:59:22', '2019-06-11 17:57:48');

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `admin_user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `password` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg' COMMENT '头像',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '激活状态 1已激活 0未激活',
  `update_time` datetime(0) NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL,
  `login_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', '$2y$10$S.da./vQ5sKF8DujTHR4IOWVPAZBBf54TjYSG7wRGPMpvpNltWlui', '/uploads/user-avatar/20190614/b94e3627588b373e36b57e614420ef9b.jpg', 1, '2019-06-19 20:20:29', '2019-04-28 11:27:32', '2019-06-19 20:20:29');
INSERT INTO `admin_user` VALUES (2, 'admin1', '$2y$10$qnDaeLLvrwx7Co1ibQQ5xOPsDOW0YqJUbYjCWqWuNiYI449ddkc16', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 1, '2019-06-11 21:50:41', '2019-04-30 15:16:23', '2019-06-11 21:40:22');
INSERT INTO `admin_user` VALUES (3, 'admin2', '$2y$10$fUb3EG/5LQlxPavuxP/Zx.9t0fUslCr/GRqeLDXeIh17A5KyGGkCC', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, '2019-05-20 21:44:38', '2019-04-30 15:16:23', '2019-04-30 15:16:23');
INSERT INTO `admin_user` VALUES (4, 'admin3', '$2y$10$UphpmjVp8AFVy6PrfI9q0.8pjGvzZW/QqvMKvTAwGQVTO058mzzyG', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, '2019-06-05 18:27:45', '2019-04-30 15:16:23', '2019-04-30 15:16:23');
INSERT INTO `admin_user` VALUES (5, 'admin4', '$10$35lD.hqVCD4mGP7bv4wP5.1/PJrlhWSaEOsvmGO7LDEDYDhCJJ9S6', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-04-30 15:16:23', '2019-04-30 15:16:23');
INSERT INTO `admin_user` VALUES (6, 'admin5', '$2y$10$3bJnGs1M07gVPjMffP7zY.HvigFHzF8NxEOgMcveIsH0JUokMSlti', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 15:43:35', NULL);
INSERT INTO `admin_user` VALUES (7, 'admin6', '$2y$10$o61Yz8bbVd7EiWme2OYLIu7rPVGclwvlnG07xT5Cfn1hNLYkMDVQu', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 15:48:49', NULL);
INSERT INTO `admin_user` VALUES (8, 'admin7', '$2y$10$H5lUTPZDtCpBLfSdTalAzegbotVQqCumcmc2TU3817sOMn6nkFeKW', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 15:49:47', NULL);
INSERT INTO `admin_user` VALUES (9, 'admin9', '$2y$10$o7hhBlYAKPSfLRTU7x.iye4UQDqowcuoy0Udu5J94J6j4ahDR8Zsm', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 15:52:12', NULL);
INSERT INTO `admin_user` VALUES (10, 'admin10', '$2y$10$Aq8mEuDhZ7uU5XPn7WQUW.wCoWn28DmjOLfLwj7ZtJFxNmkPsAVSW', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 16:02:57', NULL);
INSERT INTO `admin_user` VALUES (11, 'admin11', '$2y$10$kdAdc3A2lbtZbqBzALG8XeI7HYoNQtcpLj6DabnhxjPQEHWNkN2Y2', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, NULL, '2019-05-19 16:11:49', NULL);
INSERT INTO `admin_user` VALUES (12, 'admin12', '$2y$10$9hPP9CvVWuUfqHVQpDEmkukpCDSiZ93vNhgCEhNexRw8SWQ2jaDJK', '/uploads/user-avatar/20190610/16831da49ea144074f2b523166ccdab2.jpg', 0, '2019-06-16 11:06:44', '2019-06-12 14:47:34', NULL);

-- ----------------------------
-- Table structure for admin_user_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_role`;
CREATE TABLE `admin_user_role`  (
  `admin_user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin_user_role
-- ----------------------------
INSERT INTO `admin_user_role` VALUES (1, 1);
INSERT INTO `admin_user_role` VALUES (6, 3);
INSERT INTO `admin_user_role` VALUES (2, 3);
INSERT INTO `admin_user_role` VALUES (12, 3);
INSERT INTO `admin_user_role` VALUES (12, 4);
INSERT INTO `admin_user_role` VALUES (7, 7);
INSERT INTO `admin_user_role` VALUES (10, 6);
INSERT INTO `admin_user_role` VALUES (11, 5);
INSERT INTO `admin_user_role` VALUES (11, 13);

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `role_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名称',
  `router_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '路由ID',
  `button_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '按钮ID',
  `desc` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '角色描述',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, '超级管理员', '[2,3,6,15,17,20,18,19,21,22,23,24,26]', '[2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18]', '拥有系统全部权限', '2019-06-12 18:00:26', '2019-06-03 16:36:59');
INSERT INTO `role` VALUES (3, '观察者2', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (4, '观察者4', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (5, '观察者5', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (6, '观察者6', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (7, '观察者7', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (8, '观察者8', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (9, '观察者9', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (10, '观察者10', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (11, '观察者11', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (12, '观察者12', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');
INSERT INTO `role` VALUES (13, '观察者13', '[2,6,15,17,18,19,22]', '[]', '拥有查看权限', '2019-06-11 21:39:55', '2019-06-05 17:34:13');

-- ----------------------------
-- Table structure for system_button
-- ----------------------------
DROP TABLE IF EXISTS `system_button`;
CREATE TABLE `system_button`  (
  `button_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一标识',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '按钮名称',
  `is_enable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否可用',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`button_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_button
-- ----------------------------
INSERT INTO `system_button` VALUES (3, 'button_add', '按钮管理--新增', 1, '2019-06-01 11:18:39', NULL);
INSERT INTO `system_button` VALUES (4, 'button_edit', '按钮管理--修改', 1, '2019-06-01 11:19:11', NULL);
INSERT INTO `system_button` VALUES (5, 'button_enable', '按钮管理--可用状态', 1, '2019-06-01 11:19:46', NULL);
INSERT INTO `system_button` VALUES (6, 'router_add', '路由管理--新增', 1, '2019-06-01 11:21:04', NULL);
INSERT INTO `system_button` VALUES (7, 'router_delete', '路由管理--删除', 1, '2019-06-01 11:21:39', '2019-06-11 19:53:47');
INSERT INTO `system_button` VALUES (8, 'router_save', '路由管理--保存', 1, '2019-06-01 11:22:02', NULL);
INSERT INTO `system_button` VALUES (9, 'button_delete', '按钮管理--删除', 1, '2019-06-01 11:22:21', NULL);
INSERT INTO `system_button` VALUES (10, 'adminuser_add', '管理员--新增', 1, '2019-06-01 11:23:16', NULL);
INSERT INTO `system_button` VALUES (11, 'adminuser_edit_pwd', '管理员--修改密码', 1, '2019-06-01 11:24:32', '2019-06-01 11:30:31');
INSERT INTO `system_button` VALUES (12, 'adminuser_delete', '管理员--删除', 1, '2019-06-01 11:26:58', NULL);
INSERT INTO `system_button` VALUES (13, 'adminuser_export', '管理员--导出', 1, '2019-06-01 11:27:23', NULL);
INSERT INTO `system_button` VALUES (14, 'role_add', '角色管理--新增', 1, '2019-06-01 11:28:27', NULL);
INSERT INTO `system_button` VALUES (15, 'role_edit', '角色管理--修改', 1, '2019-06-01 11:28:50', NULL);
INSERT INTO `system_button` VALUES (16, 'role_delete', '角色管理--删除', 1, '2019-06-01 11:29:18', NULL);
INSERT INTO `system_button` VALUES (17, 'profile_upload_avatar', '个人信息--头像上传', 1, '2019-06-11 21:58:05', NULL);
INSERT INTO `system_button` VALUES (18, 'adminuser_role', '管理员--分配角色', 1, '2019-06-12 18:00:16', NULL);

SET FOREIGN_KEY_CHECKS = 1;
