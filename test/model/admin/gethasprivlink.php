#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/admin.class.php';
su('admin');

/**

title=测试 adminModel->getHasPrivLink();
cid=1
pid=1

获取功能配置第一个有权限访问的链接 >> custom,mode
获取人员管理第一个有权限访问的链接 >> dept,browse
获取模型配置第一个有权限访问的链接 >> custom,required
获取功能配置第一个有权限访问的链接 >> custom,set
获取通知设置第一个有权限访问的链接 >> mail,edit
获取二次开发第一个有权限访问的链接 >> dev,api

*/
$adminTester = new adminTest();

$subMenu['system'] = $lang->admin->menuList->system['subMenu'];
$menuList = array('system', 'user', 'model', 'feature', 'message', 'dev');
r($adminTester->getHasPrivLinkTest($menuList[0])) && p('0,1') && e('custom,mode');     // 获取功能配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[1])) && p('0,1') && e('dept,browse');     // 获取人员管理第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[2])) && p('0,1') && e('custom,required'); // 获取模型配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[3])) && p('0,1') && e('custom,set');      // 获取功能配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[4])) && p('0,1') && e('mail,edit');       // 获取通知设置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[5])) && p('0,1') && e('dev,api');         // 获取二次开发第一个有权限访问的链接
