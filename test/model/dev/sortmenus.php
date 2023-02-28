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
检查传入地盘导航顺序 >> index,calendar,work,audit,project,execution,contribute,dynamic,score,contacts,

*/

global $tester;
$menuList[0] = array();
$menuList[1] = $tester->lang->my->menu;

$devTester = new devTest();
r($devTester->sortMenusTest($menuList[0])) && p() && e('0');                                                                              // 检查传入空值时的情况
r($devTester->sortMenusTest($menuList[1])) && p() && e('index,calendar,work,audit,project,execution,contribute,dynamic,score,contacts,'); // 检查传入地盘导航顺序
