#!/usr/bin/env php
<?php
/**

title=测试 adminModel->getHasPrivLink();
timeout=0
cid=1

- 获取功能配置第一个有权限访问的链接
 -  @custom
 - 属性1 @mode
- 获取人员管理第一个有权限访问的链接
 -  @dept
 - 属性1 @browse
- 获取模型配置第一个有权限访问的链接
 -  @custom
 - 属性1 @required
- 获取功能配置第一个有权限访问的链接
 -  @custom
 - 属性1 @set
- 获取通知设置第一个有权限访问的链接
 -  @mail
 - 属性1 @edit
- 获取二次开发第一个有权限访问的链接
 -  @dev
 - 属性1 @api

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/admin.class.php';

zdTable('user')->gen(5);
su('admin');

$adminTester = new adminTest();
$subMenu['system'] = $lang->admin->menuList->system['subMenu'];
$menuList = array('system', 'company', 'model', 'feature', 'message', 'dev');

r($adminTester->getHasPrivLinkTest($menuList[0])) && p('0,1') && e('custom,mode');     // 获取功能配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[1])) && p('0,1') && e('dept,browse');     // 获取人员管理第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[2])) && p('0,1') && e('custom,required'); // 获取模型配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[3])) && p('0,1') && e('custom,set');      // 获取功能配置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[4])) && p('0,1') && e('mail,edit');       // 获取通知设置第一个有权限访问的链接
r($adminTester->getHasPrivLinkTest($menuList[5])) && p('0,1') && e('dev,api');         // 获取二次开发第一个有权限访问的链接
