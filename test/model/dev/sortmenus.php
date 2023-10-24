#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';
$ztLang = zdTable('lang');
$ztLang->id->range('1');
$ztLang->gen(0);

/**

title=测试 devModel::sortMenus();
cid=1
pid=1

检查传入空值时的情况 >> 0
检查传入产品导航顺序 >> dashboard,story,plan,project,release,roadmap,track,doc,dynamic,settings,

*/

global $tester;
$menuList[0] = array();
$menuList[1] = $tester->lang->product->menu;

$devTester = new devTest();
r($devTester->sortMenusTest($menuList[0])) && p() && e('0');                                                                              // 检查传入空值时的情况
r($devTester->sortMenusTest($menuList[1])) && p() && e('dashboard,story,plan,project,release,roadmap,track,doc,dynamic,settings,'); // 检查传入产品导航顺序
