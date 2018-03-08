/*
Navicat MySQL Data Transfer

Source Server         : 新服务器
Source Server Version : 50635
Source Host           : 211.149.155.66:3306
Source Database       : mp_kewo

Target Server Type    : MYSQL
Target Server Version : 50635
File Encoding         : 65001

Date: 2018-03-08 16:55:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mc_navs
-- ----------------------------
DROP TABLE IF EXISTS `mc_navs`;
CREATE TABLE `mc_navs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(11) DEFAULT NULL COMMENT '标题',
  `icon` varchar(255) DEFAULT NULL COMMENT 'icon',
  `href` varchar(255) DEFAULT NULL,
  `spread` tinyint(1) DEFAULT '0',
  `sort` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `bgpath` varchar(255) DEFAULT NULL COMMENT '背景图片地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Table structure for mc_role_nav
-- ----------------------------
DROP TABLE IF EXISTS `mc_role_nav`;
CREATE TABLE `mc_role_nav` (
  `nav_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_role_user
-- ----------------------------
DROP TABLE IF EXISTS `mc_role_user`;
CREATE TABLE `mc_role_user` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Table structure for mc_roles
-- ----------------------------
DROP TABLE IF EXISTS `mc_roles`;
CREATE TABLE `mc_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1' COMMENT '激活0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Table structure for mc_users
-- ----------------------------
DROP TABLE IF EXISTS `mc_users`;
CREATE TABLE `mc_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `logs` int(11) DEFAULT '0' COMMENT '登录次数',
  `created_at` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT '1' COMMENT '1可用0禁用',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

