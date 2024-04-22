#!/usr/bin/env php
<?php
/**

title=测试 customModel->buildMenuItems();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(10);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->buildMenuItemsTest('main'))    && p() && e('0'); // 构造一级菜单
r($customTester->buildMenuItemsTest('product')) && p() && e('0'); // 构造产品菜单
r($customTester->buildMenuItemsTest('my'))      && p() && e('0'); // 构造地盘菜单

zenData('lang')->gen(0);
