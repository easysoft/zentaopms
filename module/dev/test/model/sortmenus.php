#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';
$ztLang = zenData('lang');
$ztLang->id->range('1');
$ztLang->gen(0);

/**

title=测试 devModel::sortMenus();
cid=1
pid=1

检查传入空值时的情况 >> 0
检查传入产品导航顺序 >> dashboard,epic,requirement,story,plan,project,release,roadmap,track,doc,dynamic,settings,orroadmap,

*/

global $tester;
$menuList[0] = array();
$menuList[1] = $tester->lang->product->menu;

$devTester = new devTest();
r($devTester->sortMenusTest($menuList[0])) && p() && e('0');                                                                              // 检查传入空值时的情况
r($devTester->sortMenusTest($menuList[1])) && p() && e('dashboard,epic,requirement,story,plan,project,release,roadmap,track,doc,dynamic,settings,orroadmap,'); // 检查传入产品导航顺序
