#!/usr/bin/env php
<?php
/**

title=测试 customModel->getModuleMenu();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->getModuleMenuTest('main'))    && p() && e('0'); // 获取一级菜单
r($customTester->getModuleMenuTest('product')) && p() && e('0'); // 获取产品菜单
r($customTester->getModuleMenuTest('my'))      && p() && e('0'); // 获取地盘菜单

zenData('lang')->gen(0);
