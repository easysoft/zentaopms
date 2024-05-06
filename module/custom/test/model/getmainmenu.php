#!/usr/bin/env php
<?php
/**

title=测试 customModel->getMainMenu();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->getMainMenuTest()) && p() && e('0'); // 获取主菜单

zenData('lang')->gen(0);
